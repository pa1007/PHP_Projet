<?php


namespace mywishlist\controller;


use mywishlist\vue\VueParticipant;

class ListController
{
    public function __construct(){

    }

    public function getList( $no )
    {
        $list = \mywishlist\model\Liste::where('no', '=', $no)->first();
        $v = new VueParticipant($list);
        $v->render();
    }

}