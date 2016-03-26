<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MovesCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });

        Schema::table('simple_expenses', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->after('amount');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });

        Schema::table('simple_expenses', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->after('type_type');
        });
    }
}
