<?php

namespace mywishlist\controller;

use mywishlist\model\Item;
use mywishlist\vue\VueParticipant;

class ItemController {

    public function __construct() {

    }

    public function getItem($id) {
        $item = Item::where('id', '=', $id)->first();
        $v = new VueParticipant($item);
        $v->render(VueParticipant::ITEM);
    }
}

