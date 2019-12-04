<?php


use Illuminate\Database\Capsule\Manager as DB;
use mywishlist\controller\ItemController;

require_once __DIR__ . '/vendor/autoload.php';

$app = new \Slim\Slim;
$app->get('/Controller', function (){
    $itemController = new ItemController();
    $itemController->getItem(1);
    });

$db = new DB();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app->run();

