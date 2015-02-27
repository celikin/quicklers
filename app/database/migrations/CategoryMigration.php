<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class CategoryMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('categories');
        Capsule::schema()->create('categories', function($table) {
            $table->increments('id');
            $table->string('alias');
            $table->string('desc');
            $table->timestamps();
        });
    }
}
