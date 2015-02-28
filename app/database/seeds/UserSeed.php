<?php


class UserSeed {

    function run()
    {
        $user = new User;
        $user->username = "ololosh";
        $user->city_id = "1";
        $user->save();
        $user1 = new User;
        $user1->username = "qqw";
        $user1->city_id = "1";
        $user1->save();

    }
}
