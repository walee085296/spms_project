<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFailedJobsTable extends Migration
{
    /**
     * تشغيل الـ Migration (إنشاء جدول failed_jobs)
     *
     * هذا الجدول يقوم بتخزين المهام (jobs) التي فشلت أثناء تنفيذها.
     * Laravel يستخدم نظام الـ Queues لمعالجة المهام في الخلفية (background jobs)،
     * وإذا حدث خطأ في تنفيذ مهمة معينة، يتم حفظ بياناتها هنا.
     */
    public function up()
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            // المفتاح الأساسي (Primary Key)
            $table->id();

            // رقم UUID فريد لتعريف كل Job فاشلة
            $table->string('uuid')->unique();

            // اسم الاتصال (connection) المستخدم في الـ Queue
            $table->text('connection');

            // اسم الطابور (queue) الذي كانت فيه المهمة
            $table->text('queue');

            // تفاصيل المهمة (job) نفسها محفوظة كـ JSON
            $table->longText('payload');

            // نص الخطأ أو الاستثناء (exception) الذي تسبب في الفشل
            $table->longText('exception');

            // وقت حدوث الفشل (يُحفظ تلقائيًا بوقت التنفيذ)
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * عكس عملية الـ Migration (حذف الجدول)
     *
     * تُستخدم عند تنفيذ rollback لحذف جدول failed_jobs.
     */
    public function down()
    {
        Schema::dropIfExists('failed_jobs');
    }
}
