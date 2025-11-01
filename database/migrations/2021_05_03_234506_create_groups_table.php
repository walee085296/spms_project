<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * تشغيل عملية الـ Migration (إنشاء جدول المجموعات groups)
     *
     * هذا الجدول يُستخدم لتخزين بيانات مجموعات المشاريع الخاصة بالطلاب.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            // المفتاح الأساسي للجدول (id)
            $table->id();

            // مفتاح خارجي يشير إلى المشروع المرتبط بالمجموعة
            $table->unsignedBigInteger('project_id')->nullable();

            // ربط project_id بجدول projects
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('set null'); // إذا تم حذف المشروع، تصبح القيمة null

            // حالة المجموعة (مثلاً: تبحث عن أعضاء، مكتملة، تحت التقييم...)
            $table->string('state')->default('looking for members');

            // تخصص المجموعة (specification) — مثلاً: AI، Web، Mobile...
            $table->string('spec')->default('none');

            // نوع المشروع (project type) — مثل: senior أو junior
            $table->string('project_type')->default('senior');

            // الحقول الافتراضية لتتبع وقت الإنشاء والتحديث (created_at, updated_at)
            $table->timestamps();
        });
    }

    /**
     * عكس عملية الـ Migration (حذف الجدول عند التراجع)
     *
     * @return void
     */
    public function down()
    {
        // حذف جدول المجموعات إذا كان موجودًا
        Schema::dropIfExists('groups');
    }
}
