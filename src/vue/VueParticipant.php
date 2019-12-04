<?php

namespace mywishlist\vue;

class VueParticipant extends \mywishlist\vue\Vue
{

    protected $tableau;

    public function __construct($tab)
    {
        $this->tableau = $tab;
    }

    private function htmlUnItem(){

    }

    private function htmlListItem(){

    }

    public function renderListe(){
        $v = Liste::get();

    }

    public function render()
    {
        $head = parent::renduTitre();
        $menu = parent::renduMenu();
        $foot = parent::rendufooter();

        $content = $this->tableau;

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
}

