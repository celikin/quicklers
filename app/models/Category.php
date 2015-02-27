<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Category extends Eloquent {

    public function subCategory()
    {
        return $this->hasMany('SubCategory');
    }
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';
}
