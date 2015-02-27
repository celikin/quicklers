<?php
/**
 * Created by IntelliJ IDEA.
 * User: kun
 * Date: 28.02.2015
 * Time: 9:31
 */

class Utills {

    public function CheckAuth($phone, $hash){
        $result = false;
        $user = User::where('phone', '=', $phone);
        if($user->hash == $hash){
            $result = true;
        }
        return $result;
    }

} 