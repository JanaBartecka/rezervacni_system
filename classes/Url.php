<?php

class Url {
    /**
     * redirect to different url
     * 
     * @param string $path - url ending on which should be redirected
     * 
     * @return void
     * 
     */

    
    public static function redirectUrl($path) {
        if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] != "off") {
            $url_protocol = "https";
        } else {
            $url_protocol = "http";
        }

        header ("location: $url_protocol://" . $_SERVER["HTTP_HOST"] . $path);
    }
}