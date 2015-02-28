<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class CustomerMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('customers');
        Capsule::schema()->create('customers', function($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

} 