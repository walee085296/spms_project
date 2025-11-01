<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * تشغيل جميع الـ Seeders في المشروع
     *
     * هذه الدالة تُستدعى عند تنفيذ الأمر:
     * php artisan db:seed
     *
     * الهدف: ملء قاعدة البيانات بالبيانات الأساسية (مثل الصلاحيات، الأدمن، الأقسام...)
     *
     * @return void
     */
    public function run()
    {
        /**
         * استدعاء الـ Seeders الأساسية للمشروع
         * 
         * يمكن إضافة أي Seeder آخر هنا ليتم تنفيذه تلقائيًا.
         */
        $this->call([
            PermissionTableSeeder::class,  // Seeder الخاص بإنشاء جميع الصلاحيات (Permissions)
            CreateAdminUserSeeder::class,  // Seeder لإنشاء مستخدم أدمن وإعطائه جميع الصلاحيات
        ]);

        /**
         * ملاحظة: يمكنك إضافة أي Seeder آخر مثل:
         * CreateDeptSeeder::class
         * CreateGroupSeeder::class
         * إلخ
         *
         * ليتم تنفيذها تلقائيًا عند تشغيل db:seed
         */
    }
}
