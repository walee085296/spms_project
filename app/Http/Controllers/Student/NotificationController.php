<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * عرض التنبيهات للطالب
     */
    public function index()
    {
        // كل التنبيهات العامة التي نشرها المشرف
        $notifications = Notification::orderBy('created_at', 'desc')->get();

        return view('student.notifications.index', compact('notifications'));
    }
}
