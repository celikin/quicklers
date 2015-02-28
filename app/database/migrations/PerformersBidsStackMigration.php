<?php
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class PerformersBidsStackMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('performersbidsstacks');
        Capsule::schema()->create('performersbidsstacks', function($table) {
            $table->increments('id');
            $table->integer('cost');
            $table->integer('performer_id')->unsigned();
            $table->foreign('performer_id')->references('id')->on('performers');
            $table->integer('bid_id')->unsigned();
            $table->foreign('bid_id')->references('id')->on('bids');
            $table->timestamps();
        });
    }
} 