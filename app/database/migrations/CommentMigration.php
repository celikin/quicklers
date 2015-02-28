<?php
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class CommentMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('comments');
        Capsule::schema()->create('comments', function($table) {
            $table->increments('id');
            $table->string('desc');
            $table->integer('rate');
            $table->integer('bid_id')->unsigneed();
            $table->foreign('bid_id')->references('id')->on('bids');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->refernces('id')->on('users');
            $table->timestamps();
        });
    }
} 