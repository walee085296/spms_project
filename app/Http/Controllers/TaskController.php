<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
   public function index(Task $tasks,User $user, Project $project)
{     
      $user = Auth::user();

    // لو Admin يرجع كل التاسكات مباشرة
    if ($user->id === 1) { // افترض أن ID Admin هو 1
        $tasks = Task::with('project')->get();
    } else {
        // لو مش Admin، جلب مشروع المستخدم الحالي
        $projectId = $user->group->project_id ?? null;

        // لو مفيش مشروع مرتبط → يرجع مجموعة فارغة
        if (!$projectId) {
            $tasks = collect();
        } else {
            // جلب التاسكات الخاصة بالمشروع
            $tasks = Task::with('project')
                ->where('project_id', $projectId)
                ->get();
          
        }
        
    }

    return view('tasks.index', compact('tasks','project'));
}

public function create()
{
    $tasks = Task::with('checklists','project')->get();
     $projects = Project::all();
    return view('tasks.create', compact('tasks', 'projects'));
}

public function store(Request $request ,Project $project)
{
    $request->validate([
        'project_id' => 'required_without:all_projects|exists:projects,id',
        'desc' => 'required|string|max:255'
    ]);

    // لو المشرف اختار إرسال التاسك لكل المشاريع
    if ($request->all_projects) {

        $projects = Project::all();

        foreach ($projects as $project) {
            Task::create([
                'project_id' => $project->id,
                'desc' => $request->desc,
            ]);
        }

    } else {

        // إنشاء التاسك لمشروع واحد فقط
        Task::create([
            'project_id' => $request->project_id,
            'desc' => $request->desc,
        ]);
    }

    return redirect()->route('tasks.index')
        ->with('success', 'تم إنشاء التاسك بنجاح');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function show(Task $task, Project $project)
{
// ->where('id', $task->id)

$task->load('project'); // ✅ 
 $tasks = Task::with('checklists','project')->get();
    return view('tasks.show', compact('task','tasks' ,'project'));
}
public function updateState(Request $request, Task $task)
{
    // $task->update([
    //     'state' => $request->state
    // ]);
     $task->state = $request->has('state') ? 1 : 0;
    $task->save();


    return back();
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

 

public function addtask(Request $request, $id)
{
    $request->validate([
        'url' => 'required|string|max:255',
    ]);

    $task = Task::findOrFail($id);

    $task->update([
        'url' => $request->url
    ]);

    return redirect()->route('tasks.index')
        ->with('success', 'تم تحديث التاسك بنجاح');
}

     public function edit(Task $task)
    {
        $task->load('project');

        return view('tasks.edit', compact('task'));
    }

     public function update(Request $request, Task $task)
    {
        $request->validate([
            'aims' => 'required|array'
        ]);

        $aims = collect($request->aims)->map(function ($aim) use ($request) {
            return [
                'name' => $aim,
                'complete' => in_array($aim, $request->completed_aims ?? [])
            ];
        });

        $task->update([
            'desc' => json_encode($aims),
        ]);

        return redirect()->route('tasks.show', $task)
            ->with('success', 'تم تحديث التاسك بنجاح');
    }

     public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'تم حذف التاسك بنجاح');
    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
}
