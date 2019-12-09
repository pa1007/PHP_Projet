<?php

namespace mywishlist\vue;

use Slim\Slim;

class VueParticipant extends Vue {

    const LISTE = 1;
    const ITEM = 2;

    protected $tableau;

    public function __construct($tab) {
        $this->tableau = $tab;
    }

    public function render($sel) {

        if ($this->tableau == null) {
            $slim = Slim::getInstance();
            $slim->redirect("/error");
            return;
        }


        $head = parent::renduTitre();
        $menu = parent::renduMenu();
        $foot = parent::rendufooter();

        switch ($sel) {
            case VueParticipant::LISTE :
                $content = $this->renderList();
                break;
            case VueParticipant::ITEM :
                $content = $this->renderItem();
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
            <a href="/item/$item->id" class="btn btn-primary btn-smal active" role="button" aria-pressed="false">Plus d'information</a>
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
            $all = <<<END
            <div class='border border-dark'>
           <p style="transform: rotate(0);">$lis->titre / Liste numéro <a class='stretched-link' href='/liste/$lis->no'> $lis->no</a></p>
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


}

