<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل عملية الـ Migration (إنشاء جدول group_user)
     *
     * هذا الجدول هو جدول وسيط (Pivot Table)
     * يربط بين جدول المجموعات (groups) وجدول المستخدمين (users)
     * لتطبيق علاقة "Many to Many" بينهما.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_user', function (Blueprint $table) {
            // معرف المجموعة المرتبطة
            $table->unsignedBigInteger('group_id');

            // إنشاء علاقة مفتاح خارجي بين group_id وجدول groups
            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade'); // إذا تم حذف المجموعة، تُحذف علاقتها بالمستخدمين

            // معرف المستخدم المرتبط
            $table->unsignedBigInteger('user_id');

            // إنشاء علاقة مفتاح خارجي بين user_id وجدول users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); // إذا تم حذف المستخدم، تُحذف علاقته بالمجموعة

            // (اختياري) يمكن إضافة مفتاح أساسي مركّب لمنع التكرار:
            // $table->primary(['group_id', 'user_id']);
        });
    }

    /**
     * عكس عملية الـ Migration (حذف الجدول)
     *
     * @return void
     */
    public function down()
    {
        // حذف جدول group_user في حالة التراجع
        Schema::dropIfExists('group_user');
    }
};
