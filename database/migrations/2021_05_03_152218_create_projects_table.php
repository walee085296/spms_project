<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * تشغيل عملية الـ Migration (إنشاء جدول المشاريع)
     *
     * هذا الجدول يُستخدم لتخزين بيانات مشاريع التخرج أو المشاريع الأكاديمية.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            // المفتاح الأساسي للجدول (id)
            $table->id();

            // عنوان المشروع (مثل: Smart Attendance System)
            $table->string('title');

            // التخصص (specification) مثل: AI, Web, Mobile ...
            $table->string('spec')->default('none');

            // نوع المشروع (مثلاً: senior أو junior)
            $table->string('type')->default('senior');

            // حالة المشروع (proposition, approved, in progress, completed...)
            $table->string('state')->default('proposition');

            // الأهداف العامة للمشروع — تُخزن بصيغة JSON
            $table->json('aims');

            // الأهداف التفصيلية (Objectives) — أيضًا بصيغة JSON
            $table->json('objectives');

            // المهام (Tasks) الخاصة بالمشروع — بصيغة JSON
            $table->json('tasks');

            // رابط المشروع (قد يكون رابط GitHub أو عرض تجريبي)
            $table->string('url')->default('http://localhost:8000/');

            // معرف المشرف على المشروع (supervisor)
            $table->unsignedBigInteger('supervisor_id')->nullable();

            // إنشاء علاقة مفتاح خارجي مع جدول users (لأن المشرف مستخدم)
            $table->foreign('supervisor_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); // عند حذف المشرف تصبح القيمة null

            // الحقول التلقائية لتتبع الإنشاء والتعديل
            $table->timestamps();
        });
    }

    /**
     * عكس عملية الـ Migration (حذف جدول المشاريع)
     *
     * @return void
     */
    public function down()
    {
        // حذف جدول projects إذا كان موجودًا
        Schema::dropIfExists('projects');
    }
}
