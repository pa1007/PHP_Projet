<?php


namespace mywishlist\controller;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\model\Item;
use mywishlist\model\Liste;
use mywishlist\vue\VueModif;
use Slim\Slim;

class ModifController {

    private $type;
    private $token;

    /**
     * ModifController constructor.
     * @param $type
     * @param $token
     */
    public function __construct($type, $token) {
        $this->type = $type;
        $this->token = $token;
    }

    public function process() {
        $slim = Slim::getInstance();
        switch ($this->type) {
            case "liste":
                $itemT = Liste::where("modifToken", "=", $this->token)->first();
                if ($itemT !== null && $this->testToken()) {
                    $vueModif = new VueModif($this->token);
                    $vueModif->render(VueModif::LISTE);
                } else {
                    $slim->redirect($slim->urlFor('Error'), 301);
                }
                break;
            case "item":
                $listeT = Item::where("modifToken", "=", $this->token)->first();
                if ($listeT !== null && $this->testToken()) {
                    $vueModif = new VueModif($this->token);
                    $vueModif->render(VueModif::ITEM);
                } else {
                    $slim->redirect($slim->urlFor('Error'), 301);
                }
                break;
            default:
                $slim->redirect($slim->urlFor('Error'), 301);
                break;
        }
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


    public function modifyItem() {
        $slim = Slim::getInstance();
        try {
            switch ($this->type) {
                case "liste":
                    if ($this->testToken()) {
                        $this->liste();
                    } else {
                        $slim->redirect($slim->urlFor('Error'), 301);
                    }
                    break;
                case "item":
                    if ($this->testToken()) {
                        $this->item();
                    } else {
                        $slim->redirect($slim->urlFor('Error'), 301);
                    }
                    break;
                default:
                    $slim->redirect($slim->urlFor('Error'), 301);
                    break;
            }
        } catch (ModelNotFoundException $e) {
            $slim->redirect($slim->urlFor('Error'), 301);
        }
    }

    private function liste() {
        $slim = Slim::getInstance();
        $req = $slim->request;
    }

    private function item() {
        $item = Item::where("modifToken", "=", $this->token)->firstOrFail();
        $slim = Slim::getInstance();
        $req = $slim->request;
        $nomP = $_POST['nom'];
        $descriptionP = $_POST['Description'];
        $numberP = $_POST['number'];
        $listP = $_POST['listes'];
        $butP = $_POST['submit'];
        if ($nomP !== "" && $descriptionP !== "" && $numberP !== "" && $listP !== "" && $butP === 'submit') {
            $item->nom = filter_var($nomP, FILTER_SANITIZE_SPECIAL_CHARS);
            $item->descr = filter_var($descriptionP, FILTER_SANITIZE_SPECIAL_CHARS);
            $item->tarif = filter_var($numberP, FILTER_SANITIZE_NUMBER_FLOAT);
            $item->liste_id = filter_var($listP, FILTER_SANITIZE_NUMBER_INT);
            $item->img = filter_var($_POST['Image'], FILTER_SANITIZE_URL);
            $item->url = filter_var($_POST['urlEx'], FILTER_SANITIZE_URL);
            $item->save();
            $url = $req->getRootUri() . "/item/$item->id";
            $slim->redirect($url, 302);
        } else {
            setcookie("Error", "Il y a une erreur dans le formulaire", time() + 10);
            $slim->redirect($req->getResourceUri(), 302);
        }
    }

    public function delete() {
        $slim = Slim::getInstance();
        $req = $slim->request;
        try {
            switch ($this->type) {
                case "liste":
                    if ($this->testToken()) {
                        $list = Liste::where("modifToken", "=", $this->token)->firstOrFail();
                        $list->delete();
                        $slim->redirect($slim->urlFor('Menu'), 302);
                    } else {
                        $slim->redirect($slim->urlFor('Error'), 30);
                    }
                    break;
                case "item":
                    if ($this->testToken()) {
                        $item = Item::where("modifToken", "=", $this->token)->firstOrFail();
                        $item->delete();
                        $slim->redirect($slim->urlFor('Menu'), 302);
                    } else {
                        $slim->redirect($slim->urlFor('Error'), 301);
                    }
                    break;
                default:
                    $slim->redirect($slim->urlFor('Error'), 301);
                    break;
            }
        } catch (ModelNotFoundException $e) {
            $slim->redirect($slim->urlFor('Error'), 301);
        }

    }
}