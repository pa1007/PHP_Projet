<?php

namespace mywishlist\controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\exception\AuthException;
use mywishlist\model\Authentication;
use mywishlist\model\Liste;
use mywishlist\model\User;
use mywishlist\vue\VueCompte;
use Slim\Slim;

class CompteController {

    public function compteCrea() {
        if (!$this->isConnected()) {
            $msg = "";
            if (isset($_COOKIE['Error'])) {
                $msg = $_COOKIE['Error'];
                setcookie("Error", "", 1);
            }
            $vueCompte = new VueCompte($msg);
            $vueCompte->render(VueCompte::CREA);
        } else {
            $slim = Slim::getInstance();
            $slim->redirect($slim->urlFor("compteco"), 301);
        }
    }

    private function isConnected() {
        return isset($_SESSION['id']);
    }

    public function postCompteCrea() {
        $slim = Slim::getInstance();
        if (!$this->isConnected()) {
            $nom = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_SPECIAL_CHARS);
            $log = filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS);
            $pass = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            $passC = filter_var($_POST['passwordConf'], FILTER_SANITIZE_SPECIAL_CHARS);
            $email = $_POST['mail'];
            if ($pass === $passC) {
                try {
                    Authentication::createUser($log, $pass, $nom, $prenom, $email);
                    $slim->redirect($slim->urlFor("compteco"), 301);
                } catch (AuthException $e) {
                    setcookie("Error", $e->getMessage(), time() + 10);
                    $slim->redirect($slim->urlFor("createcompte"), 302);
                }
            } else {
                setcookie("Error", "Les mots de passe ne correspondent pas ", time() + 10);
                $slim->redirect($slim->urlFor("createcompte"), 302);
            }
        } else {
            $slim->redirect($slim->urlFor("compteco"), 301);
        }
    }

    public function formConn() {
        if (!$this->isConnected()) {
            $msg = "";
            if (isset($_COOKIE['Error'])) {
                $msg = $_COOKIE['Error'];
                setcookie("Error", "", 1);
            }
            $v = new VueCompte($msg);
            $v->render(VueCompte::LOGIN);
        } else {
            $slim = Slim::getInstance();
            $slim->redirect($slim->urlFor("compteco"), 301);
        }
    }

    public function auth() {
        $slim = Slim::getInstance();
        if (!$this->isConnected()) {
            $log = filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS);
            $pass = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            try {
                Authentication::authenticate($log, $pass);
                $slim->redirect($slim->urlFor("compteco"), 301);
            } catch (AuthException $e) {
                setcookie("Error", $e->getMessage(), time() + 10);
                $slim->redirect($slim->urlFor("connect"), 302);
            }
        }
    }

    public function connected() {
        $slim = Slim::getInstance();
        if ($this->isConnected()) {
            $msg = "";
            if (isset($_COOKIE['Error'])) {
                $msg = $_COOKIE['Error'];
                setcookie("Error", "", 1);
            }
            $vueCompte = new VueCompte($msg);
            $page = isset($_GET['page']) ? $_GET['page'] : "";
            switch ($page) {
                case "listes" :
                    $vueCompte->listes = $this->genreateListes();
                    $vueCompte->render(VueCompte::AUFSEELISTES);
                    break;
                case "modif":
                    $vueCompte->user = User::find($_SESSION['id']['uid']);
                    $vueCompte->render(VueCompte::AUFMODIFCOMPTE);
                    break;
                default:
                    $vueCompte->reserv = $this->generateReserv($slim);
                    $vueCompte->render(VueCompte::NORMAL);
                    break;
            }
        } else {
            $slim->redirect($slim->urlFor("connect"), 302);
        }
    }

    private function genreateListes() {
        $slim = Slim::getInstance();
        try {
            $u = User::where("uid", '=', $_SESSION['id']['uid'])->firstOrFail();
            $listes = $u->listes;
            $request = $slim->request;
            $url = $request->getRootUri() . "/modif/liste/";
            $c = 0;
            $res = "";
            foreach ($listes as $liste) {
                if ($c === 3) {
                    $res .= "</div><div class='row'>";
                    $c = 0;
                }
                $res .= "  <div class=\"card mb-4\">
    <div class=\"card-body\">
      <h4 class=\"card-title\">$liste->titre</h4>
      <p class=\"card-text\">$liste->description</p>
      <p class=\"card-text\">$liste->expiration</p>
      <a class=\"btn btn-light-blue btn-md\" href='$url$liste->modifToken'>Modifier</a>
    </div>
  </div>";
                $c++;
            }
            return $res;
        } catch (ModelNotFoundException $e) {
            $slim->redirect($slim->urlFor("Error"), 301);
        }
        return "ERROR";
    }

    private function generateReserv($slim) {

        $u = User::find($_SESSION['id']['uid']);
        $b = 0;
        $html = "<h5>Vous avez réservé : </h5><ul>";
        if ($u !== null) {
            $base = $slim->request->getRootUri() . '/liste/';
            $reservation = $u->reservation;
            foreach ($reservation as $res) {
                $it = $res->Item;
                $liste = $it->Liste->token;
                $url = $base . $liste . "/item/$it->id";
                $html .= "<li> <a href='$url'><u>$it->nom</u></a> pour le prix de $it->tarif € </li>";
                $b++;
            }
            if ($b === 0) {
                $html .= "<li> Vous n'avez encore rien réservé </li>";
            }
            return $html . "</ul>";

        } else {
            $slim->redirect($slim->urlFor("Error"), 301);
        }
        return "";
    }

    public function addToken() {
        $slim = Slim::getInstance();
        if ($this->isConnected()) {
            $t = filter_var($_POST['modifTokAdd'], FILTER_SANITIZE_SPECIAL_CHARS);
            try {
                $l = Liste::where('modifToken', '=', $t)->firstOrFail();
                $uid = $_SESSION['id']['uid'];
                if ($l->user_id === $uid) {
                    setcookie("Error", "Inpossible d'ajouter 2 fois une liste", time() + 10);
                } elseif ($l->user_id !== null) {
                    setcookie("Error", "Inpossible d'ajouter une liste déjà ajoutée à un autre utilisateur", time() + 10);
                } else {
                    $l->user_id = $uid;
                    $l->save();
                }
            } catch (ModelNotFoundException $e) {
                setcookie("Error", "Liste non trouvée", time() + 10);
            }
        } else {
            $slim->redirect($slim->urlFor("Error"), 301);
        }
        $slim->redirect($slim->request->getRootUri() . "/connected?page=listes", 302);
    }

    function modifCompte() {
        $slim = Slim::getInstance();
        $url = $slim->request->getRootUri();
        if ($this->isConnected()) {
            $nom = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_SPECIAL_CHARS);
            $pass = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            $passC = filter_var($_POST['passwordConf'], FILTER_SANITIZE_SPECIAL_CHARS);
            $email = $_POST['mail'];
            try {
                $logout = false;
                $u = User::where("uid", '=', $_SESSION['id']['uid'])->firstOrFail();
                $u->nom = $nom;
                $u->prenom = $prenom;
                $u->mail = $email;
                if ($pass !== "" && !password_verify($pass, $u->password)) {
                    if ($pass === $passC) {
                        $u->password = password_hash($pass, PASSWORD_DEFAULT);
                        $logout = true;
                    } else {
                        setcookie("Error", "Les mots de passe ne correspondent pas", time() + 10);
                        $slim->redirect($url . "/connected/?page=modif");
                    }
                }
                $u->save();
                if ($logout) {
                    $this->logout();
                }
            } catch (ModelNotFoundException $e) {
                $slim->redirect($slim->urlFor("Error"), 301);
            }

        } else {
            $slim->redirect($slim->urlFor("Error"), 301);
        }
        $slim->redirect($url . "/connected");
    }

    public function logout() {
        unset($_SESSION['id']);
        $slim = Slim::getInstance();
        $slim->redirect($slim->urlFor('Menu'));
    }

    public function deleteCompte() {
        $slim = Slim::getInstance();
        $url = $slim->request->getRootUri();
        if ($this->isConnected()) {
            $uid = $_SESSION['id']['uid'];
            $listesUser = Liste::where('user_id', '=', $uid)->get();
            foreach ($listesUser as $liste) {
                foreach ($liste->Item as $item) {
                    $img1 = $item->img;
                    if (!filter_var($img1, FILTER_VALIDATE_URL)) {
                        $img = $url . 'img/' . $img1;
                        if (file_exists($img)) {
                            unset($img);
                        }
                    }
                    $item->delete();
                }
                $liste->delete();
            }
            User::find($uid)->delete();
            $this->logout();

        } else {
            $slim->redirect($slim->urlFor("Error"), 301);
        }
    }


}