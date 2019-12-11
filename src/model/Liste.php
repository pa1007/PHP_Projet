<?php

namespace mywishlist\model;

use Illuminate\Database\Eloquent\Model;

class Liste extends Model {

    public $timestamps = false;
    protected $primaryKey = "no";
    protected $table = "liste";


    public function Item() {
        return $this->hasMany('mywishlist\model\Item', 'liste_id');
    }



}