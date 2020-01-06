<?php


namespace mywishlist\model;


use Illuminate\Database\Eloquent\Model;

class Cagnotte extends Model
{

    public $timestamps = false;
    protected $table = "cagnotte";

    public function Item() {
        return $this->belongsTo('mywishlist\model\Item', 'id_item');
    }


}