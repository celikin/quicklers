<?php


class CitySeed {

    function run()
    {
        $city = new City;
        $city->alias = "Vlad";
        $city->save();
    }
}
