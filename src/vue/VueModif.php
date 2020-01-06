<?php


namespace mywishlist\vue;


use mywishlist\model\Item;
use mywishlist\model\Liste;
use Slim\Slim;

class VueModif extends Vue {

    const LISTE = 1;
    const ITEM = 2;
    const ITEM_IMAGE_CHANGE = 3;

    private $token;

    /**
     * VueModif constructor.
     * @param $token
     */
    public function __construct($token) { $this->token = $token; }


    public function render($sel) {
        $head = parent::renduTitre();
        $menu = parent::renduMenu();
        $foot = parent::rendufooter();
        $cont = "";
        if (isset($_COOKIE['Error'])) {
            $cont = $_COOKIE['Error'];
            $cont = <<<END
    <div class="alert alert-danger" role="alert">
     $cont 
    </div>
END;
        }

        switch ($sel) {
            case self::ITEM:
                $cont .= $this->renderItem();
                break;
            case self::LISTE:
                $cont .= $this->renderListe();
                break;
            case self::ITEM_IMAGE_CHANGE:
                $cont .= $this->renderItemChangeImage();
                break;
            default :
                $slim = Slim::getInstance();
                $slim->redirect($slim->urlFor("Error"), 301);
                break;
        }
        $html = <<<END
$head
$menu
<div class="container">
 $cont
 </div>
$foot
END;
        echo $html;
    }

    private function renderItem() {
        $u = Item::where('modifToken', "=", $this->token)->first();
        $slim = Slim::getInstance();
        $request = $slim->request;
        $url = $request->getPath();
        $sel = $this->generateSel($u->liste_id);
        return <<<END
<form class="form-horizontal" method="post">
<fieldset>
<legend>Modification d'objet</legend>
<div class="form-group">
  <label class="col-md-4 control-label" for="name">Nom de l'item</label>  
  <div class="col-md-4">
  <input id="name" name="name" type="text" placeholder="" class="form-control input-md" required="" value="$u->nom">
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="Image">Image</label>  
  <div class="col-md-4">
 <a class="btn btn-block btn-info" href="$url/changeImage">Modification Images</a>
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="description">Description</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="description" name="description">$u->descr</textarea>
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="tarif">Tarif</label>  
  <div class="col-md-4">
  <input id="tarif" name="tarif" type="number" step="any" placeholder="" class="form-control input-md" required="" value="$u->tarif">
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="urlEx">Url Externe</label>  
  <div class="col-md-4">
  <input id="urlEx" name="urlEx" type="text" placeholder="Url Externe" class="form-control input-md" value="$u->url">
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="listes">Listes</label>
  <div class="col-md-4">
    <select id="listes" name="listes" class="form-control">
    $sel
    </select>
  </div>
</div>
<div class="form-group">
  <div class="col-md-4">
    <input id="submit" name="submit" class="btn btn-success" type="submit"/>
  </div>
</div>
</fieldset>
</form>
<form method="post" class="form-horizontal">
<input type="hidden" name="_METHOD" value="DELETE"/> <!--https://docs.slimframework.com/routing/delete/ -->
<div class="form-group">
  <div class="col-md-4">
     <input class="btn btn-danger" role="button" value="Supprimer" type="submit"/>
  </div>    
</div>
</form>
END;

    }

    private function generateSel($idSel) {
        $item = Liste::all();
        if ($idSel === 0) {
            $ret = "<option value='0' selected>None</option>";
        } else {
            $ret = "<option value='0'>None</option>";
        }
        foreach ($item as $value) {
            if ($value->no === $idSel) {
                $ret .= " <option value=\"$value->no\" selected>$value->titre / $value->no</option>";
            } else {
                $ret .= " <option value=\"$value->no\">$value->titre / $value->no</option>";
            }
        }
        return $ret;
    }

    private function renderListe() {
        $u = Liste::where('modifToken', "=", $this->token)->first();
        $visPriv = !$u->visible ? "checked=\"checked\"" : "";
        $visPubl = $u->visible ? "checked=\"checked\"" : "";
        return <<<END
<form class="form-horizontal" method="post">
<fieldset>

<!-- Form Name -->
<legend>Modifie les informations générales de ta liste !</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="titreListe">Titre</label>  
  <div class="col-md-4">
  <input id="titreListe" name="titreListe" type="text" placeholder="" class="form-control input-md" value="$u->titre">
    
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="descriptionListe">Description</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="descriptionListe" name="descriptionListe">$u->description</textarea>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="dateEcheanceListe">Date d'échéance</label>  
  <div class="col-md-4">
  <input id="dateEcheanceListe" name="dateEcheanceListe" type="date" placeholder="" class="form-control input-md" value="$u->expiration">
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="Visibilite">Visibilité</label>
  <div class="col-md-4">
  <div class="radio">
    <label for="Visibilite-0">
      <input type="radio" name="Visibilite" id="Visibilite-0" value="1" $visPriv>
      Privé
    </label>
	</div>
  <div class="radio">
    <label for="Visibilite-1">
      <input type="radio" name="Visibilite" id="Visibilite-1" value="2" $visPubl>
      Publique
    </label>
	</div>
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="butValider"></label>
  <div class="col-md-4">
    <button id="butValider" name="butValider" class="btn btn-primary">Valider</button>
  </div>
</div>

</fieldset>
</form>

END;

    }

    private function renderItemChangeImage() {
        $u = Item::where('modifToken', "=", $this->token)->first();
        $more = "";
        $slim = Slim::getInstance();
        $request = $slim->request;
        $url = $request->getPath();
        $images = $u->images;
        if (!$images->isEmpty()) {
            $more .= "<span class='d-block small alert-info text-center '> Cliquez sur l'image pour la supprimer</span>";
            $more .= "<label>Images associées : </label>";
            foreach ($images as $image) {
                $imageL = $this->getImage($image->img, $request);
                $more .= <<<END
<div class='d-inline'>
<form action="$url/sup/$image->id_image" method="post" class="d-inline">
   <input type="hidden" name="_METHOD" value="DELETE" hidden/> <!--https://docs.slimframework.com/routing/delete/ -->
        <button type="submit" class="btn btn-danger d-inline"><img src="$imageL" width="100" height="100" alt="image$image->id_image" class="d-inline"/></input>
      </form>  
</div>
END;
            }

        }
        return <<<END
<form method="post" enctype="multipart/form-data">
<legend>Ajoutez une image</legend>
<div class="form-group">
  <label class="col-md-4 control-label" for="Ajout image">Choisissez un fichier</label>
  <div class="col-md-4">
    <input id="Ajout image" name="image" class="input-file" type="file">
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="valider" name="submit" value="file" class="btn btn-primary">Valider</button>
  </div>
</div>
</form>
<form  method="post" enctype="multipart/form-data">
<div class="form-group">
  <label class="col-md-4 control-label" for="url">URL Externe d'explication</label>  
  <div class="col-md-4">
  <input id="urlExter" name="url" type="url" placeholder="URL" class="form-control input-md">
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="valider" name="submit" value="image" class="btn btn-primary">Valider</button>
  </div>
</div>
</fieldset>
</form>
<div class="d-inline">
   $more 
</div>
END;

    }

    private function getImage($img, $req) {
        if (!filter_var($img, FILTER_VALIDATE_URL)) {
            $img = $req->getRootUri() . "/img/$img";
        }
        return $img;
    }
}