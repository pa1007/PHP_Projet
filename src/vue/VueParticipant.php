<?php

namespace mywishlist\vue;

use mywishlist\model\User;
use Slim\Slim;

class VueParticipant extends Vue {

    const LISTE = 1;
    const ITEM = 2;
    const ITEM_CREA = 4;
    const LIST_CREA = 5;
    const PUBLIC = 6;
    const LIST_CREATEUR_PUBLICS = 7;

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
            case VueParticipant::LIST_CREA :
                $content = $this->renderCreateList();
                break;
            case VueParticipant::PUBLIC :
                $content = $this->publicListes();
                break;
            case VueParticipant::LIST_CREATEUR_PUBLICS:
                $content = $this->renderCreateur();
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
        $text = "<h4>$tableau->titre</h4><div> ";
        foreach ($items as $item) {
            $text .= $this->renderItemListe($item);
        }
        $text .= "</div> <h4>$tableau->description</h4>";
        $text .= $this->renderMessages();
        $text .= $this->renderAjoutMessageListe();
        return $text;
    }

    private function renderItemListe($item) {
        $slim = Slim::getInstance();
        $req = $slim->request;
        $li = $this->tableau;
        $rootUri = $req->getRootUri() . "/liste/$li->token/item/$item->id";
        $img = $this->generateImageView($item->images);
        $badge = "";


        $lis = $item->Liste;
        $reserv = $item->Reservation;

        $val = "<a href=\"$rootUri\" class=\"btn btn-primary btn-smal active\" role=\"button\" aria-pressed=\"false\">Plus d'information</a>";
        if ($item->Reservation !== null) {
            $badge = "<span class=\"badge badge-danger\">Réservé</span>";
            if ($lis->hasExpire()) {

                $val = "Réservation de : " . $reserv->nomUtilisateur . "<br>";
                $val .= "Message de réservation : " . $reserv->message;
            }
        }


        return <<<END
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <div class="my-0 font-weight-normal"><h1 class="card-title ">$item->nom</h1></div>
          </div>
          <div class="card-body d-inline-flex">$img
            <div class="m-5">
            <br><br>
               <div class="d-block"><h3>$item->descr</h3> $badge</div>
                <div> $item->tarif €</div>
            <br>
            $val
            </div>
          </div>
          </div>
   
END;
    }

    private function generateImageView($images) {
        $slim = Slim::getInstance();
        $request = $slim->request;
        $url = $request->getRootUri();
        $more = "";
        $i = 1;
        foreach ($images as $image) {
            if (filter_var($image->img, FILTER_VALIDATE_URL)) {
                $img = $image->img;
            } else {
                $img = $url . "/img/$image->img";
            }
            if ($i === 1) {
                $more .= "    <div class=\"carousel-item active\">";
            } else {
                $more .= "    <div class=\"carousel-item\">";
            }
            $more .= "<img class=\"d-block img-fluid\" style='width: 250px; height: 250px' src=\"$img\" alt=\"\" /></div>";
            $i++;
        }
        return <<<END
<div class="carousel slide w-25" data-ride="carousel">
  <div class="carousel-inner">
    $more
  </div>
</div>
END;

    }

    private function renderMessages() {
        $u = $this->tableau->comm;
        $comms = "";
        foreach ($u as $item) {
            $comms .= "<div class=\"alert alert-dark \" role=\"alert\">
  $item->nom : <div class='d-inline'> $item->message</div>
</div>";
        }
        return $comms;
    }

    private function renderAjoutMessageListe() {
        $poN = "";
        if (isset($_SESSION['id'])) {
            $var = User::find($_SESSION['id']['uid']);
            $poN = $var->prenom . " " . $var->nom;
        }
        return <<<END
       <form class="form-horizontal" method="post">
 <div class="form-row">
<div class="form-group col-md-6">
  <label class="control-label" for="message">Ajoutez un message à la liste</label>  
  <input id="message" name="message" type="text" placeholder="Votre message" class="form-control input-md">
</div>
      <div class="form-group col-md-6">
      <label for="nom">Nom</label>
      <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required value="$poN">
    </div></div>
    <input type="submit" class="form-control btn btn-info">
</form>
END;
    }

    private function renderItem() {
        $item = $this->tableau['item'];
        $lis = $item->Liste;
        $all = "";
        $img = $this->generateImageView($item->images);
        if (!is_null($lis)) {
            $slim = Slim::getInstance();
            $req = $slim->request;
            $url = $req->getRootUri() . "/liste/$lis->token";
            $all = <<<END
            <div class='border border-dark'>
           <p style="transform: rotate(0);"><a class='stretched-link' href='$url'> $lis->titre</a></p>
           </div>
END;
        }
        if (!$lis->hasExpire()) {
            $form = $this->tableau['reservation'];
        } else {
            $form = "";
        }
        return <<<END
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <div class="my-0 font-weight-normal"><h1 class="card-title ">$item->nom</h1></div>
          </div>
          <div class="card-body d-inline-flex">$img
            <div class="m-5">
            <br><br>
                <h3>$item->descr</h3>
                <div> $item->tarif €</div>
            <br>
             $all
             <br>
             $form
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

    private function renderCreateList() {
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
<form class="form-horizontal" method="post">
<fieldset>

<!-- Form Name -->
<legend>Création de Liste</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Nom de la Liste</label>  
  <div class="col-md-4">
  <input id="textinput" name="titre" type="text" placeholder="nom" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Description">Description</label>  
  <div class="col-md-4">
  <input id="Description" name="Description" type="text" placeholder="description" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="date">Date d'expiration</label>  
  <div class="col-md-4">
  <input id="date" name="date" type="date" class="form-control input-md" required="">
  <span class="help-block">Rentrez une date au format JJ/MM/YYYY</span>  
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="valider">Validation</label>
  <div class="col-md-4">
    <button id="valider" name="valider" class="btn btn-primary">Valider</button>
  </div>
</div>

</fieldset>
</form>

END;
    }

    private function publicListes() {
        $slim = Slim::getInstance();
        $startURL = $slim->request->getRootUri() . "/liste";
        $ex = "";
        foreach ($this->tableau as $liste) {
            $msg = $liste->Item->count();
            if (!$liste->hasExpire()) {
                $ex .= <<<END
<div class="card text-center">
  <div class="card-header">
    <h3>$liste->titre</h3>
  </div>
  <div class="card-body">
    <h5 class="card-title">$liste->description</h5>
    <p class="card-text">il y a $msg items</p>
    <a href="$startURL/$liste->token" class="btn btn-primary">Voir la liste</a>
  </div>
  <div class="card-footer text-muted">
    Expire le $liste->expiration
  </div>
</div><br>
END;
            }
        }
        return $ex;
    }

    private function renderCreateur() {
        return <<<END
<div>
Voici les créateurs qui ont partagé leurs listes :
</div>
$this->tableau
END;

    }
}

