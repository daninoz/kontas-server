<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_card_dates', function (Blueprint $table) {
            $table->date('period')->after('id');
            $table->boolean('has_real_dates')->after('deadline');
        });

        Schema::rename('credit_card_dates', 'statements');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('statements', 'credit_card_dates');

        Schema::table('credit_card_dates', function (Blueprint $table) {
            $table->drop('has_real_dates');
            $table->drop('period');
        });
    }
}
