<?php

class PerformerSeed {

    function run()
    {
        $performer = new Performer;
        $performer->save();
    }
}
