<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuoteTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_tag', function (Blueprint $table) {
            $table->unsignedInteger('quote_id');
            $table->unsignedInteger('tag_id');

            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_tag', function ($table) {
            $table->dropForeign(['tag_id']);
            $table->dropForeign(['quote_id']);
        });
        Schema::drop('quote_tag');
    }
}
