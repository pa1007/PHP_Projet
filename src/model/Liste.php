<?php

namespace mywishlist\model;

use Illuminate\Database\Eloquent\Model;

class Liste extends Model {

    public $timestamps = false;
    protected $primaryKey = "no";
    protected $table = "liste";


    public function Item()
    {
        return $this->hasMany('mywhishlist\model\Item', 'user_id');
    }

    public function creerListe($n,$us,$tit,$des,$exp,$tok){
        $l= new Liste();
        $l->no = $n;
        $l->user_id=$us;
        $l->titre=$tit;
        $l->description=$des;
        $l->expiration=$exp;
        $l->token=$tok;

        $l->save();
    }

}