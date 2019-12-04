<?php

namespace mywishlist\controller;

use mywishlist\vue\VueParticipant;

class ItemController {

    public function __construct(){

    }

    public function getItem( $id )
    {
        $item = \mywishlist\model\Item::where('id', '=', $id)->first();
        $v = new VueParticipant($item);
        $v->render();
    }

    /*public function listItem() {
        $liste = Item::OrderBy('titre')->get() ;
        $v = new ItemView( $liste , LIST_VIEW ) ;
        $v->render() ;
    }


*/
}

