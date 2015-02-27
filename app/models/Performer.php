<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Performer extends Eloquent {
    public function user()
    {
        return $this->hasOne('User');
    }
    public function bid()
    {
        return $this->hasMany('Bid');
    }
    public function performersBidStack()
    {
        return $this->hasMany('PerformersBidStack');
    }
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'performers';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

}
