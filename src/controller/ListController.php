<?php


namespace mywishlist\controller;


use mywishlist\model\Item;
use mywishlist\model\Liste;
use mywishlist\vue\VueParticipant;
use Slim\Slim;

class ListController
{

    private $token;

    public function __construct($tok){
    $this->token=$tok;
    }

    public function getList( $no )
    {
        $list = Liste::where('no', '=', $no)->first();
        $v = new VueParticipant($list);
        $v->render(VueParticipant::LISTE);
    }

    private function testToken() {
        if (isset($_SESSION['token'])) {
            foreach ($_SESSION['token'] as $item) {
                if ($item === $this->token) {
                    return true;
                }
            }
        }
        return false;
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
        $it = 0;
        if (isset($_POST['titre']) && $_POST["titre"] !== "" && isset($_POST["Description"]) && $_POST["Description"] !== "" && isset($_POST["datexp"])
            && $_POST["datexp"] !== "" && isset($_POST['singlebutton']) && $_POST['singlebutton'] === "Submit") {
            $list = new Liste();
            $list->titre = filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $list->descr = filter_var($_POST['Description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $list->tarif = filter_var($_POST['datexp'], FILTER_SANITIZE_STRING);
            if (isset($_POST['urlExter'])) {
                $list->url = filter_var($_POST['urlExter'], FILTER_SANITIZE_URL);
            }
            $list->liste_id = 0;
            $list->modifToken = bin2hex(openssl_random_pseudo_bytes(32));
            $list->save();
            $it = $list->id;
            $_SESSION['token'][] = $list->modifToken;

        } else {
            setcookie("Error", "Il y a une erreur dans le formulaire", time() + 10);
            $slim->redirect($slim->urlFor("creaListe"), 302);
        }
        $req = $slim->request;
        $url = $req->getRootUri() . "/liste/$it";
        $slim->redirect($url, 302);

    }

    public function creerListe(){
        $slim = Slim::getInstance();
        $req = $slim->request;
        $liste = Liste::where("modifToken", "=", $this->token)->first();
        $titre  = $_POST['titre'];
        $description = $_POST['Description'];
        $datexp = $_POST['datexp'];
        $butP = $_POST['submit'];
        if ($titre!== "" && $description !== "" && $datexp !== "" && $butP === 'submit') {
            $liste->titre = filter_var($titre, FILTER_SANITIZE_SPECIAL_CHARS);
            $liste->descr = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
            $liste->datex = filter_var($datexp, FILTER_SANITIZE_NUMBER_FLOAT);
            $liste->url = filter_var($_POST['urlEx'], FILTER_SANITIZE_URL);
            $liste->save();
            $url = $req->getRootUri() . "/item/$liste->id";
            $slim->redirect($url, 302);
        } else {
            setcookie("Error", "Il y a une erreur dans le formulaire", time() + 10);
            $slim->redirect($req->getResourceUri(), 302);
        }


    }

}