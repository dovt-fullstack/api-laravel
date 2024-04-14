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
       Schema::create('Details_User_Choose', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('idUser');
    $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');
    $table->string('question');
    $table->string('question_id');
    $table->string('image')->nullable();
    $table->json('options')->default('{}'); // Sửa đổi ở đây
    $table->string('answer');
    $table->integer('point');
    $table->json('choose');
    $table->string('userChoose')->nullable();
    $table->boolean('select')->default(false);
    $table->timestamps();
});

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Details_User_Choose');
    }
};
