<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class CustomerMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('costumers');
        Capsule::schema()->create('costumers', function($table) {
            $table->increments('id');
            $table->increments('user_id');
            $table->timestamps();
        });
    }

} 