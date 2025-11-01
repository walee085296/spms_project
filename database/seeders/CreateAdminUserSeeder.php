<?php

namespace Database\Seeders;

use App\Enums\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * تنفيذ عملية ملء البيانات (Seeding) في قاعدة البيانات.
     *
     * وظيفة هذا Seeder هي إنشاء مستخدم "أدمن" (Admin)
     * ومنحه جميع الصلاحيات (Permissions) الموجودة في النظام.
     *
     * @return void
     */
    public function run()
    {
        /**
         * إنشاء مستخدم جديد من نوع "أدمن"
         * يتم تخزين بياناته الأساسية في جدول users
         */
        $user = User::create([
            'first_name' => 'waleed',          // الاسم الأول للأدمن
            'last_name' => 'ali',              // الاسم الأخير
            'stdsn' => '21141709',             // رقم الطالب (Student Serial Number)
            'email' => 'waleed@gmail.com',     // البريد الإلكتروني للأدمن
            'spec' => Specialization::Software, // التخصص (Software Engineering)
            'password' => bcrypt('12345678'),  // كلمة المرور بعد التشفير
        ]);

        /**
         * إنشاء دور (Role) جديد باسم "Student"
         * هذا الدور يمكن استخدامه لاحقًا للمستخدمين العاديين
         */
        Role::create(['name' => 'Student']);

        /**
         * إنشاء الدور الثاني وهو "Admin"
         * هذا الدور سيُمنح للمستخدم الإداري الذي أنشأناه بالأعلى
         */
        $role = Role::create(['name' => 'Admin']);

        /**
         * جلب جميع الصلاحيات (Permissions) الموجودة في النظام
         * كقائمة من IDs لتعيينها إلى الدور "Admin"
         */
        $permissions = Permission::pluck('id', 'id')->all();

        /**
         * ربط كل الصلاحيات بالدور "Admin"
         * بحيث يمتلك الأدمن كل صلاحيات التطبيق
         */
        $role->syncPermissions($permissions);

        /**
         * تعيين الدور "Admin" للمستخدم الذي أنشأناه بالأعلى
         * يتم ذلك عبر حقل pivot في جدول model_has_roles
         */
        $user->assignRole([$role->id]);
    }
}
