<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class City extends Eloquent {

    public function user()
    {
        return $this->hasMany('User');
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
	protected $table = 'cities';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

}
