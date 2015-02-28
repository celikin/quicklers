<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent {

    public function city()
    {
        return $this->hasOne('City');
    }
    public function performer()
    {
        return $this->hasOne('Performer');
    }
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('hash');
}
