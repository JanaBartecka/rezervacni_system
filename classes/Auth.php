<?php

class Auth {

    /**
     * 
     * verify if user is logged in
     * 
     * @return boolean true if the user is logged in
     */

    public static function isLoggedIn() {
        return isset($_SESSION["is_logged_in"]) and $_SESSION["is_logged_in"];
    }

}