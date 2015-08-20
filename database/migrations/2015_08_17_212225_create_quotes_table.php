<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36);
            $table->text('content');
            $table->string('hash')->unique();
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('likes')->default(0);
            $table->boolean('published')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotes', function ($table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['author_id']);
        });
        Schema::drop('quotes');
    }
}
