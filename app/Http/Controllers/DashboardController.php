<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;


class DashboardController extends Controller
{
    public function index()
    {
        // كل التاسكات
    $tasks = Task::with('project')->latest()->get();

    // إحصائيات
    $stats = [
        'all' => Task::count(),
        'pending' => Task::where('state', 0)->count(),
        'completed' => Task::where('state', 1)->count(),
        'rejected' => Task::where('state', 2)->count(),
    ];

    return view('dashboard', compact('tasks', 'stats'));
    }
}
