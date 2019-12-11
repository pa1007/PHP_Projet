<?php

namespace mywishlist\vue;

use Slim\Slim;

class VueParticipant extends Vue {

    const LISTE = 1;
    const ITEM = 2;
    const ITEM_CREA = 4;

    protected $tableau;

    public function __construct($tab) {
        $this->tableau = $tab;
    }

    public function render($sel) {
        $head = parent::renduTitre();
        $menu = parent::renduMenu();
        $foot = parent::rendufooter();

        switch ($sel) {
            case VueParticipant::LISTE :
                if ($this->tableau == null) {
                    $slim = Slim::getInstance();
                    $slim->redirect($slim->urlFor("Error"), 301);
                    return;
                }
                $content = $this->renderList();
                break;
            case VueParticipant::ITEM :
                if ($this->tableau == null) {
                    $slim = Slim::getInstance();
                    $slim->redirect($slim->urlFor("Error"), 301);
                    return;
                }
                $content = $this->renderItem();
                break;
            case VueParticipant::ITEM_CREA :
                $content = $this->renderCreatItem();
                break;
        }
        $html = <<<END
$head
$menu

<div class="container">
 $content
 </div>
$foot
END;
        echo $html;

    }

    private function renderList() {
        $tableau = $this->tableau;
        $items = $tableau->Item;
        $text = "<div><h4>$tableau->titre</h4>
      <div class=\"mb-4 \"> ";
        foreach ($items as $item) {
            $text .= $this->renderItemListe($item);
        }
        $text .= "</div> <h4>$tableau->description</h4> </div>";
        return $text;
    }

    private function renderItemListe($item) {
        $slim = Slim::getInstance();
        $req = $slim->request;
        $rootUri = $req->getRootUri() . "/item/$item->id";
        return <<<END
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <div class="my-0 font-weight-normal"><h1 class="card-title ">$item->nom</h1></div>
          </div>
          <div class="card-body d-inline-flex"><img class="border border-primary " src='../img/$item->img' height="250" width="250" style="" alt="">
            <div class="m-5">
            <br><br>
                <h3>$item->descr</h3>
                <div> $item->tarif €</div>
            <br>
            <a href="$rootUri" class="btn btn-primary btn-smal active" role="button" aria-pressed="false">Plus d'information</a>
            </div>
          </div>
          </div>
   
END;
    }

    private function renderItem() {
        $item = $this->tableau;
        $lis = $item->Liste;
        $all = "";
        if (!is_null($lis)) {
            $slim = Slim::getInstance();
            $req = $slim->request;
            $url = $req->getRootUri() . "/liste/$lis->no";
            $all = <<<END
            <div class='border border-dark'>
           <p style="transform: rotate(0);">$lis->titre / Liste numéro <a class='stretched-link' href='$url'> $lis->no</a></p>
           </div>
END;
        }
        return <<<END
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <div class="my-0 font-weight-normal"><h1 class="card-title ">$item->nom</h1></div>
          </div>
          <div class="card-body d-inline-flex"><img class="border border-primary " src='../img/$item->img' height="250" width="250" style="" alt="">
            <div class="m-5">
            <br><br>
                <h3>$item->descr</h3>
                <div> $item->tarif €</div>
            <br>
             $all
            </div>
          </div>
          </div>
   
END;
    }

    private function renderCreatItem() {
        $err = "";
        if ($this->tableau !== "") {
            $err = <<<END
<div class="alert alert-danger" role="alert">
  $this->tableau
</div>
END;
        }


        return <<<END
$err
<form class="form-horizontal" method="post" >
<fieldset>
<legend>Créez votre item !</legend>
<div class="form-group">
  <label class="col-md-4 control-label" for="nom">Nom</label>  
  <div class="col-md-4">
  <input id="nom" name="nom" type="text" placeholder="Nom" class="form-control input-md" required="">
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="Description">Description</label>
  <div class="col-md-4">                     
    <textarea class="form-control" id="Description" name="Description"></textarea>
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="prix">Prix</label>  
  <div class="col-md-4">
  <input id="prix" name="number" type="number" step="any" placeholder="prix" class="form-control input-md">
  </div>
</div>
<div class="form-group">
  <label class="col-md-4 control-label" for="urlExter">URL Externe d'explication</label>  
  <div class="col-md-4">
  <input id="urlExter" name="urlExter" type="url" placeholder="URL" class="form-control input-md">
  </div>
</div>
<div class="form-group">
  <div class="col-md-4">
    <input id="singlebutton" name="singlebutton" class="btn btn-primary" type="submit"/>
  </div>
</div>
</fieldset>
</form>
END;
    }

}

