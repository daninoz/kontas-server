<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakesEstimationEndDateNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimations', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });


        Schema::table('estimations', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimations', function (Blueprint $table) {
            $table->dropColumn('end_date');
        });

        Schema::table('estimations', function (Blueprint $table) {
            $table->date('end_date')->after('start_date');
        });
    }
}
