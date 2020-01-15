<?php

namespace mywishlist\model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'uid';
    protected $table = "user";

    public function listes() {
        return $this->hasMany("mywishlist\model\Liste", "user_id", 'uid');
    }

    public function reservation() {
        return $this->hasMany("mywishlist\model\Reservation", "userID");
    }
}