<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * تشغيل عملية الـ Migration (إنشاء جدول الإشعارات)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            // إنشاء عمود id من نوع UUID ليكون المفتاح الأساسي
            $table->uuid('id')->primary();

            // نوع الإشعار (مثلاً: إشعار قبول، إشعار رفض، إلخ)
            $table->string('type');

            // الحقول الخاصة بتعدد الأشكال (polymorphic relation)
            // Laravel سيُنشئ عمودين: notifiable_id و notifiable_type
            // لتحديد الجهة التي تخصها الإشعارات (مثل User أو Admin)
            $table->morphs('notifiable');

            // محتوى بيانات الإشعار بصيغة JSON أو نص طويل
            $table->text('data');

            // وقت قراءة الإشعار (nullable لأنه قد لا يُقرأ بعد)
            $table->timestamp('read_at')->nullable();

            // الحقول التلقائية: created_at و updated_at
            $table->timestamps();
        });
    }

    /**
     * عكس عملية الـ Migration (حذف جدول الإشعارات)
     *
     * @return void
     */
    public function down()
    {
        // حذف جدول notifications إذا كان موجودًا
        Schema::dropIfExists('notifications');
    }
}
