<?php

namespace App\Http\Controllers;

use App\Enums\GroupState;
use Carbon\Carbon;
use App\Models\Project;
use App\Enums\ProjectType;
use App\Enums\ProjectState;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Enums\Specialization;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Enum;
use Laravel\Socialite\Facades\Socialite;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:project-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:project-supervise', ['only' => ['supervise', 'unsupervise']]);
        $this->middleware('permission:project-approve', ['only' => ['approve', 'disapprove']]);
    }
    public function index(Request $request)
    {
        $specs = Specialization::cases();
        $types = ProjectType::cases();
        $states = ProjectState::cases();
        $projects = Project::with('group')->latest()->filter(request(['search', 'spec', 'type', 'state', 'created_from', 'created_to', 'updated_from', 'updated_to']))
            ->paginate(10)->withQueryString();
        return view('projects.index', compact(['projects', 'specs', 'types', 'states']))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function export(Request $request)
    {
        $this->authorize('export', Project::class);
        return Project::with('group')->latest()
            ->filter(request(['search', 'spec', 'type', 'state', 'created_from', 'created_to', 'updated_from', 'updated_to']))
            ->get()->map(function ($project) {
                return [
                    'Title' => $project->title,
                    'Type' => ucfirst($project->type->value),
                    'Spec' => ucfirst($project->spec->value),
                    'State' => ucfirst($project->state->value),
                    'Supervisor' => $project->supervisor->name,
                    'Team' => $project->group ? $project->group->developers->map(function ($user) {
                        return ['name' => $user->name];
                    })->implode('name', ', ') : null,
                    'Created at' => $project->created_at->format('Y/m/d D'),
                    'Updated at' => $project->updated_at->format('Y/m/d D'),
                ];
            })
            ->downloadExcel('projects.xlsx', null, true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $this->authorize('create', Project::class);
    //     $repos = 'https://github.com/walee085296/spms_project#';
    //     $specs = Specialization::cases();
    //     $types = ProjectType::cases();
    //     $states = ProjectState::cases();
    //     return view('projects.create', compact(['specs', 'types', 'states', 'repos']));
    // }

        public function create()
    {
        $this->authorize('create', Project::class); // التأكد من الصلاحية

        // جلب قائمة الريبو من GitHub (إذا محددة في .env)
        $reposUrl = env('GITHUB_ORG', 'https://api.github.com/users/walee085296/repos');

           $repos =   Http::get($reposUrl)->json();

            // Http::withToken(env('GITHUB_TOKEN'))
            //      ->get(env('GITHUB_ORG'))
            //      ->json();
        //    $repos = Http::get("https://api.github.com/users/walee085296/repos")->json();
        // Http::get($reposUrl)->json();

        // تمرير التخصصات وأنواع المشاريع وحالاتها لواجهة المستخدم
        $specs = Specialization::cases();
        $types = ProjectType::cases();
        $states = ProjectState::cases();

        return view('projects.create', compact(['specs', 'types', 'states', 'repos']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request)
{
    $this->authorize('create', Project::class);

    // 🔥 الحصول على آخر جروب للطالب
    $group = $request->user()->groups->last();

    // ----------------------------------------------------------
    // 🔒 الشرط (1): منع إنشاء مشروع بدون مجموعة
    // ----------------------------------------------------------
    if (!$group) {
        return redirect()->back()
            ->with('error', 'You must join a group before creating a project.');
    }

    // ----------------------------------------------------------
    // 🔒 الشرط (2): منع الطالب من إنشاء أكثر من مشروع
    // ----------------------------------------------------------
    if ($group->project_id) {
        return redirect()->back()
            ->with('error', 'Your group already has a project. You cannot create another one.');
    }
    // ----------------------------------------------------------

    $this->validate($request, [
        'title' => 'required|unique:projects,title',
        'type' => [new Enum(ProjectType::class)],
        'spec' => [new Enum(Specialization::class)],
        'state' => [new Enum(ProjectState::class)],
        'aims' => 'required|array|min:1',
        'aims.*' => 'required|string',
        'objectives' => 'required|array|min:1',
        'objectives.*' => 'required|string',
        'tasks' => 'required|array|min:1',
        'tasks.*' => 'required|string',
    ]);

    $aims = collect($request->aims)->transform(fn($aim) => [
        'name' => $aim,
        'complete' => false
    ]);

    $objectives = collect($request->objectives)->transform(fn($objective) => [
        'name' => $objective,
        'complete' => false
    ]);

    $tasks = collect($request->tasks)->transform(fn($task) => [
        'name' => $task,
        'complete' => false
    ]);

    // GitHub Repo (اختياري)
    if (!$request->repo) {
        if ($request->state == ProjectState::Incomplete || $request->state == ProjectState::Evaluating) {
            $response = Http::withToken(env('GITHUB_TOKEN'))->post(env('GITHUB_ORG').'/repos', [
                'name' => Str::slug($request->title),
                'private' => false,
            ]);
            $new_repo = $response->json('url');
        }
        $new_repo = null;
    }

    $project = Project::create([
        'title' => $request->title,
        'url' => $request->repo ?? $new_repo,
        'type' => $request->user()->can('project-create') ? $request->type : $group->project_type,
        'spec' => $request->user()->can('project-create') ? $request->spec : $group->spec ?? 'General',
        'state' => $request->user()->can('project-approve') ? $request->state : ProjectState::Proposition,
        'aims' => json_encode($aims),
        'objectives' => json_encode($objectives),
        'tasks' => json_encode($tasks),
        'supervisor_id' => $request->supervise,
          'readme_url' => $request->readme_url,   // ← هنا
    ]);

    // ربط المشروع بالجروب
    $group->update(['project_id' => $project->id]);

    return redirect()->route('projects.index')
        ->with('success', 'Project created successfully.');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    $project = Project::with('group', 'supervisor')->find($id);

    // 1️⃣ لو مفيش URL من الأساس → رجّع الصفحة بدون GitHub
    if (!$project->url) {
        $markdown = '<p class="text-red-700">لا يوجد رابط GitHub لهذا المشروع.</p>';
        $github = null;
        $languages = [];
        return view('projects.show', compact('project', 'markdown', 'github', 'languages'));
    }

    // 2️⃣ محاولة الاتصال بـ GitHub مع Catch لأي خطأ
    try {
        Http::withToken(env('GITHUB_TOKEN'))->get($project->url)->json();
    } catch (\Exception $e) {
        $github = cache()->get('github' . $id);
        $markdown = cache()->get('markdown' . $id, '<p class="text-red-700">لا يمكن الاتصال بخوادم GitHub الآن. حاول لاحقًا.</p>');
        $languages = cache()->get("languages.$id", []);
        return view('projects.show', compact('project', 'markdown', 'github', 'languages'));
    }

    // 3️⃣ GitHub Request مع Cache
    $github = cache()->remember(
        'github' . $project->id,
        21600,
        fn () => Http::withToken(env('GITHUB_TOKEN'))->get($project->url)->json()
    );

    $markdownResponse = Http::withToken(env('GITHUB_TOKEN'))
        ->accept('application/vnd.github.html')
        ->get($project->url . '/readme');

    $markdown = cache()->remember(
        'markdown' . $project->id,
        21600,
        fn () => $markdownResponse->failed() ? '<p class="text-red-700">لا يمكن جلب ملف README.</p>' : $markdownResponse->body()
    );

    $languages = cache()->remember(
        "languages.$project->id",
        21600,
        fn () => $github && isset($github['languages_url'])
            ? Http::withToken(env('GITHUB_TOKEN'))->get($github['languages_url'])->json()
            : []
    );

    return view('projects.show', compact('project', 'markdown', 'github', 'languages'));
}


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        $this->authorize('edit', $project);
        $repos = Http::get(env('GITHUB_ORG').'/repos')->json();
        $specs = Specialization::cases();
        $types = ProjectType::cases();
        $states = ProjectState::cases();
        return view('projects.edit', compact(['project', 'specs', 'types', 'states', 'repos']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        $this->authorize('edit', $project);
        $this->validate($request, [
            'title' => 'required|unique:projects,title,' . $id,
            'type' => [new Enum(ProjectType::class)],
            'spec' => [new Enum(Specialization::class)],
            'aims' => 'required|array|min:1',
            'aims.*' => 'required|string',
            'objectives' => 'required|array|min:1',
            'objectives.*' => 'required|string',
            'tasks' => 'required|array|min:1',
            'tasks.*' => 'required|string',
        ]);
        $aims = collect($request->aims)->transform(function ($aim) use ($request) {
            return [
                'name' => $aim,
                'complete' => in_array($aim, $request->aims_complete ?? [])
            ];
        });
        $objectives = collect($request->objectives)->transform(function ($objective) use ($request) {
            return [
                'name' => $objective,
                'complete' => in_array($objective, $request->objectives_complete ?? [])
            ];
        });
        $tasks = collect($request->tasks)->transform(function ($task) use ($request) {
            return [
                'name' => $task,
                'complete' => in_array($task, $request->tasks_complete ?? [])
            ];
        });
        if (!$request->repo) {
            if ($request->state == ProjectState::Incomplete || $request->state == ProjectState::Evaluating) {
                $response = Http::withToken(env('GITHUB_TOKEN'))->post(env('GITHUB_ORG').'/repos', [
                    'name' => Str::slug($request->title),
                    'private' => false,
                ]);
                $new_repo = $response->json('url');
            }
            $new_repo = null;
        }
        if ($request->user()->can('project-approve')) {
            $project->update([
                'title' => $request->title,
                'type' => $request->type,
                'spec' => $request->spec,
                'state' => $request->state,
                'url' => $request->repo ?? $new_repo,
                'aims' => json_encode($aims),
                'objectives' => json_encode($objectives),
                'tasks' => json_encode($tasks),
            ]);
        } else {
            $project->update([
                'title' => $request->title,
                'type' => $request->type,
                'spec' => $request->spec,
                'aims' => json_encode($aims),
                'objectives' => json_encode($objectives),
                'tasks' => json_encode($tasks),
            ]);
        }
        if ($request->supervise = true && !$project->supervisor) {
            $project->supervisor()->associate(auth()->user())->save();
        }
        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $this->authorize('destroy', $project);
        if ($project->supervisor && auth()->user() != $project->supervisor) {
            return redirect()->back()->withErrors('Cannot delete project, only this project\'s supervisor can delete this project');
        }
        $project->delete();
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully');
    }
    public function assign($id)
    {
        $project = Project::find($id);
        if (!Auth::user()->groups) {
            return redirect()->back()->with('error', 'You need to join a group before assigning a project');
        }
        $group = Auth::user()->groups->last();
        if ($group->project_id) {
            return redirect()->back()->with('error', 'You need to abandon your current project before assigning a new one');
        }
        if ($project->group == null ?? false) {
            return redirect()->back()->with('error', 'A group is already assigned to this project');
        }
        if ($project->spec != Specialization::None && $project->spec != $group->spec) {
            return redirect()->back()->with('error', 'This project is not for your group\'s specialization');
        }
        if ($project->type != $group->project_type) {
            return redirect()->back()->with('error', 'This project is for ' . $project->type->value . ' only');
        }
        $group->project()->associate($project)->save();
        if ($project->supervisor_id) {
            $project->update(['state' => ProjectState::Approving]);
        }
        return redirect()->back()->with('success', 'Project assigned successfully');
    }
    public function unassign($id)
    {

        $project = Project::find($id);
        if (!$project->supervisor_id) {
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Group unassigned successfully');
        }
        $project->group()->update(['project_id' => null]);
        $project->update(['state' => ProjectState::Proposition]);
        return redirect()->back()->with('success', 'Group unassigned successfully');
    }
    public function supervise($id)
    {
        $project = Project::find($id);
        if (request()->user()->github_token) {
            $supervisor = Socialite::driver('github')->userFromToken(request()->user()->github_token)->getNickName();
            Http::withToken(env('GITHUB_TOKEN'))->put($project->url . '/collaborators/' . $supervisor, ['permission' => 'maintain']);
        }
        $project->supervisor()->associate(request()->user())->save();
        if ($project->group() ?? false) {
            $project->update(['state' => ProjectState::Approving]);
        }
        return redirect()->back()->with('success', 'Assigned supervisor successfully');
    }
    public function abandon($id)
    {
        Project::find($id)->update(['supervisor_id' => null, 'state' => ProjectState::Proposition]);
        return redirect()->back()->with('success', 'Unassigned supervisor successfully');
    }
    public function approve(Project $project)
    {
        switch ($project->state) {
            case (ProjectState::Incomplete):
                $project->update(['state' => ProjectState::Complete]);
                break;
            case (ProjectState::Evaluating):
                $project->update(['state' => ProjectState::Complete]);
                break;
            default:
                $maintainers = $project->group->developers->pluck('github_id')->toArray();
                if (!$project->url) {
                    try {
                        $response = Http::withToken(env('GITHUB_TOKEN'))->post(env('GITHUB_ORG').'/repos', [
                            'name' => Str::slug($project->title),
                            'private' => false,
                        ]);
                    } catch (Exception) {
                        return redirect()->back()->withErrors('Could not reach the GitHub servers at the moment please try again later!');
                    }
                } else {
                    $response = Http::withToken(env('GITHUB_TOKEN'))->get($project->url);
                }
                Http::withToken(env('GITHUB_TOKEN'))->post(
                    env('GITHUB_ORG').'/teams',
                    [
                        'name' => Str::slug($project->title),
                        'repo_names' => [$response->json('name')],
                        'permission' => 'push',
                        'maintainers' => $maintainers,
                    ]
                );
                $project->update(['state' => ProjectState::Incomplete, 'url' => $response->json('url')]);
        }
        $project->group->update(['state' => GroupState::Full]);
        return redirect()->back()->with('success', 'Project approved successfully');
    }

    public function disapprove(Project $project)
    {
        $project->update(['state' => ProjectState::Rejected]);
        return redirect()->back()->with('success', 'Project rejected successfully');
    }

    public function complete(Project $project)
    {
        $this->authorize('complete', $project);
        $sha = Http::withToken(env('GITHUB_TOKEN'))->get($project->url . '/git/refs/heads')->json('0')['object']['sha'];
        $response = Http::withToken(env('GITHUB_TOKEN'))->post(
            $project->url . '/git/refs',
            [
                'ref' => 'refs/heads/' . str()->slug($project->type->value),
                'sha' => $sha
            ]
        );
        if ($response->failed()) {
            return redirect()->back()->withErrors($response->json());
        }
        $project->update(['state' => ProjectState::Evaluating]);
        return redirect()->back()->with('success', 'Completed successfully, awaiting evaluation');
    }
    public function sync($id)
    {
        $project = Project::find($id);
        $this->authorize('sync', $project);
        cache()->forget('github' . $project->id);
        cache()->forget('markdown' . $project->id);
        cache()->forget("languages.$project->id");
        try {
            $github = Http::withToken(env('GITHUB_TOKEN'))->get($project->url)->json();
            $updated_at =  $github ? $github['pushed_at'] : null;
            if ($updated_at && (Carbon::parse($updated_at) >= Carbon::parse($project->updated_at))) {
                $project->touch();
            };
        } catch (Exception) {
            return redirect()->route('projects.show', $project)->with('error', 'Could not connect to the GitHub Servers');
        }
        return redirect()->route('projects.show', $project);
    }
}
