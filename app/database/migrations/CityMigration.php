<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class CityMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('cities');
        Capsule::schema()->create('cities', function($table) {
            $table->increments('id');
            $table->string('alias');
            $table->timestamps();
        });
    }
}
