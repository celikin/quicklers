<?php


class UserSeed {

    function run()
    {
        $user = new User;
        $user->username = "Test user";
        $user->city_id = 1;
        $user->save();
    }
}
