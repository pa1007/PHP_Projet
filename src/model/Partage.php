<?php


namespace mywishlist\model;


use Illuminate\Database\Eloquent\Model;

class Partage extends Model {

    public $timestamps = false;
    protected $primaryKey = "id";
    protected $table = "partage";

    public function liste() {
        return $this->belongsTo('mywishlist\model\Liste', "idliste");
    }

}