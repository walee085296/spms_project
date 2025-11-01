<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    /**
     * تشغيل عملية الـ Migration (إنشاء الجداول الخاصة بالأدوار والصلاحيات)
     *
     * @return void
     */
    public function up()
    {
        // جلب أسماء الجداول من ملف الإعدادات config/permission.php
        $tableNames = config('permission.table_names');

        // جلب أسماء الأعمدة الخاصة بالعلاقات polymorphic
        $columnNames = config('permission.column_names');

        // التحقق من أن ملف الإعدادات موجود ومحمّل
        if (empty($tableNames)) {
            throw new \Exception('خطأ: ملف config/permission.php غير محمّل. استخدم الأمر [php artisan config:clear] ثم حاول مرة أخرى.');
        }

        /*
         |--------------------------------------------------------------------------
         | جدول الصلاحيات (permissions)
         |--------------------------------------------------------------------------
         */
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');       // اسم الصلاحية (مثل: edit posts, delete users)
            $table->string('guard_name'); // اسم الـ guard المستخدم (مثل: web أو api)
            $table->timestamps();

            // منع تكرار نفس الاسم مع نفس الـ guard
            $table->unique(['name', 'guard_name']);
        });

        /*
         |--------------------------------------------------------------------------
         | جدول الأدوار (roles)
         |--------------------------------------------------------------------------
         */
        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');       // اسم الدور (مثل: admin, editor, user)
            $table->string('guard_name'); // اسم الـ guard
            $table->timestamps();

            // منع تكرار نفس الدور مع نفس الـ guard
            $table->unique(['name', 'guard_name']);
        });

        /*
         |--------------------------------------------------------------------------
         | جدول ربط النماذج بالصلاحيات (model_has_permissions)
         | يربط الصلاحيات بموديلات مثل User أو Admin
         |--------------------------------------------------------------------------
         */
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('permission_id'); // مفتاح خارجي يشير إلى جدول permissions

            $table->string('model_type');                 // نوع الموديل (مثل App\Models\User)
            $table->unsignedBigInteger($columnNames['model_morph_key']); // معرف الموديل (id)

            // إنشاء فهرس لسهولة البحث باستخدام (model_id + model_type)
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            // علاقة المفتاح الخارجي مع جدول permissions
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade'); // حذف الصلاحيات إذا تم حذف الأصل

            // جعل الثلاثة أعمدة كمفتاح أساسي مركّب
            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_permissions_permission_model_type_primary');
        });

        /*
         |--------------------------------------------------------------------------
         | جدول ربط النماذج بالأدوار (model_has_roles)
         | يربط الأدوار بموديلات مثل User أو Staff
         |--------------------------------------------------------------------------
         */
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('role_id'); // مفتاح خارجي يشير إلى جدول roles

            $table->string('model_type');          // نوع الموديل (User, Admin, ...)
            $table->unsignedBigInteger($columnNames['model_morph_key']); // معرف الموديل (id)

            // فهرس لسهولة البحث
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            // علاقة المفتاح الخارجي مع جدول roles
            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade'); // حذف العلاقات لو تم حذف الدور

            // جعل الثلاثة أعمدة كمفتاح أساسي مركّب
            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_roles_role_model_type_primary');
        });

        /*
         |--------------------------------------------------------------------------
         | جدول ربط الأدوار بالصلاحيات (role_has_permissions)
         | يحدد ما هي الصلاحيات المرتبطة بكل دور
         |--------------------------------------------------------------------------
         */
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id'); // مفتاح خارجي يشير إلى جدول permissions
            $table->unsignedBigInteger('role_id');       // مفتاح خارجي يشير إلى جدول roles

            // ربط الدور بالصلاحيات
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            // جعل combination (permission_id + role_id) مفتاح أساسي
            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        // تفريغ الكاش الخاص بالصلاحيات لتحديث النظام بعد إنشاء الجداول
        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
    }

    /**
     * عكس عملية الـ Migration (حذف جميع الجداول الخاصة بالصلاحيات والأدوار)
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('خطأ: ملف config/permission.php غير موجود، ولم يتم تحميل القيم الافتراضية. يرجى نشر إعدادات الحزمة أو حذف الجداول يدويًا.');
        }

        // حذف الجداول بالترتيب المعاكس للعلاقات
        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
