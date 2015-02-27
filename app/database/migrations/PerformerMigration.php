<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class PerformerMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('performers');
        Capsule::schema()->create('performers', function($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');//->onDelete('cascade');
            $table->timestamps();
        });
    }
}
