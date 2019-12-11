<?php


use Illuminate\Database\Capsule\Manager as DB;
use mywishlist\controller\ItemController;
use mywishlist\controller\ListController;
use mywishlist\controller\ModifController;
use mywishlist\vue\VueError;
use Slim\Slim;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Slim;
$app->get('/item/:id', function ($id) {
    $itemController = new ItemController();
    $itemController->getItem($id);
});
$app->get('/liste/:no', function ($no) {
    $listController = new ListController();
    $listController->getList($no);
});

$app->get('/error', function () {
    $vueError = new VueError();
    $vueError->render(1);
})->setName("Error");

$app->get("/modif/:type/:token", function ($type, $token) {
    $modifController = new ModifController($type, filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->process();
});
$app->post("/modif/:type/:token", function ($type, $token) {
    $modifController = new ModifController($type, filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->modifyItem();
});


$db = new DB();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app->run();

