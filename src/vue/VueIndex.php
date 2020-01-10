<?php


namespace mywishlist\vue;


class VueIndex extends Vue
{

    public function render($sel)
    {
        $head = parent::renduTitre();
        $menu = parent::renduMenu();
        $foot = parent::rendufooter();

        echo $head . $menu . "<div style=\"text-align: center;\"><h1 style=\"\">MyWhishList</h1><img src='img/wishlistLogo.png' height=\"312\" width=\"820\" style=\"border: 1px dashed rgb(66, 133, 244);\"><ul class=\"list-group\" style=\"\">
  
  
  
</ul><div class=\"form-group\" style=\"\"><label>Site créé par Paul-Alexandre Fourrière, Fabien Drommer, Laury Thiebaux et Matthias Froehlicher</label><textarea class=\"form-control\"></textarea></div></div>
</div>" . $foot;
    }
}