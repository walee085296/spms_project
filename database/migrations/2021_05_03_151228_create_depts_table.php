
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeptsTable extends Migration
{
    /**
     * تشغيل عملية الـ Migration (إنشاء جدول الأقسام)
     *
     * هذا الجدول يُستخدم لتخزين أسماء الأقسام الأكاديمية
     * مثل: علوم الحاسب، نظم المعلومات، الذكاء الاصطناعي، إلخ.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depts', function (Blueprint $table) {
            // المفتاح الأساسي للجدول (id)
            $table->id();

            // اسم القسم (مثل: Computer Science أو Information Systems)
            $table->string('name');
        });
    }

    /**
     * عكس عملية الـ Migration (حذف الجدول)
     *
     * تُستخدم عند تنفيذ rollback لحذف جدول الأقسام.
     *
     * @return void
     */
    public function down()
    {
        // حذف جدول depts إذا كان موجودًا
        Schema::dropIfExists('depts');
    }
}
