<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTheForeignIdOfStarsAndUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('stars', function($table){
            $table->integer('user_id')->unsigned()->index();
            $table->integer('report_experiment_id')->unsigned()->index();
            $table->foreign('report_experiment_id')->references('experiment_id')->on('reports')->onDelete('restrict')->onUpdate('restrict');
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
        Schema::table('stars', function(Blueprint $table) {
            $table->dropForeign('stars_report_experiment_id_foreign');
            $table->dropColumn('user_id');
            $table->dropColumn('report_experiment_id');
        });

    }
}
