<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    /**
     * show: عرض إشعار محدد بناءً على الـ ID
     * 
     * عند فتح هذا الإشعار:
     * 1. يتم التحقق من وجوده ضمن إشعارات المستخدم الحالي.
     * 2. يتم وضعه كـ "مقروء" (markAsRead).
     * 3. يتم إعادة توجيه المستخدم إلى الرابط الموجود في بيانات الإشعار.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function show($id)
    {
        // جلب الإشعار بناءً على ID من مجموعة إشعارات المستخدم الحالي
        $notification = auth()->user()->notifications->where('id', $id)->first();

        if ($notification) {
            // وضع الإشعار كـ "مقروء"
            $notification->markAsRead();

            // إعادة التوجيه إلى الرابط المرتبط بالإشعار
            return redirect($notification->data['link']);
        }

        // إذا لم يوجد الإشعار يمكن إعادة توجيه المستخدم للصفحة السابقة أو الرئيسية
        return redirect()->back()->with('error', 'Notification not found.');
    }

    /**
     * markAllRead: تعليم جميع الإشعارات الغير مقروءة كمقروءة
     * 
     * 1. يستدعي جميع إشعارات المستخدم الغير مقروءة.
     * 2. يجعلها مقروءة.
     * 3. يعيد المستخدم إلى الصفحة السابقة.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllRead()
    {
        // تعليم كل الإشعارات الغير مقروءة كمقروءة
        auth()->user()->unreadNotifications->markAsRead();

        // إعادة التوجيه للصفحة السابقة
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
