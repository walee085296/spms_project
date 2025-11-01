<?php  

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * تشغيل عملية الـ Migration (إضافة الأعمدة الجديدة)
     *
     * @return void
     */
    public function up()
    {
        // تعديل جدول المستخدمين (users)
        Schema::table('users', function ($table) {
            // إضافة عمود لتخزين معرف المستخدم في GitHub
            $table->string('github_id')->nullable();
            
            // إضافة عمود لتخزين التوكن الخاص بـ GitHub (للمصادقة)
            $table->string('github_token')->nullable();
            
            // إضافة عمود لتخزين الـ refresh token في حال انتهاء صلاحية التوكن الأصلي
            $table->string('github_refresh_token')->nullable();
        });
    }

    /**
     * عكس عملية الـ Migration (حذف الأعمدة التي تمت إضافتها)
     *
     * @return void
     */
    public function down()
    {
        // إزالة الأعمدة التي أضفناها في دالة up()
        Schema::table('users', function ($table) {
            $table->dropColumn('github_id');
            $table->dropColumn('github_token');
            $table->dropColumn('github_refresh_token');
        });
    }
};
