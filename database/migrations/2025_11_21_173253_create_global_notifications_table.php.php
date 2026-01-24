<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 public function up()
{
    Schema::create('global_notifications', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('message');
        $table->unsignedBigInteger('created_by');
        $table->string('for_role')->default('students'); // students / all
        $table->boolean('status')->default(1);
        $table->timestamps();

        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
