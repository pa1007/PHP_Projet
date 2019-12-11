<?php


use Illuminate\Database\Capsule\Manager as DB;
use mywishlist\controller\ItemController;
use mywishlist\controller\ListController;
use mywishlist\controller\ModifController;
use mywishlist\vue\VueError;
use mywishlist\vue\VueIndex;
use Slim\Slim;

require_once __DIR__ . '/vendor/autoload.php';
session_start();
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

//Recupere le formulaire et l'affiche
$app->get('/createitem', function () {
    $itemContr = new ItemController();
    $itemContr->seeFormCrea();
})->setName('creaItem');

//Recupérer la reponse au formulaire
$app->post('/createitem', function () {
    $itemContr = new ItemController();
    $itemContr->postCreaForm();
});

$app->get("/modif/:type/:token", function ($type, $token) {
    $modifController = new ModifController($type, filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->process();
});
$app->post("/modif/:type/:token", function ($type, $token) {
    $modifController = new ModifController($type, filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->modifyItem();
});


$app->get('/', function () {
    $vueIndex = new VueIndex();
    $vueIndex->render(1);
})->setName("Menu");

$db = new DB();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app->run();

