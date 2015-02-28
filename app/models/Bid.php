<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Bid extends Eloquent {
    public function performersBidStack()
    {
        return $this->hasOne('PerformersBidStack');
    }
    public function city()
    {
        return $this->hasOne('City');
    }
    public function user()
    {
        return $this->hasOne('User');
    }
    public function performer()
    {
        return $this->hasOne('Performer');
    }
    public function subCategory()
    {
        return $this->hasOne('SubCategory');
    }
    public function comment()
    {
        return $this->hasMany('Comment');
    }

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'bids';
}
