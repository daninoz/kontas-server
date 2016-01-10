<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->date('date');
            $table->unsignedInteger('source_id');
            $table->string('source_type');
            $table->unsignedInteger('type_id');
            $table->string('type_type');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('expenses');
    }
}
