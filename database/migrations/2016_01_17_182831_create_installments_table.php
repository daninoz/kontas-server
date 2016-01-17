<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_card_purchases', function (Blueprint $table) {
            $table->dropColumn('number_pays');
            $table->dropColumn('credit_card_id');
        });

        Schema::create('installments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('amount');
            $table->unsignedInteger('credit_card_purchase_id');
            $table->unsignedInteger('statement_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('installments');

        Schema::table('credit_card_purchases', function (Blueprint $table) {
            $table->unsignedTinyInteger('number_pays');
            $table->unsignedInteger('credit_card_id');
        });
    }
}
