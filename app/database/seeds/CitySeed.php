<?php

class CitySeed {
    function run()
    {
        $category = new City;
        $category->alias = "Vladivostok";
        $category->save();
    }

}

