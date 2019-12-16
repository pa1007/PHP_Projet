<?php


namespace mywishlist\model;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model{

    public $timestamps = false;
    protected $primaryKey = "idItem";
    protected $table = "reservation";

    public function Item() {
        return $this->hasMany('mywishlist\model\Item');
    }

}