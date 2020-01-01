<?php


namespace mywishlist\model;


use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model{

    public $timestamps = false;
    protected $table = "commentaire";

    public function Liste() {
        return $this->belongsTo('mywishlist\model\Liste', 'liste_id');
    }


}