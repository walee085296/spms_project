<?php

namespace App\Http\Controllers;

use App\Enums\GroupState; // استدعاء Enum لحالة المجموعة (Full, Recruiting, etc)
use Carbon\Carbon; // مكتبة التعامل مع التواريخ والأوقات
use App\Models\Project; // نموذج المشروع
use App\Enums\ProjectType; // Enum لأنواع المشاريع (Web, Mobile, Desktop, etc)
use App\Enums\ProjectState; // Enum لحالات المشروع (Proposition, Incomplete, Complete...)
use Illuminate\Support\Str; // دوال للسلاسل النصية مثل slug
use Illuminate\Http\Request; // التعامل مع الطلبات
use App\Enums\Specialization; // Enum للتخصصات (Frontend, Backend, Fullstack...)
use Exception; // التعامل مع الاستثناءات
use Illuminate\Support\Facades\Auth; // إدارة المستخدم الحالي
use Illuminate\Support\Facades\Http; // إرسال طلبات HTTP (مثل GitHub API)
use Illuminate\Validation\Rules\Enum; // للتحقق من صحة قيمة Enum
use Laravel\Socialite\Facades\Socialite; // التعامل مع OAuth مثل GitHub

class ProjectController extends Controller
{
    /**
     * Constructor: تعيين الـ Middleware للتحكم في صلاحيات المستخدمين
     * مثلا من يمكنه مشاهدة، إنشاء، الإشراف أو الموافقة على المشاريع.
     */
    public function __construct()
    {
        // أي مستخدم يمتلك صلاحية project-list يمكنه الوصول إلى index و show
        $this->middleware('permission:project-list', ['only' => ['index', 'show']]);

        // أي مستخدم يمتلك صلاحية project-supervise يمكنه الإشراف أو إزالة الإشراف
        $this->middleware('permission:project-supervise', ['only' => ['supervise', 'unsupervise']]);

        // أي مستخدم يمتلك صلاحية project-approve يمكنه الموافقة أو رفض المشروع
        $this->middleware('permission:project-approve', ['only' => ['approve', 'disapprove']]);
    }

    /**
     * index: عرض قائمة المشاريع مع إمكانية الفلترة
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // جلب كل التخصصات وأنواع المشاريع وحالاتها
        $specs = Specialization::cases();
        $types = ProjectType::cases();
        $states = ProjectState::cases();

        // جلب المشاريع مع العلاقة group واستخدام فلترة بناءً على المدخلات
        $projects = Project::with('group')
            ->latest() // ترتيب المشاريع من الأحدث
            ->filter(request([
                'search', 'spec', 'type', 'state', 
                'created_from', 'created_to', 'updated_from', 'updated_to'
            ]))
            ->paginate(10) // عرض 10 مشاريع لكل صفحة
            ->withQueryString(); // الاحتفاظ بفلترة البحث أثناء التنقل بين الصفحات

        // عرض صفحة index مع تمرير البيانات
        return view('projects.index', compact(['projects', 'specs', 'types', 'states']))
            ->with('i', (request()->input('page', 1) - 1) * 5); // رقم الصف الأول حسب الصفحة
    }

    /**
     * export: تصدير المشاريع إلى ملف Excel
     * @param Request $request
     */
    public function export(Request $request)
    {
        $this->authorize('export', Project::class); // التأكد من صلاحية المستخدم

        return Project::with('group')
            ->latest()
            ->filter(request([
                'search', 'spec', 'type', 'state', 
                'created_from', 'created_to', 'updated_from', 'updated_to'
            ]))
            ->get()
            ->map(function ($project) {
                // تحويل البيانات للتمثيل في Excel
                return [
                    'Title' => $project->title,
                    'Type' => ucfirst($project->type->value),
                    'Spec' => ucfirst($project->spec->value),
                    'State' => ucfirst($project->state->value),
                    'Supervisor' => $project->supervisor->name,
                    'Team' => $project->group 
                        ? $project->group->developers->map(fn($user) => ['name' => $user->name])->implode('name', ', ') 
                        : null,
                    'Created at' => $project->created_at->format('Y/m/d D'),
                    'Updated at' => $project->updated_at->format('Y/m/d D'),
                ];
            })
            ->downloadExcel('projects.xlsx', null, true); // تنزيل الملف مباشرة
    }

    /**
     * create: عرض نموذج إنشاء مشروع جديد
     */
    public function create()
    {
        $this->authorize('create', Project::class); // التأكد من الصلاحية

        // جلب قائمة الريبو من GitHub (إذا محددة في .env)
        $reposUrl = env('GITHUB_ORG', 'https://api.github.com/users/walee085296/repos');
        $repos = Http::get($reposUrl)->json();

        // تمرير التخصصات وأنواع المشاريع وحالاتها لواجهة المستخدم
        $specs = Specialization::cases();
        $types = ProjectType::cases();
        $states = ProjectState::cases();

        return view('projects.create', compact(['specs', 'types', 'states', 'repos']));
    }

    /**
     * store: تخزين مشروع جديد في قاعدة البيانات
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class); // التأكد من صلاحية المستخدم

        $group = $request->user()->groups->last(); // آخر مجموعة ينتمي لها المستخدم

        // التحقق من صحة البيانات المدخلة
        $this->validate($request, [
            'title' => 'required|unique:projects,title', // عنوان المشروع مطلوب ويجب أن يكون فريد
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

        // تجهيز أهداف المشروع مع حالة الإنجاز الافتراضية false
        $aims = collect($request->aims)->map(fn($aim) => ['name' => $aim, 'complete' => false]);
        $objectives = collect($request->objectives)->map(fn($objective) => ['name' => $objective, 'complete' => false]);
        $tasks = collect($request->tasks)->map(fn($task) => ['name' => $task, 'complete' => false]);

        $new_repo = null; // رابط Repo جديد إذا تم إنشاؤه على GitHub

        // إنشاء Repo على GitHub لو لم يُحدد المستخدم Repo
        if (!$request->repo) {
            if ($request->state == ProjectState::Incomplete || $request->state == ProjectState::Evaluating) {
                $url = env('GITHUB_ORG') 
                    ? "https://api.github.com/orgs/" . env('GITHUB_ORG') . "/repos"
                    : "https://api.github.com/user/repos";

                $response = Http::withToken(env('GITHUB_TOKEN'))->post($url, [
                    'name' => Str::slug($request->title), // اسم الريبو بدون فراغات
                    'private' => false, // المشروع عام
                ]);

                // التحقق من نجاح إنشاء الريبو
                if ($response->successful()) {
                    $new_repo = $response->json('html_url'); // رابط الريبو
                } else {
                    return back()->withErrors(['github' => 'فشل إنشاء Repo على GitHub: ' . $response->body()]);
                }
            }
        }

        // إنشاء المشروع في قاعدة البيانات
        $project = Project::create([
            'title' => $request->title,
            'url' => $request->repo ?? $new_repo,
            'type' => $request->user()->can('project-create') ? $request->type : $group->project_type,
            'spec' => $request->user()->can('project-create') ? $request->spec : $group->spec,
            'state' => $request->user()->can('project-approve') ? $request->state : ProjectState::Proposition,
            'aims' => json_encode($aims),
            'objectives' => json_encode($objectives),
            'tasks' => json_encode($tasks),
            'supervisor_id' => $request->supervise
        ]);

        // ربط المشروع بالمجموعة
        if ($group) {
            $group->update(['project_id' => $project->id]);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * show: عرض تفاصيل المشروع مع مستودع GitHub
     * @param int $id
     */
    public function show($id)
    {
        $project = Project::with('group', 'supervisor')->find($id);

        // إذا لم يكن المشروع موجودًا أو لم يتم ربطه بـ GitHub
        if (!$project || !$project->url) {
            return view('projects.show', [
                'project' => $project,
                'markdown' => '<p class="text-red-700">No GitHub repository linked for this project.</p>',
                'github' => null,
                'languages' => []
            ]);
        }

        try {
            // محاولة الاتصال بـ GitHub للحصول على بيانات المشروع
            Http::withToken(env('GITHUB_TOKEN'))->get($project->url)->json();
        } catch (Exception) {
            // إذا فشل الاتصال، استخدم البيانات المؤقتة من Cache
            $github = cache()->get('github' . $id);
            $markdown = cache()->get('markdown' . $id, '<p class="text-red-700">Could not connect to GitHub servers at this moment please try again later!</p>');
            $languages = cache()->get("languages.$id", []);
            return view('projects.show', compact('project', 'markdown', 'github', 'languages'));
        }

        // تخزين البيانات في Cache لمدة 6 ساعات لتقليل طلبات GitHub
        $github = cache()->remember('github' . $project->id, 21600, fn () =>
            $project->url ? Http::withToken(env('GITHUB_TOKEN'))->get($project->url)->json() : null
        );

        $markdown = $project->url
            ? Http::withToken(env('GITHUB_TOKEN'))->accept('application/vnd.github.html')->get($project->url . '/readme')
            : null;

        $markdown = cache()->remember('markdown' . $project->id, 21600, fn () =>
            $markdown && $markdown->failed() ? $markdown->json() : ($markdown ? $markdown->body() : '')
        );

        $languages = cache()->remember("languages.$project->id", 21600, fn () =>
            $github ? collect(Http::withToken(env('GITHUB_TOKEN'))->get($github['languages_url'])->json()) : []
        );

        return view('projects.show', compact('project', 'markdown', 'github', 'languages'));
    }

    /**
     * باقي الدوال مثل edit, update, destroy, assign, unassign, approve, disapprove, complete يمكن كتابتها
     * بنفس الطريقة مع إضافة التعليقات لكل خطوة لتوضيح وظيفة كل جزء من الكود.
     */
}
