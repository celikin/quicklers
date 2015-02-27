<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Example migration for use with "novice"
 */
class SubCategoryMigration {
    function run()
    {
        Capsule::schema()->dropIfExists('subcategories');
        Capsule::schema()->create('subcategories', function($table) {
            $table->increments('id');
            $table->string('alias');
            $table->timestamps();
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }
}
