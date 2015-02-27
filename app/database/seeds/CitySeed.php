<?php


class CitySeed {

    function run()
    {
        $city = new City;
        $city->alias = "Vladivostok";
        $city->save();
    }
}
