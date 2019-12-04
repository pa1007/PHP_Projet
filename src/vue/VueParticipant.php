<?php

namespace mywishlist\vue;

class VueParticipant extends Vue {

    const LISTE = 1;
    const ITEM = 2;

    protected $tableau;

    public function __construct($tab) {
        $this->tableau = $tab;
    }

    public function render($sel) {

        /*       if ($this->tableau == null) {
                   $slim = Slim::getInstance();
                   $slim->redirect("/");
                   return;
               }*/


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
<!DOCTYPE html>
$head
$menu
<body>
<div class="content">
 $content
</div>
</body>
$foot
END;
        echo $html;

    }

    private function renderItem($item = null) {
        if ($item == null) {
            $item = $this->tableau;
        }
        return <<<END
<div class="container">
      <div class="card-deck mb-3 text-center">
        <div class="card mb-4 box-shadow">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal"><h1 class="card-title pricing-card-title">$item->nom</h1></h4>
          </div>
          <div class="card-body "><img class="border border-primary" src='../img/$item->img' height="250" width="250" style="" alt="">
            
          <div class="m-5">$item->descr</div></div>
        </div>
       
      </div>

END;
    }

    private function htmlUnItem() {

    }

    private function htmlListItem() {

    }
}

