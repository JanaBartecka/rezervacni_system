<?php

class Database {

    /**
     * connection to database
     * @return object - for database connection
     */


    public function connectionDB() {
        // $db_host="127.0.0.1";
        // $db_user="mcZirafaAdmin";
        // $db_password="McZirafa2023+";
        // $db_name="zirafa";

        $db_host="db.dw128.webglobe.com";
        $db_user="janabartecka_cz1";
        $db_password="McZirafa2023+";
        $db_name="janabartecka_cz1";

        $connection = "mysql:host=" . $db_host . ";dbname=" . $db_name . ";charset=utf8";

        try {
            $db = new PDO($connection, $db_user, $db_password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo $e -> getMessege();
            exit;
        }
    }
}