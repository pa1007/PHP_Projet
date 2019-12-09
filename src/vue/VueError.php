<?php


namespace mywishlist\vue;


class VueError extends Vue {

    public function render($sel) {
        $head = parent::renduTitre();
        $menu = parent::renduMenu();
        $foot = parent::rendufooter();

        echo $head . $menu . "<div class=\"d-flex justify-content-center align-items-center\" id=\"main\">
    <h1 class=\"mr-3 pr-3 align-top border-right inline-block align-content-center\">404</h1>
    <div class=\"inline-block align-middle\">
    	<h2 class=\"font-weight-normal lead\" id=\"desc\">La page demandée n'existe pas.</h2>
    </div>
</div>" . $foot;
    }
}