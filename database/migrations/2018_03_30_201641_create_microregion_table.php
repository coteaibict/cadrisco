<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMicroregionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('microregions', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('name');
            $table->integer('mesoregion')->unsigned();
            $table->timestamps();

            $table->foreign('mesoregion')
                ->references('id')
                ->on('mesoregions')
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
        Schema::dropIfExists('microregions');
    }
}
