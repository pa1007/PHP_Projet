<?php

namespace mywishlist\controller;

use mywishlist\exception\AuthException;
use mywishlist\model\Authentication;
use mywishlist\vue\VueCompte;
use Slim\Slim;

class CompteController {

    public function compteCrea() {
        if (!$this->isConnected()) {
            $msg = "";
            if (isset($_COOKIE['Error'])) {
                $msg = $_COOKIE['Error'];
                setcookie("Error", "", 1);
            }
            $vueCompte = new VueCompte($msg);
            $vueCompte->render(VueCompte::CREA);
        } else {
            $slim = Slim::getInstance();
            $slim->redirect($slim->urlFor("compteco"), 301);
        }
    }

    private function isConnected() {
        return isset($_SESSION['id']);
    }

    public function postCompteCrea() {
        $slim = Slim::getInstance();
        if (!$this->isConnected()) {
            $nom = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_SPECIAL_CHARS);
            $log = filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS);
            $pass = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            $passC = filter_var($_POST['passwordConf'], FILTER_SANITIZE_SPECIAL_CHARS);
            $email = $_POST['mail'];
            if ($pass === $passC) {
                try {
                    Authentication::createUser($log, $pass, $nom, $prenom, $email);
                } catch (AuthException $e) {
                    setcookie("Error", $e->getMessage(), time() + 10);
                    $slim->redirect($slim->urlFor("createcompte"), 302);
                }
            } else {
                setcookie("Error", "Les mots de passe ne correspondent pas ", time() + 10);
                $slim->redirect($slim->urlFor("createcompte"), 302);
            }
        } else {
            $slim->redirect($slim->urlFor("compteco"), 301);
        }
    }

    public function formConn() {
        if (!$this->isConnected()) {
            $msg = "";
            if (isset($_COOKIE['Error'])) {
                $msg = $_COOKIE['Error'];
                setcookie("Error", "", 1);
            }
            $v = new VueCompte($msg);
            $v->render(VueCompte::LOGIN);
        } else {
            $slim = Slim::getInstance();
            $slim->redirect($slim->urlFor("compteco"), 301);
        }
    }

    public function auth() {
        $slim = Slim::getInstance();
        if (!$this->isConnected()) {
            $log = filter_var($_POST['login'], FILTER_SANITIZE_SPECIAL_CHARS);
            $pass = filter_var($_POST['password'], FILTER_SANITIZE_SPECIAL_CHARS);
            try {
                Authentication::authenticate($log, $pass);
                $slim->redirect($slim->urlFor("compteco"), 301);
            } catch (AuthException $e) {
                setcookie("Error", $e->getMessage(), time() + 10);
                $slim->redirect($slim->urlFor("connect"), 302);
            }
        }
    }

    public function connected() {

    }

    public function logout() {
        unset($_SESSION['id']);
        $slim = Slim::getInstance();
        $slim->redirect($slim->urlFor('Menu'));
    }
}