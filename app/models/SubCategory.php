<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SubCategory extends Eloquent {

    public function category()
    {
        return $this->hasOne('Category');
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
	protected $table = 'subcategories';
}
