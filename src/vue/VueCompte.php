<?php

namespace mywishlist\vue;

use Slim\Slim;

class VueCompte extends Vue {

    const CREA = 1;
    const LOGIN = 2;
    private $message;

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
}