<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Comment extends Eloquent {
    public function user()
    {
        return $this->hasOne('User');
    }
    public function bid()
    {
        return $this->hasOne('Bid');
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';
}