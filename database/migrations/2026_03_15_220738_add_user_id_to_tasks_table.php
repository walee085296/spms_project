<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $tasks) {
            // المعرف الأساسي
            $tasks->id();

            // ربط المهمة بالمشروع (Foreign Key)
            // استخدام constrained يضمن وجود المشروع قبل إنشاء المهمة
            // واستخدام onDelete('cascade') يمسح المهمة لو المشروع اتحذف
            $tasks->foreignId('project_id')
                      ->constrained('projects')
                      ->onDelete('cascade');

            // وصف المهمة (desc)
            $tasks->text('desc'); 

            // حالة المهمة (state) 
            // جعلناها integer مع قيمة افتراضية 0
            $tasks->integer('state')->default(0);

            $tasks->string('url')->nullable(); // رابط المهمة (اختياري)
            $tasks->string('comment')->nullable()->default(null); // تعليق المهمة (اختياري) 
            
        
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};