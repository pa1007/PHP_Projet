<?php

namespace mywishlist\vue;

use Slim\Slim;

class   VueCompte extends Vue {

    const CREA = 1;
    const LOGIN = 2;
    const AUFMODIFCOMPTE = 31;
    const AUFSEELISTES = 32;
    const NORMAL = 30;
    private $message;
    private $listes;
    private $user;

    public function __construct($message = "") { $this->message = $message; }

    public function render($sel) {
        $cont = "";
        if ($this->message !== "") {
            $cont = <<<END
    <div class="alert alert-danger" role="alert">
     $this->message
    </div>
END;
        }
        switch ($sel) {
            case self::CREA:
                $cont .= $this->renderForm();
                break;
            case self::LOGIN:
                $cont .= $this->renderLog();
                break;
            case self::AUFMODIFCOMPTE:
                $cont .= $this->renderAufModifCompte();
                break;
            case self::AUFSEELISTES:
                $cont .= $this->renderAufListes();
                break;
            case self::NORMAL:
                $cont .= $this->renderAufMenu();
                break;
        }
        $head = parent::renduTitre();
        $menu = parent::renduMenu();
        $foot = parent::rendufooter();
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

    private function renderForm() {

        return <<<END
<form method="post">
<legend>Créer votre compte utilisateur !</legend>
    <div class="form-row">
      <div class="form-group col-md-6">
      <label for="name">Nom</label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Nom" required>
    </div>
	<div class="form-group col-md-6">
      <label for="prenom">Nom</label>
      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" required>
    </div></div>
  <div class="form-row">
      <div class="form-group col-md-6">
      <label for="login">Login</label>
      <input type="text" class="form-control" id="login" name="login" placeholder="login" required>
    </div>
     <div class="form-group col-md-6">
      <label for="mail">Mail</label>
      <input type="email" class="form-control" id="mail" name="mail" placeholder="Email" required>
    </div> </div>
  <div class="form-row">
   <div class="form-group col-md-6">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
    </div>
    <div class="form-group col-md-6">
      <label for="passwordConf">Password</label>
      <input type="password" class="form-control" id="passwordConf" name="passwordConf" placeholder="Password" required>
    </div> </div>
  <button type="submit" class="btn btn-primary">Validez</button>
</form>
END;
    }

    private function renderLog() {
        $slim = Slim::getInstance();
        $request = $slim->request;
        $url = $request->getRootUri() . "/createcompte";
        return <<<END
    <form method="post">
    <legend>Connexion</legend>
      <div class="form-row">
      <div class="form-group col-md-6">
      <label for="login">Login</label>
      <input type="text" class="form-control" id="login" name="login" placeholder="login" required>
    </div>
       <div class="form-group col-md-6">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
    </div>
    </div>
 <div class="form-row">
    <div class="col-md-6">    
    <input  type="submit" class="btn-success btn" id="subM" value="Se connecter "/>
</div>
<div class="col-md-6">
<div>
<a href="$url">Créez un compte</a>
</div>
</div>
  <div>
</div>
	</div>
</form>
END;
    }

    private function renderAufModifCompte() {
        $slim = Slim::getInstance();
        $request = $slim->request;
        $url = $request->getPath();
        $urlLogout = $request->getRootUri() . "/logout";
        $u = $this->user;
        $urlmodifCompte = $url . "modifCompte";
        return <<<END
<div class="row">
  <div class="col-3">
    <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
      <a class="nav-link " href="$url?page= " role="tab">Page Principal</a>
      <a class="nav-link active"  href="$url?page=modif" role="tab">Modifier compte</a>
      <a class="nav-link"  href="$url?page=listes" role="tab">Listes liée au compte</a>
      <a class="nav-link"  href="$urlLogout" role="tab">Déconnexion</a>
    </div>
  </div>
  <div class="col-9">
    <div class="tab-content" id="v-pills-tabContent">
      <div>
         <div class="form">
          <form class="form-check" method="post" action="$urlmodifCompte">
           <div class="form-row">
      <div class="form-group col-md-6">
      <label for="name">Nom</label>
      <input type="text" class="form-control" id="name" name="name" placeholder="Nom" required value="$u->nom">
    </div>
    <div class="form-group col-md-6">
      <label for="prenom">Prenom</label>
      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" required value="$u->prenom">
    </div></div>
  <div class="form-row">
      <div class="form-group col-md-6">
      <label for="login">Login</label>
      <input class="form-control disabled" disabled placeholder="login" required value="$u->login">
    </div>
     <div class="form-group col-md-6">
      <label for="mail">Mail</label>
      <input type="email" class="form-control" id="mail" name="mail" placeholder="Email"  value="$u->mail" required>
    </div> </div>
  <div class="form-row">
   <div class="form-group col-md-6">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
    <div class="form-group col-md-6">
      <label for="passwordConf">Confirmation</label>
      <input type="password" class="form-control" id="passwordConf" name="passwordConf" placeholder="Password">
    </div> </div>
    <button type="submit" class="btn btn-primary">Validez</button>
          </form>
          <form action="$urlmodifCompte" method="post">
          <input type="hidden" name="_METHOD" value="DELETE" hidden/> <!--https://docs.slimframework.com/routing/delete/ -->
          <div class="col-md-6"><input type="submit" class="btn btn-danger" value="Supprimez Compte"></div>
          </form>
        </div>
</div>
    </div>
  </div>
</div>
END;
    }

    private function renderAufListes() {
        $slim = Slim::getInstance();
        $request = $slim->request;
        $url = $request->getPath();
        $rootUri = $request->getRootUri();
        $urlLogout = $rootUri . "/logout";
        $formURL = $rootUri . "/connected/addModif";
        return <<<END
<div class="row">
  <div class="col-3">
    <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
      <a class="nav-link " href="$url?page= " role="tab">Page Principal</a>
      <a class="nav-link"  href="$url?page=modif" role="tab">Modifier compte</a>
      <a class="nav-link active"  href="$url?page=listes" role="tab">Listes liée au compte</a>
      <a class="nav-link"  href="$urlLogout" role="tab">Déconnexion</a>
    </div>
  </div>
  <div class="col-9">
    <div class="tab-content" id="v-pills-tabContent">
      <div>
      <div class='row'>
      $this->listes
       <div class="form">
          <form class="form-check" method="post" action="$formURL">
          <label><h5>Ajoutez un token de modification</h5></label>
		  <div><input name="modifTokAdd" type="text" class="form-control" placeholder="token"/></div>
		  <div><input type="submit"></div>
          </form>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
END;
    }

    private function renderAufMenu() {
        $slim = Slim::getInstance();
        $request = $slim->request;
        $url = $request->getPath();
        $urlLogout = $request->getRootUri() . "/logout";
        return <<<END
<div class="row">
  <div class="col-3">
    <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
      <a class="nav-link active" href="$url?page= " role="tab">Page Principal</a>
      <a class="nav-link"  href="$url?page=modif" role="tab">Modifier compte</a>
      <a class="nav-link"  href="$url?page=listes" role="tab">Listes liée au compte</a>
      <a class="nav-link"  href="$urlLogout" role="tab">Déconnexion</a>
    </div>
  </div>
  <div class="col-9">
    <div class="tab-content" id="v-pills-tabContent">
      <div>
      
</div>
    </div>
  </div>
</div>
END;

    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

}