<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class UserMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('users');
        Capsule::schema()->create('users', function($table) {
            $table->increments('id');
            $table->string('username');
            $table->string('phone');
            $table->string('address');
            $table->string('hash');
            $table->timestamps();
            $table->boolean('banned');
            $table->boolean('deleted');
            $table->integer('city_id')->unsigned();
            //$table->integer('city_id')->after('id')->nullable()->unsigned();
            $table->foreign('city_id')->references('id')->on('cities');//->onDelete('cascade');
            }
        );
    }
}
