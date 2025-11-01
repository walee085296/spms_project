<?php

use Illuminate\Support\Facades\Route; // استدعاء Facade للـ Routes
use App\Http\Controllers\RoleController; // استدعاء الـ Controller لإدارة الصلاحيات
use App\Http\Controllers\UserController; // استدعاء الـ Controller لإدارة المستخدمين
use App\Http\Controllers\GroupController; // استدعاء الـ Controller لإدارة الجروبات
use App\Http\Controllers\Auth\GitHubController; // استدعاء الـ Controller لتسجيل الدخول عبر GitHub
use App\Http\Controllers\ProfileController; // استدعاء الـ Controller لإدارة الملف الشخصي
use App\Http\Controllers\ProjectController; // استدعاء الـ Controller لإدارة المشاريع
use App\Http\Controllers\DashboardController; // استدعاء الـ Controller للوحة التحكم
use App\Http\Controllers\GroupRequestController; // استدعاء الـ Controller لطلبات الانضمام للجروبات
use App\Http\Controllers\UserNotificationController; // استدعاء الـ Controller للإشعارات

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| هنا يتم تسجيل جميع الـ Routes الخاصة بالويب.
| هذه الروابط محمية بواسطة الـ RouteServiceProvider ضمن مجموعة "web".
|
*/

Route::get('/', function () {
    return view('welcome'); // الصفحة الرئيسية للتطبيق (Welcome page)
});

// Routes لتسجيل الدخول باستخدام GitHub
Route::get('auth/github', [GitHubController::class, 'gitRedirect'])->name('auth.git'); // تحويل المستخدم لـ GitHub
Route::get('auth/github/callback', [GitHubController::class, 'handleProviderCallback']); // معالجة Callback بعد تسجيل الدخول

// مجموعة Routes محمية بالـ middleware auth (المستخدم يجب أن يكون مسجّل دخول)
Route::group(['middleware' => ['auth']], function () {

    // لوحة التحكم (Dashboard)
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // عرض الملف الشخصي
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');

    // نموذج تعديل الملف الشخصي
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // تحديث الملف الشخصي
    Route::put('profile/edit', [ProfileController::class, 'update'])->name('profile.update');

    // تعليم جميع الإشعارات كمقروءة
    Route::get('markAllRead', [UserNotificationController::class, 'markAllRead'])->name('markAllRead');

    // عرض إشعار محدد
    Route::get('notifications/{id}', [UserNotificationController::class, 'show'])->name('notifications.show');

    // إرسال طلب الانضمام لجروب
    Route::get('requests/{group:id}', [GroupRequestController::class, 'store'])->name('requests.store');

    // حذف طلب الانضمام
    Route::delete('requests/{group:id}', [GroupRequestController::class, 'destroy'])->name('requests.destroy');

    // قبول طلب الانضمام
    Route::get('request/{id}/accept', [GroupRequestController::class, 'acceptRequest'])->name('requests.accept');

    // رفض طلب الانضمام
    Route::get('request/{id}/reject', [GroupRequestController::class, 'rejectRequest'])->name('requests.reject');

    // Routes CRUD كاملة لإدارة الصلاحيات
    Route::resource('roles', RoleController::class);

    // Routes CRUD كاملة لإدارة المستخدمين
    Route::resource('users', UserController::class);

    // Routes CRUD كاملة لإدارة الجروبات
    Route::resource('groups', GroupController::class);

    // خروج المستخدم من الجروب
    Route::get('groups/{group:id}/leave', [GroupController::class, 'leaveGroup'])->name('groups.leave');

    // Routes CRUD كاملة لإدارة المشاريع
    Route::resource('projects', ProjectController::class);

    // تصدير المشاريع
    Route::get('export.projects', [ProjectController::class, 'export'])->name('projects.export');

    // تعيين مشروع لمستخدم
    Route::get('projects/{project:id}/assign', [ProjectController::class, 'assign'])->name('projects.assign');

    // إزالة التعيين من مشروع
    Route::get('projects/{project:id}/unassign', [ProjectController::class, 'unassign'])->name('projects.unassign');

    // الإشراف على مشروع
    Route::get('projects/{project:id}/supervise', [ProjectController::class, 'supervise'])->name('projects.supervise');

    // التخلي عن مشروع
    Route::get('projects/{project:id}/abandon', [ProjectController::class, 'abandon'])->name('projects.abandon');

    // اعتماد المشروع
    Route::get('projects/{project:id}/approve', [ProjectController::class, 'approve'])->name('projects.approve');

    // رفض المشروع
    Route::get('projects/{project:id}/disapprove', [ProjectController::class, 'disapprove'])->name('projects.disapprove');

    // إكمال المشروع
    Route::get('projects/{project:id}/complete', [ProjectController::class, 'complete'])->name('projects.complete');

    // مزامنة بيانات المشروع
    Route::get('projects/{project}/sync', [ProjectController::class, 'sync'])->name('projects.sync');
});

// استدعاء Routes الخاصة بالمصادقة (login, register, forgot password ...)
require __DIR__ . '/auth.php';
