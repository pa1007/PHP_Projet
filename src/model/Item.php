<?php

namespace mywishlist\model;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

    public $timestamps = false;
    protected $table = "item";

    public function Liste() {
        return $this->belongsTo('mywishlist\model\Liste', 'liste_id');
    }

    public function Reservation() {
        return $this->belongsTo('mywishlist\model\Reservation', 'id');
    }

    public function images() {
        return $this->hasMany("mywishlist\model\Images", "id_item");
    }

    public function cagnotte() {
        return $this->hasOne('mywishlist\model\Cagnotte', "id_item");
    }

}