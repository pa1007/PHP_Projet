<?php

namespace mywishlist\controller;

use mywishlist\model\Cagnotte;
use mywishlist\model\Item;
use mywishlist\model\Reservation;
use mywishlist\vue\VueParticipant;
use Slim\Slim;

class ItemController {

    public function __construct() {

    }

    public function getItem($id, $lToken) {
        $slim = Slim::getInstance();
        $item = Item::where('id', '=', $id)->first();
        if ($item->Liste->token === $lToken) {
            $reserv = $item->Reservation;
            if (is_null($reserv)) {
                $name = "";
                if (isset($_SESSION['id'])) {
                    $name = $_SESSION['id']['login'];
                }
                $res = <<<END
            <form class="" method="POST">
              <fieldset>
                <legend>Reservation</legend>
                  <div class="form-row">
                  <div class="form-group col-md-8">
                    <label class="control-label" for="nomUtilisateur">Utilisateur</label>  
                      <div class="">
                      <input name="nomUtilisateur" class="form-control input-md" id="nomUtilisateur" type="text" placeholder="nom" value="$name">
                      </div>
                  </div>
                <div class="form-group col-md-8">
            <label class=" control-label" for="message">Message</label>
            <div class="">                     
             <textarea name="message" class="form-control" id="message" placeholder="Votre message"></textarea>
             </div>
            </div>
</div>
<div class="form-row d-inline">
<input name="submit" type="submit" class="btn btn-success" value="Reserver">
END;
                if (ModifController::testToken($item->Liste->modifToken)) {
                    $res .= "<input role=\"button\" name=\"submit\" type=\"submit\" class=\"btn btn-info\" value=\"Créée une cagnotte !\">";
                }
                $res .= "</div></fieldset></form>";
            } elseif ($reserv->type == "CAGNOTTE") {
                $cagnotte = $item->cagnotte;
                $vGet = $cagnotte->valeur;
                $act = $slim->request->getRootUri() . $slim->request->getResourceUri() . "/cadd";
                $w = ($vGet / $item->tarif) * 100;
                $res = <<<END
            <div class="progress">
  <div class="progress-bar" role="progressbar" style="width:$w%;" aria-valuenow="$vGet" aria-valuemin="0" aria-valuemax="$item->tarif">$vGet</div>
    </div>
END;
                if ($w != 100) {
                    $res .= <<<END
<form method="post" action="$act">
<br>
<div class="form-row d-inline">
 <label for="montant">le montant que vous voulez ajouter : &emsp;</label>
 <input type="number" placeholder="montant" name="Montant" step="any"></div>
 <input type="submit" class="btn btn-danger ml-5">
</form>
END;
                } else {
                    $res .= "<p>Cagnotte remplie !</p>";
                }
            } else {
                $res = "<p>Item reservé</p>";
            }
            $v = new VueParticipant(["item" => $item, "reservation" => $res]);
            $v->render(VueParticipant::ITEM);
        } else {
            $slim->redirect($slim->urlFor("Error"));
        }
    }

    public function reserverItem($id, $lToken) {
        $item = Item::where('id', '=', $id)->first();
        $res = Reservation::where('idItem', '=', $id)->first();
        if ($item->Liste->token === $lToken) {
            if (is_null($res)) {
                if (isset($_POST['nomUtilisateur'])) {
                    $uti = $_POST['nomUtilisateur'];
                    $message = $_POST['message'];
                    $r = new Reservation();
                    $r->idItem = $id;
                    $r->nomUtilisateur = filter_var($uti, FILTER_SANITIZE_SPECIAL_CHARS);
                    $r->message = filter_var($message, FILTER_SANITIZE_SPECIAL_CHARS);
                    if (isset($_POST['submit']) && $_POST['submit'] === "Créée une cagnotte !") {
                        $r->type = "CAGNOTTE";
                        $c = new Cagnotte();
                        $c->id_item = $id;
                        $c->valeur = 0;
                        $c->save();
                    }
                    if (isset($_SESSION['id'])) {
                        $r->userID = $_SESSION['id']['uid'];
                    }
                    $r->save();
                }
            }
            $slim = Slim::getInstance();
            $req = $slim->request;
            $url = $req->getRootUri() . "/liste/$lToken/item/$item->id";
            $slim->redirect($url, 302);
        } else {
            $slim = Slim::getInstance();
            $slim->redirect($slim->urlFor("Error"));
        }

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
        if (isset($_POST['nom']) && $_POST["nom"] !== "" && isset($_POST["Description"]) && $_POST["Description"] !== "" && isset($_POST["number"])
            && $_POST["number"] !== "" && isset($_POST['singlebutton'])) {
            $item = new Item();
            $item->nom = filter_var($_POST['nom'], FILTER_SANITIZE_SPECIAL_CHARS);
            $item->descr = filter_var($_POST['Description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $item->tarif = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
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

    public function addCagnotte($id, $lToken) {
        $item = Item::where('id', '=', $id)->first();
        $res = Reservation::where('idItem', '=', $id)->first();
        $slim = Slim::getInstance();
        $cagnotte = $item->cagnotte;
        if ($item->Liste->token === $lToken && $cagnotte !== null && isset($_POST['Montant'])) {
            $montant = filter_input(INPUT_POST, 'Montant', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if ($cagnotte->valeur + $montant <= $item->tarif) {
                $cagnotte->valeur = $cagnotte->valeur + $montant;
                $cagnotte->save();
            }
            $url = $slim->request->getRootUri() . "/liste/$lToken/item/$id";
            $slim->redirect($url, 302);
        } else {
            $slim->redirect($slim->urlFor("Error"));
        }
    }
}

