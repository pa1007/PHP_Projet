<?php


use Illuminate\Database\Capsule\Manager as DB;
use mywishlist\controller\ItemController;

require_once __DIR__ . '/vendor/autoload.php';

$app = new \Slim\Slim;
$app->get('/item/:id', function ($id){
    $itemController = new ItemController();
    $itemController->getItem($id);
    });

$db = new DB();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app->run();

