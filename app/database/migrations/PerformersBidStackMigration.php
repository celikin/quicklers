<?php
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class PerformersBidStackMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('performersbidsstacks');
        Capsule::schema()->create('performersbidsstacks', function($table) {
            $table->increments('id');
            $table->increments('performer_id')->unsigned();
            $table->foreign('performer_id')->references('id')->on('performers');
            $table->increments('bid_id')->unsigned();
            $table->foreign('bid_id')->references('id')->on('bids');
            $table->timestamps();
        });
    }
} 