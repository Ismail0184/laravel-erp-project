<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('acc_countries', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->string('name');
			$table->string('continent');
			$table->string('short');
			$table->integer('code');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('countries');
    }
}