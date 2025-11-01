<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * تنفيذ Seeder لإضافة جميع الصلاحيات الأساسية للنظام
     *
     * @return void
     */
    public function run()
    {
        /**
         * قائمة الصلاحيات الأساسية (Permissions)
         * كل صلاحية تمثل قدرة معينة داخل النظام
         *
         * مثال:
         * - role-list: مشاهدة قائمة الأدوار
         * - role-create: إنشاء دور جديد
         * - group-edit: تعديل بيانات المجموعات
         * - project-supervise: الإشراف على المشاريع
         */
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'group-list',
            'group-create',
            'group-edit',
            'group-delete',
            'project-list',
            'project-create',
            'project-edit',
            'project-delete',
            'project-supervise',
            'project-approve',
            'project-export',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ];

        /**
         * إنشاء كل صلاحية داخل جدول permissions
         * باستخدام حلقة foreach
         */
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
