<?php

namespace mywishlist\model;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\exception\AuthException;

class Authentication {

    /**
     * @throws AuthException l'erreur lors de la connexion
     */
    public static function createUser($userName, $password, $nom, $prenom, $mail) {
        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
        } else {
            throw new AuthException("Email invalide");
        }
        if (User::where("login", "=", $userName)->first() != null) {
            throw new AuthException("Login déjà utilisé");
        }
        $user = new User();
        $user->login = $userName;
        $user->mail = $mail;
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->uid = self::generateUID();
        $user->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
        $user->save();
        self::loadProfile($user->uid);
    }

    private static function generateUID() {
        do {
            $uid = bin2hex(openssl_random_pseudo_bytes(32));
        } while (User::where('uid', "=", $uid)->first() !== null);
        return $uid;
    }

    /**
     * @param $uid String l'uid
     * @throws AuthException si il y a une erreur lors de la charge du profil
     */
    private static function loadProfile($uid) {
        $u = User::where("uid", '=', $uid)->first();
        if ($u === null) {
            throw new AuthException('Erreur lors de la connexion');
        }
        unset($_SESSION['id']);
        $_SESSION['id'] = ["login" => $u->login, "uid" => $uid];
    }

    public static function authenticate($username, $password) {
        try {
            $u = User::where("login", "=", $username)->firstOrFail();
            if (password_verify($password, $u->password)) {
                self::loadProfile($u->uid);
            } else {
                throw new AuthException("Erreur lors de l'authentification");
            }
        } catch (ModelNotFoundException $ignored) {
            throw new AuthException("Erreur lors de l'authentification");
        }
    }

    public static function checkAccessRights($required) {


    }
}