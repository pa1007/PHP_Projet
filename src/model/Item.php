<?php

namespace mywishlist\model;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

    public $timestamps = false;

    protected $table = "item";

    public function Liste(){
        return $this->belongsTo('mywhishlist\model\Liste','liste_id');
    }

}