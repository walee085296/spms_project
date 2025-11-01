<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController; // تسجيل الدخول والخروج
use App\Http\Controllers\Auth\ConfirmablePasswordController; // تأكيد كلمة المرور للمستخدم
use App\Http\Controllers\Auth\EmailVerificationNotificationController; // إرسال إشعار تحقق البريد
use App\Http\Controllers\Auth\EmailVerificationPromptController; // صفحة طلب التحقق من البريد
use App\Http\Controllers\Auth\NewPasswordController; // استعادة كلمة المرور
use App\Http\Controllers\Auth\PasswordResetLinkController; // إنشاء رابط إعادة تعيين كلمة المرور
use App\Http\Controllers\Auth\RegisteredUserController; // تسجيل مستخدم جديد
use App\Http\Controllers\Auth\VerifyEmailController; // التحقق من البريد
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| هذه الروابط مسؤولة عن تسجيل الدخول، التسجيل، إعادة تعيين كلمة المرور،
| التحقق من البريد الإلكتروني، وتأكيد كلمة المرور.
|
*/

// تسجيل مستخدم جديد - نموذج التسجيل
Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest') // متاح فقط للزوار غير المسجلين
                ->name('register');

// تسجيل مستخدم جديد - معالجة البيانات المرسلة من النموذج
Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest');

// تسجيل الدخول - نموذج تسجيل الدخول
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

// تسجيل الدخول - معالجة بيانات تسجيل الدخول
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest');

// طلب إعادة تعيين كلمة المرور - نموذج البريد الإلكتروني
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');

// إرسال رابط إعادة تعيين كلمة المرور إلى البريد الإلكتروني
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

// إعادة تعيين كلمة المرور - نموذج إدخال كلمة مرور جديدة
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');

// إعادة تعيين كلمة المرور - معالجة إدخال كلمة المرور الجديدة
Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

// صفحة التحقق من البريد - تطلب من المستخدم التحقق من بريده
Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth') // يجب أن يكون المستخدم مسجّل دخول
                ->name('verification.notice');

// تحقق من البريد من الرابط المرسل للمستخدم
Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1']) // تحقق من التوقيع وتقييد عدد المحاولات
                ->name('verification.verify');

// إرسال إشعار تحقق جديد بالبريد
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1']) // يجب تسجيل الدخول، وتقييد الإرسال لتجنب الإساءة
                ->name('verification.send');

// عرض نموذج تأكيد كلمة المرور قبل القيام بإجراءات حساسة
Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

// معالجة تأكيد كلمة المرور
Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

// تسجيل الخروج
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');
