<?php

namespace mywishlist\controller;

use mywishlist\model\Item;
use mywishlist\model\Reservation;
use mywishlist\vue\VueParticipant;
use Slim\Slim;

class ItemController {

    public function __construct() {

    }

    public function getItem($id) {
        $item = Item::where('id', '=', $id)->first();
        $v = new VueParticipant($item);
        $v->render(VueParticipant::ITEM);
    }

    public function seeFormCrea() {
        $msg = "";
        if (isset($_COOKIE['Error'])) {
            $msg = $_COOKIE['Error'];
            setcookie("Error", "", 1);
        }
        $v = new VueParticipant($msg);
        $v->render(VueParticipant::ITEM_CREA);
    }

    public function postCreaForm() {
        $slim = Slim::getInstance();
        $it = 0;
        if (isset($_POST['nom']) && $_POST["nom"] !== "" && isset($_POST["Description"]) && $_POST["Description"] !== "" && isset($_POST["number"])
            && $_POST["number"] !== "" && isset($_POST['singlebutton']) && $_POST['singlebutton'] === "Submit") {
            $item = new Item();
            $item->nom = filter_var($_POST['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
            $item->descr = filter_var($_POST['Description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $item->tarif = filter_var($_POST['number'], FILTER_SANITIZE_NUMBER_FLOAT);
            if (isset($_POST['urlExter'])) {
                $item->url = filter_var($_POST['urlExter'], FILTER_SANITIZE_URL);
            }
            $item->liste_id = 0;
            $item->modifToken = bin2hex(openssl_random_pseudo_bytes(32));
            $item->save();
            $it = $item->id;
            $_SESSION['token'][] = $item->modifToken;

        } else {
            setcookie("Error", "Il y a une erreur dans le formulaire", time() + 10);
            $slim->redirect($slim->urlFor("creaItem"), 302);
        }
        $req = $slim->request;
        $url = $req->getRootUri() . "/item/$it";
        $slim->redirect($url, 302);

    }
}

