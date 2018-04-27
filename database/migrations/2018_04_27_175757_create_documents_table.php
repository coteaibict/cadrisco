<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('ordinance');
            $table->string('declaration');
            $table->string('role');
            $table->integer('state_id')->nullable();
            $table->integer('county_id')->nullable();
            $table->text('note')->nullable();
            $table->string('situation');
            $table->timestamps();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('county_id')->references('id')->on('counties')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
