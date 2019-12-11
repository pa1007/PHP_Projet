<?php


namespace mywishlist\controller;


use mywishlist\model\Liste;
use mywishlist\vue\VueParticipant;

class ListController
{
    public function __construct(){

    }

    public function getList( $no )
    {
        $list = Liste::where('no', '=', $no)->first();
        $v = new VueParticipant($list);
        $v->render(VueParticipant::LISTE);
    }

    public function CreerListe($titre, $desc, $datexp){

    }

}