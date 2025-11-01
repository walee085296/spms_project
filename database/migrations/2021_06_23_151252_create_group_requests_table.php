<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupRequestsTable extends Migration
{
    /**
     * تشغيل عملية الـ Migration (إنشاء جدول group_requests)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_requests', function (Blueprint $table) {
            // المفتاح الأساسي للجدول (id)
            $table->id();

            // معرف المجموعة المرتبطة (علاقة مع جدول groups)
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')
                ->references('id')->on('groups')
                ->onDelete('cascade'); // حذف الطلبات إذا تم حذف المجموعة

            // معرف المستخدم الذي أرسل الطلب (المرسل)
            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')
                ->references('id')->on('users')
                ->onDelete('cascade'); // حذف الطلب إذا تم حذف المستخدم المرسل

            // معرف المستخدم الذي استقبل الطلب (المستقبِل)
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->foreign('receiver_id')
                ->references('id')->on('users')
                ->onDelete('set null'); // إذا تم حذف المستخدم المستقبِل → اجعل القيمة null

            // حالة الطلب (مثلاً: pending, accepted, rejected)
            $table->string('status');

            // الحقول التلقائية: created_at و updated_at
            $table->timestamps();
        });
    }

    /**
     * عكس عملية الـ Migration (حذف الجدول)
     *
     * @return void
     */
    public function down()
    {
        // حذف جدول group_requests إذا كان موجودًا
        Schema::dropIfExists('group_requests');
    }
}
