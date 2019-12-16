<?php


namespace mywishlist\vue;


use mywishlist\model\Item;
use mywishlist\model\Liste;
use Slim\Slim;

class VueModif extends Vue {

    const LISTE = 1;
    const ITEM = 2;

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
  <input id="Image" name="Image" type="text" placeholder="Image" class="form-control input-md" value="$u->img">
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
     <input class="btn btn-danger" value="Supprimer" type="submit"/>
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

    private function renderListe(){
        $u = Liste::where('modifToken', "=", $this->token)->first();
        return <<<END
<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Modifie les informations générales de ta liste !</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="titreListe">Titre</label>  
  <div class="col-md-4">
  <input id="titreListe" name="titreListe" type="text" placeholder="" class="form-control input-md">
    
  </div>
</div>

<!-- Textarea -->
<div class="form-group">
  <label class="col-md-4 control-label" for="descriptionListe">Description</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="descriptionListe" name="descriptionListe"></textarea>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="dateEcheanceListe">Date d'échéance</label>  
  <div class="col-md-4">
  <input id="dateEcheanceListe" name="dateEcheanceListe" type="date" placeholder="" class="form-control input-md">
    
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
}