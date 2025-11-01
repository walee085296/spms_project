<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * تنفيذ الـ Migration (إنشاء الجدول)
     *
     * هنا نقوم بإنشاء جدول "users" الذي يحتوي على بيانات المستخدمين داخل النظام.
     * هذا الجدول عادةً ما يُستخدم لتسجيل الدخول وإدارة الحسابات.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // العمود الأساسي (المفتاح الرئيسي Primary Key)
            $table->id();

            // الاسم الأول للمستخدم
            $table->string('first_name');

            // الاسم الأخير للمستخدم — يمكن أن يكون فارغًا (nullable)
            $table->string('last_name')->nullable();

            // رقم الطالب (Student Serial Number) — يمكن أن يكون فارغًا
            // هذا العمود مفيد في حالة أن النظام أكاديمي (مثل نظام مشاريع التخرج)
            $table->unsignedInteger('stdsn')->nullable();

            // التخصص أو القسم (specialization) — القيمة الافتراضية "none"
            $table->string('spec')->default('none');

            // البريد الإلكتروني — يجب أن يكون فريدًا (Unique)
            $table->string('email')->unique();

            // الصورة الشخصية للمستخدم (avatar) — لها قيمة افتراضية "default.jpg"
            $table->string('avatar')->default('default.jpg');

            // وقت تأكيد البريد الإلكتروني — يمكن أن يكون فارغًا
            // يستخدم لتحديد ما إذا كان المستخدم قد قام بتأكيد إيميله أم لا
            $table->timestamp('email_verified_at')->nullable();

            // كلمة المرور (Password)
            $table->string('password');

            // عمود خاص بلارافيل لتذكر المستخدم عند تسجيل الدخول (Remember Me)
            $table->rememberToken();

            // وقت آخر تسجيل دخول (لتتبع آخر مرة دخل فيها المستخدم)
            $table->timestamp('last_login_at')->nullable();

            // عنوان IP الذي تم الدخول منه آخر مرة — يمكن أن يكون فارغًا
            $table->string('last_login_ip')->nullable();

            // توقيت إنشاء السجل وتحديثه (Laravel يضيف created_at و updated_at تلقائيًا)
            $table->timestamps();
        });
    }

    /**
     * عكس عملية الـ Migration (حذف الجدول)
     *
     * يتم تنفيذ هذه الدالة في حال أردنا التراجع (rollback) عن إنشاء الجدول.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
