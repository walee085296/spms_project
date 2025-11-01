<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends Migration
{
    /**
     * تشغيل الـ Migration
     *
     * يتم هنا إنشاء جدول "password_resets"
     * الذي يُستخدم لتخزين رموز (tokens) إعادة تعيين كلمة المرور للمستخدمين.
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            // البريد الإلكتروني للمستخدم الذي طلب إعادة تعيين كلمة المرور
            $table->string('email')->index();

            // رمز إعادة التعيين (token) الذي يتم إرساله إلى البريد الإلكتروني
            $table->string('token');

            // وقت إنشاء الطلب (عشان تقدر Laravel تتحقق من صلاحية الطلب)
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * عكس عملية الـ Migration (حذف الجدول)
     *
     * يتم حذف الجدول في حالة تنفيذ rollback.
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
