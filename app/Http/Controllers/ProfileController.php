<?php

namespace App\Http\Controllers;

use App\Models\User; // نموذج المستخدم
use Exception;
use Illuminate\Support\Arr; // للتعامل مع المصفوفات
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // لجلب المستخدم الحالي
use Illuminate\Support\Facades\Hash; // لتشفير الباسورد
use Laravel\Socialite\Facades\Socialite; // للتعامل مع OAuth مثل GitHub

class ProfileController extends Controller
{
    /**
     * عرض صفحة البروفايل للمستخدم الحالي
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $id = Auth::id(); // جلب ID المستخدم الحالي
        $user = User::find($id); // جلب بيانات المستخدم من قاعدة البيانات

        // لو المستخدم ربط حسابه بـ GitHub
        if ($user->github_id) {
            try {
                // نحفظ بيانات GitHub في Cache لمدة 6 ساعات (21600 ثانية)
                $git = cache()->remember('git' . $id, 21600, fn () => Socialite::driver('github')->userFromToken($user->github_token));
            } catch (Exception) {
                $git = null; // لو حصل خطأ في الاتصال بـ GitHub
            }
        } else $git = null;

        return view('profile.show', compact('user', 'git')); // إرسال البيانات للواجهة
    }

    /**
     * عرض صفحة تعديل البروفايل
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = Auth::user(); // المستخدم الحالي
        $img = $user->avatar ? asset('storage/' . $user->avatar) : null; // رابط صورة الملف الشخصي إذا موجودة
        

        return view('profile.edit', compact('user', 'img'));
    }

    /**
     * تحديث بيانات البروفايل
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // التحقق من صحة البيانات
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'stdsn' => 'digits:7|unique:users,stdsn,' . $user->id, // رقم الطالب
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|same:confirm-password',
            'avatar' => 'nullable|mimes:jpg,jpeg,png,webp|max:5048' // صورة الملف الشخصي
        ]);

        $input = $request->all();

        // لو المستخدم غير الباسورد
        if (!empty($input['password'])) {
            $user->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // تشفير الباسورد
                'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : $user->avatar, // حفظ الصورة الجديدة أو الاحتفاظ القديمة
            ]);
        } else {
            $input = Arr::except($input, ['password']); // إزالة كلمة المرور من البيانات
            $user->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : $user->avatar, // حفظ الصورة الجديدة أو الاحتفاظ القديمة
            ]);
        }

        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully'); // رسالة نجاح
    }
}
