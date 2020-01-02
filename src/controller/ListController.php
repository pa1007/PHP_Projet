<?php


namespace mywishlist\controller;


use mywishlist\model\Commentaire;
use mywishlist\model\Liste;
use mywishlist\vue\VueParticipant;
use Slim\Slim;

class ListController {
    public function __construct() {

    }

    public function getList($no) {
        $list = Liste::where('no', '=', $no)->first();
        $v = new VueParticipant($list);
        $v->render(VueParticipant::LISTE);
    }

    public function seeFormCrea() {
        $msg = "";
        if (isset($_COOKIE['Error'])) {
            $msg = $_COOKIE['Error'];
            setcookie("Error", "", 1);
        }
        $v = new VueParticipant($msg);
        $v->render(VueParticipant::LIST_CREA);
    }

    public function postCreaForm() {
        $slim = Slim::getInstance();
        if (isset($_POST['titre']) && $_POST["titre"] !== "" && isset($_POST["Description"]) && $_POST["Description"] !== "" && isset($_POST["date"])
            && $_POST["date"] !== "" && isset($_POST['valider'])) {
            $list = new Liste();
            $list->titre = filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $list->description = filter_var($_POST['Description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $list->expiration = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
            $list->modifToken = bin2hex(openssl_random_pseudo_bytes(32));
            if (isset($_SESSION['id'])) {
                $list->user_id = $_SESSION['id']['uid'];
            }
            $list->save();
            $_SESSION['token'][] = $list->modifToken;
            $req = $slim->request;
            $url = $req->getRootUri() . "/modif/liste/$list->modifToken";
            $slim->redirect($url, 302);
        } else {
            setcookie("Error", "Il y a une erreur dans le formulaire", time() + 10);
            $slim->redirect($slim->urlFor("creaListe"), 302);
        }


    }


    public function MessageAjoute($id) {
        $slim = Slim::getInstance();
        $req = $slim->request;
        $com = new Commentaire();
        $com->message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        $com->nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
        $com->liste_id = $id;
        $com->save();
        $lis = Liste::where('no', '=', $id)->first();
        $url = $req->getRootUri() . "/liste/$id";
        $slim->redirect($url, 302);
    }

}