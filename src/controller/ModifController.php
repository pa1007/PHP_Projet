<?php


namespace mywishlist\controller;


use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\model\Images;
use mywishlist\model\Item;
use mywishlist\model\Liste;
use mywishlist\model\Partage;
use mywishlist\model\User;
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
                if ($itemT !== null && self::testToken($this->token)) {
                    $vueModif = new VueModif($this->token);
                    $vueModif->render(VueModif::LISTE);
                } else {
                    $slim->redirect($slim->urlFor('Error'), 301);
                }
                break;
            case "item":
                $listeT = Item::where("modifToken", "=", $this->token)->first();
                if ($listeT !== null && self::testToken($this->token)) {
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

    public static function testToken($token) {
        if (isset($_SESSION['token'])) {
            foreach ($_SESSION['token'] as $item) {
                if ($item === $token) {
                    return true;
                }
            }
        }
        if (isset($_SESSION['id'])) {
            try {
                $id = User::where('uid', '=', $_SESSION['id']['uid'])->firstOrFail();
                foreach ($id->listes as $item) {
                    if ($item->modifToken === $token) {
                        return true;
                    }
                }
            } catch (ModelNotFoundException $e) {
                return false;
            }
        }
        return false;
    }


    public function modify() {
        $slim = Slim::getInstance();
        try {
            switch ($this->type) {
                case "liste":
                    if (self::testToken($this->token)) {
                        $this->modifyListe();
                    } else {
                        $slim->redirect($slim->urlFor('Error'), 301);
                    }
                    break;
                case "item":
                    if (self::testToken($this->token)) {
                        $this->modifyItem();
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

    private function modifyListe() {
        $slim = Slim::getInstance();
        $req = $slim->request;
        if (self::testToken($this->token)) {
            $liste = Liste::where("modifToken", "=", $this->token)->first();
            $titre = $_POST['titreListe'];
            $description = $_POST['descriptionListe'];
            $dateEch = $_POST['dateEcheanceListe'];
            $visCh = $_POST['Visibilite'];
            if ($visCh === "2") {
                $vis = 1;
            } else {
                $vis = 0;
            }
            if ($titre !== "" && $description !== "" && $dateEch !== "") {
                $liste->titre = filter_var($titre, FILTER_SANITIZE_SPECIAL_CHARS);
                $liste->description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
                $liste->expiration = filter_var($dateEch, FILTER_SANITIZE_SPECIAL_CHARS);
                $liste->visible = $vis;
                $liste->save();
                $url = $req->getRootUri() . "/liste/$liste->token";
                $slim->redirect($url, 302);
            } else {
                setCookie("Error", "Il y a une erreur dans le formulaire", time() + 10);
                $slim->redirect($req->getRootUri() . $req->getResourceUri(), 302);
            }
        }

    }

    private function modifyItem() {
        $slim = Slim::getInstance();
        $req = $slim->request;
        $item = Item::where("modifToken", "=", $this->token)->first();
        $nomP = $_POST['name'];
        $descriptionP = $_POST['description'];
        $numberP = $_POST['tarif'];
        $listP = $_POST['listes'];
        $butP = $_POST['submit'];
        if ($nomP !== "" && $descriptionP !== "" && $numberP !== "" && $listP !== "") {
            $item->nom = filter_var($nomP, FILTER_SANITIZE_SPECIAL_CHARS);
            $item->descr = filter_var($descriptionP, FILTER_SANITIZE_SPECIAL_CHARS);
            $item->tarif = filter_input(INPUT_POST, 'tarif', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $item->liste_id = filter_var($listP, FILTER_SANITIZE_NUMBER_INT);
            $item->url = filter_var($_POST['urlEx'], FILTER_SANITIZE_URL);
            $item->save();

            try {
                $token = $item->Liste->token;
            } catch (Exception $e) {
                setcookie("Error", "Pour voir un item il lui faut une liste", time() + 10);
                $url = $req->getRootUri() . "/modif/$this->type/$this->token";
                $slim->redirect($url, 302);
            }
            $url = $req->getRootUri() . "/liste/$token/item/$item->id";
            $slim->redirect($url, 302);
        } else {
            setcookie("Error", "Il y a une erreur dans le formulaire", time() + 10);
            $url = $req->getRootUri() . "/modif/$this->type/$this->token";
            $slim->redirect($url, 302);
        }
    }

    public function delete() {
        $slim = Slim::getInstance();
        $req = $slim->request;
        try {
            switch ($this->type) {
                case "liste":
                    if (self::testToken($this->token)) {
                        $list = Liste::where("modifToken", "=", $this->token)->firstOrFail();
                        $list->delete();
                        $slim->redirect($slim->urlFor('Menu'), 302);
                    } else {
                        $slim->redirect($slim->urlFor('Error'), 301);
                    }
                    break;
                case "item":
                    if (self::testToken($this->token)) {
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

    public function changeImageForm() {
        $slim = Slim::getInstance();
        $req = $slim->request;
        if (self::testToken($this->token)) {
            $vueModif = new VueModif($this->token);
            $vueModif->render(VueModif::ITEM_IMAGE_CHANGE);
        } else {
            $slim->redirect($slim->urlFor('Error'), 301);
        }
    }

    public function modifyImage() {
        $slim = Slim::getInstance();
        $req = $slim->request;
        if (self::testToken($this->token)) {
            $u = Item::where('modifToken', '=', $this->token)->first();
            $im = new Images();

            $im->id_item = $u->id;

            $submit = $_POST['submit'];
            if ($submit === "file") {
                $target_dir = "img/";
                $basename = basename($_FILES["image"]["name"]);
                $target_file = $target_dir . $basename;
                if (!file_exists($target_file)) {
                    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                }
                $im->img = $basename;
                unset($_FILES['image']);
            } elseif ($submit === "image") {
                $urlImageP = $_POST['url'];
                $im->img = filter_var($urlImageP, FILTER_SANITIZE_URL);
            } else {
                $slim->redirect($slim->urlFor('Error'), 301);
            }
            $im->save();
            $slim->redirect($req->getRootUri() . "/modif/item/$this->token/changeImage");
        } else {
            $slim->redirect($slim->urlFor('Error'), 301);
        }
    }

    public function deleleImage($id) {
        $slim = Slim::getInstance();
        $req = $slim->request;
        if (self::testToken($this->token)) {
            $item = Item::where("modifToken", "=", $this->token)->first();
            foreach ($item->images as $image) {
                if ($image->id_image == $id) {
                    if (!filter_var($image->img, FILTER_VALIDATE_URL)) {
                        unlink("img/$image->img");
                    }
                    $image->delete();
                    $slim->redirect($req->getRootUri() . "/modif/item/$this->token/changeImage");
                }
            }
        }
        $slim->redirect($slim->urlFor('Error'), 301);
    }

    public function partagerListe() {
        $slim = Slim::getInstance();
        $req = $slim->request;
        try {
            $liste = Liste::where("modifToken", "=", $this->token)->firstOrFail();
            $partage = new Partage();
            $partage->idliste = $liste->no;
            $partage->tokenpartage = bin2hex(openssl_random_pseudo_bytes(32));
            $partage->save();

            $slim->redirect($req->getRootUri() . "/modif/liste/$this->token");

        } catch (ModelNotFoundException $e) {
            $slim->redirect($slim->urlFor('Error'), 301);
        }
    }
}