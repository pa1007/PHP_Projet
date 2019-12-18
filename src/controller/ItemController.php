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

    public function reserverItem($id) {
        $item = Item::where('id', '=', $id)->first();
        $res = Reservation::where('idItem', '=', $id)->first();
        if (is_null($res)) {
            if (isset($_POST['nomUtilisateur'])) {
                $r = new Reservation();
                $r->idItem = $id;
                $r->nomUtilisateur = filter_var($_POST['nomUtilisateur'], FILTER_SANITIZE_SPECIAL_CHARS);
                $r->save();
            }
        }
        $slim = Slim::getInstance();
        $req = $slim->request;
        $url = $req->getRootUri() . "/item/$item->id";
        $slim->redirect($url, 302);

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
            && $_POST["number"] !== "" && isset($_POST['singlebutton']) ) {
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
            $_SESSION['token'][] = $item->modifToken;
            $req = $slim->request;
            $url = $req->getRootUri() . "/modif/item/$item->modifToken";
            $slim->redirect($url, 302);

        } else {
            setcookie("Error", "Il y a une erreur dans le formulaire", time() + 10);
            $slim->redirect($slim->urlFor("creaItem"), 302);
        }
    }
}

