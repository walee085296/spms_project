<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dept;

class CreateDeptSeeder extends Seeder
{
    /**
     * تنفيذ عملية ملء البيانات (Seeding) في قاعدة البيانات.
     *
     * هذا الـ Seeder يقوم بإنشاء قسم (Department) واحد فقط
     * باسم "IT Software" داخل جدول الأقسام (depts).
     *
     * يمكن توسيعه لاحقًا لإضافة أقسام أخرى مثل:
     * - Computer Science
     * - Information Systems
     * - Network Engineering
     *
     * @return void
     */
    public function run()
    {
        /**
         * إنشاء سجل جديد في جدول depts
         * يحتوي على عمود واحد فقط وهو الاسم (name)
         */
        Dept::create([
            'name' => 'IT Software'  // اسم القسم
        ]);
    }
}
