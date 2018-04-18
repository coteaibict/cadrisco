<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMesoregionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesoregions', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('name');
            $table->integer('state')->unsigned();
            $table->timestamps();

            $table->foreign('state')
                ->references('id')
                ->on('states')
                ->onDelete('cascade');

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mesoregions');
    }
}
