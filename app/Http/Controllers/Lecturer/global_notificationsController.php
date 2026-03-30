<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class global_notificationsController extends Controller
{
    /**
     * صفحة إنشاء تنبيه جديد
     */
    public function create()
    {
        return view('lecturer.notifications.create');
    }

    /**
     * حفظ التنبيه في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

       Notification::create([
            'title'   => $request->title,
            'message' => $request->message,
            'sender_id' => Auth::id(),
            'type' => 'general', // تنبيه عام لكل الطلاب
        ]);

        return redirect( )
            ->back()
            ->with('success', 'تم نشر التنبيه بنجاح ✔',);
    }
}
