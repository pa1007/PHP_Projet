<?php


namespace mywishlist\vue;


use Slim\Slim;

abstract class Vue {

    public abstract function render($sel);

    protected final function renduTitre() {
        return "<!DOCTYPE html><html lang='fr'>
<head>
             
    <title>mywishlist</title>
  <!-- Bootstrap core (CSS et JS) -->
    <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\" integrity=\"sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh\" crossorigin=\"anonymous\">
    <script src=\"https://code.jquery.com/jquery-3.4.1.slim.min.js\" integrity=\"sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n\" crossorigin=\"anonymous\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js\" integrity=\"sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo\" crossorigin=\"anonymous\"></script>
    <script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js\" integrity=\"sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6\" crossorigin=\"anonymous\"></script>
</head>";
    }

    protected final function renduMenu() {

        $url = $this->generateLink();
        $slim = Slim::getInstance();
        $request = $slim->request;
        $u = $request->getRootUri();
        return " <div class=\"d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow\">
      <h5 class='w-auto'><a class=\"my-0 mr-md-auto font-weight-normal text-dark text-decoration-none \" href=\"$u/\">My Wish List</a></h5><!--Erreur HTML pour affichage-->
      <nav class=\"my-2 my-md-0 mr-md-3\">
        <a class=\"p-2 text-dark\" href=\"$u/createliste\">Créer une liste</a>
        <a class=\"p-2 text-dark\" href=\"$u/createitem\">Créer un item</a>
        <a class=\"p-2 text-dark\" href=\"$u/publique\">Liste publique</a>
        <a class=\"p-2 text-dark\" href=\"$u/createur\">Créateur</a>
      </nav>
      $url
    </div>";
    }

    private final function generateLink() {
        $slim = Slim::getInstance();
        $request = $slim->request;
        if (!array_key_exists("id", $_SESSION)) {
            $url = $request->getRootUri() . "/connect";
            $url = "<a class=\"btn btn-outline-primary\" href=\"$url\">Connexion</a>";
        } else {
            $name = $_SESSION['id']['login'];
            $t = $request->getRootUri() . '/connected';
            $url = "<a class='class=\"p-2 text-dark\"' href='$t'>Bienvenue $name</a>";
        }
        return $url;
    }

    protected final function rendufooter() {
        return "  <!-- Footer -->
  <footer class=\"py-5 bg-dark\">
    <div class=\"container\">
      <p class=\"m-0 text-center text-white small\"> &copy; </p>
    </div>
  </footer>
  </body>
</html>
";
    }

}