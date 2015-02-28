<?php


class PerformerSeed {

    function run()
    {
        $performer = new Performer;
        $performer->user_id = "2";
        $performer->save();
    }
}
