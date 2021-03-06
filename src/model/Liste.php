<?php

namespace mywishlist\model;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Liste extends Model {

    public $timestamps = false;
    protected $primaryKey = "no";
    protected $table = "liste";


    public function Item() {
        return $this->hasMany('mywishlist\model\Item', 'liste_id');
    }

    public function comm() {
        return $this->hasMany("mywishlist\model\Commentaire", 'liste_id');
    }

    public function partages() {
        return $this->hasMany("mywishlist\model\Partage", "idliste");
    }

    public function hasExpire(): bool {
        try {
            return new DateTime() > new DateTime($this->expiration);
        } catch (\Exception $e) {
            return false;
        }
    }
}