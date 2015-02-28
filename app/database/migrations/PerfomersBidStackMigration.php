<?php
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class PerfomersBidStackMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('PerfomersBidsStacks');
        Capsule::schema()->create('PerfomersBidsStacks', function($table) {
            $table->increments('id');
            $table->increments('performer_id')->unsigned();
            $table->foreign('performer_id')->references('id')->on('performers');
            $table->increments('bid_id')->unsigned();
            $table->foreign('bid_id')->references('id')->on('bids');
            $table->timestamps();
        });
    }
} 