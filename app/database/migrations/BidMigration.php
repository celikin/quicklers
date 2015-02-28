<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 *
        * Bids
        Таблица заявок
        INTEGER	id	PK
        VARCHAR	title
        VARCHAR	desc
        TIMESTAMP	deadline
        TIMESTAMP	date_create
        VARCHAR	address
        INTEGER	cost
        INTEGER	status
        INTEGER	city_id	FK
        INTEGER	customer_id	FK
        INTEGER
        performer_id
        FK
        INTEGER	subcategory_id	FK

 */
class BidMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('bids');
        Capsule::schema()->create('bids', function($table) {
            $table->increments('id');
            $table->string('title');
            $table->string('desc');
            $table->timestamp('deadline');
            $table->timestamps();
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('performer_id')->unsigned();
            $table->foreign('performer_id')->references('id')->on('performers');
            $table->integer('subcategory_id')->unsigned();
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
        });
    }
}
