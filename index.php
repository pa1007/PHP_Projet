<?php


use Illuminate\Database\Capsule\Manager as DB;
use mywishlist\controller\CompteController;
use mywishlist\controller\ItemController;
use mywishlist\controller\ListController;
use mywishlist\controller\ModifController;
use mywishlist\vue\VueError;
use mywishlist\vue\VueIndex;
use Slim\Slim;

require_once __DIR__ . '/vendor/autoload.php';
session_start();
$app = new Slim;

$app->notFound(function () {
    $vueError = new VueError();
    $vueError->render(1);
});


$app->get('/liste/:no/item/:id', function ($no, $id) {
    $itemController = new ItemController();
    $itemController->getItem($id, $no);
});
$app->get('/liste/:no', function ($no) {
    $listController = new ListController();
    $listController->getList($no);
});

$app->post('/liste/:id', function ($id) {
    $list = new ListController();
    $list->MessageAjoute($id);
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

$app->get('/createliste', function () {
    $list = new ListController();
    $list->seeFormCrea();
})->setName('creaListe');

$app->post('/createliste', function () {
    $list = new ListController();
    $list->postCreaForm();
});

$app->get('/publique', function () {
    $list = new ListController();
    $list->publicListe();
});


$app->get("/createcompte", function () {
    $compteController = new CompteController();
    $compteController->compteCrea();
})->setName("createcompte");

$app->post("/createcompte", function () {
    $compteController = new CompteController();
    $compteController->postCompteCrea();
});

$app->get("/modif/:type/:token", function ($type, $token) {
    $modifController = new ModifController($type, filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->process();
});
$app->get("/modif/item/:token/changeImage", function ($token) {
    $modifController = new ModifController("item", filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->changeImageForm();
});
$app->post("/modif/liste/:token/partager", function ($token) {
    $modifController = new ModifController("item", filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->partagerListe();
});
$app->post("/modif/item/:token/changeImage", function ($token) {
    $modifController = new ModifController("item", filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->modifyImage();
});
$app->delete("/modif/item/:token/changeImage/sup/:id", function ($token, $id) {
    $modifController = new ModifController("item", filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->deleleImage($id);
});
$app->post("/modif/:type/:token", function ($type, $token) {
    $modifController = new ModifController($type, filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->modify();
});
$app->delete("/modif/:type/:token", function ($type, $token) {
    $modifController = new ModifController($type, filter_var($token, FILTER_SANITIZE_STRING));
    $modifController->delete();
});

$app->post("/liste/:token/item/:id", function ($token, $id) {
    $reserv = new ItemController();
    $reserv->reserverItem($id, $token);
});
$app->post('/liste/:no/item/:id/cadd', function ($no, $id) {
    $itemController = new ItemController();
    $itemController->addCagnotte($id, $no);
});
$app->get('/connect', function () {
    $cCont = new CompteController();
    $cCont->formConn();
})->setName('connect');

$app->post('/connect', function () {
    $cCont = new CompteController();
    $cCont->auth();
});

$app->get('/createur',function(){
    $lc = new ListController();
    $lc->verifListePublique();
});

$app->get('/connected/', function () {
    $cCont = new CompteController();
    $cCont->connected();
})->setName('compteco');

$app->post("/connected/addModif", function () {
    $cCont = new CompteController();
    $cCont->addToken();
});
$app->get('/logout', function () {
    $cCont = new CompteController();
    $cCont->logout();
});

$app->post("/connected/modifCompte", function () {
    $cCont = new CompteController();
    $cCont->modifCompte();
});
$app->delete("/connected/modifCompte", function () {
    $cCont = new CompteController();
    $cCont->deleteCompte();
});

$app->get('/', function () {
    $vueIndex = new VueIndex();
    $vueIndex->render(1);
})->setName("Menu");

$app->get('/participe/:token', function ($id) {
    $listCont = new ListController();
    $listCont->afficherTokenPartage(filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS));
});

$db = new DB();
$db->addConnection(parse_ini_file("src/conf/conf.ini"));
$db->setAsGlobal();
$db->bootEloquent();

$app->run();

