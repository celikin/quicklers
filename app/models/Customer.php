<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Customer extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';
}