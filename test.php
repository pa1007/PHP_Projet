<?php

use Illuminate\Database\Capsule\Manager as DB;
use mywishlist\model\Item;
use mywishlist\model\Liste;

require_once __DIR__ . '/vendor/autoload.php';

$db = new DB();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();
$v = Liste::get();

foreach ($v as $item) {
    echo $item;
}
echo "\n";
$vI = Item::get();

foreach ($vI as $item) {
    echo $item;
}
echo "\n";
$i = Item::where("id", "=", $_GET['id'])->first();
echo $i;

$nI = new Item();
$nI->nom = "tests";
$nI->liste_id = -1;
$nI->descr = "tests";
$nI->save();
$v = Liste::first();

