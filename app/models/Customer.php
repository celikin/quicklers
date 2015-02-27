<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Customer extends Eloquent {
    public function user()
    {
        return $this->hasOne('User');
    }
    public function bid()
    {
        return $this->hasMany('Bid');
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';
}