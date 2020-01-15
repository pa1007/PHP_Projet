<?php


namespace mywishlist\controller;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\model\Commentaire;
use mywishlist\model\Liste;
use mywishlist\model\Partage;
use mywishlist\model\User;
use mywishlist\vue\VueParticipant;
use Slim\Slim;

class ListController {
    public function __construct() {

    }

    public function getList($no) {
        $list = Liste::where('token', '=', $no)->first();
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
            $list->token = bin2hex(openssl_random_pseudo_bytes(32));
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
        $list = Liste::where('token', '=', $id)->first();
        $com->message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        $com->nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
        $com->liste_id = $list->no;
        $com->save();
        $url = $req->getRootUri() . "/liste/$id";
        $slim->redirect($url, 302);
    }

    public function publicListe() {
        $list = Liste::where('visible', '=', 1)->get();
        $v = new VueParticipant($list);
        $v->render(VueParticipant::PUBLIC);
    }

    public function afficherTokenPartage($id) {
        $slim = Slim::getInstance();
        $req = $slim->request;
        try {
            $part = Partage::where('tokenpartage', '=', $id)->firstOrFail();
            $liste = $part->liste;
            $v = new VueParticipant($liste);
            $v->render(VueParticipant::LISTE);


        } catch (ModelNotFoundException $e) {
            $slim->redirect($slim->urlFor('Error'));
        }
    }

    public function verifListePublique() {
        $u = User::all();
        $slim = Slim::getInstance();
        $html = "<div>";
        $startURL = $slim->request->getRootUri() . "/liste";
        foreach ($u as $user) {
            $listes = $user->listes;
            $dis = 0;
            $userLis = "";
            foreach ($listes as $liste) {
                if ($liste->visible == 1 && !$liste->hasExpire()) {
                    $dis = 1;
                    $userLis .= <<<END
<div class="card text-center"> 
  <div class="card-header">
    <h3>$liste->titre</h3>
  </div>
  <div class="card-body">
    <h5 class="card-title">$liste->description</h5>

    <a href="$startURL/$liste->token" class="btn btn-primary">Voir la liste</a>
  </div>
  <div class="card-footer text-muted">
    Expire le $liste->expiration
  </div>
</div><br>
END;
                }
            }
            if ($dis > 0) {
                $html .= <<<END
<div class="card">
<div class="card-header">
<h3> Fait par : $user->nom $user->prenom</h3>
</div>
<div class="card-body">
$userLis
</div>
</div> <br>
END;

            }

        }
        $html .= "</div>";
        $v = new VueParticipant($html);
        $v->render(VueParticipant::LIST_CREATEUR_PUBLICS);
    }
}