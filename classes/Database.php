<?php

class Database {

    /**
     * connection to database
     * @return object - for database connection
     */


    public function connectionDB() {
        $db_host="127.0.0.1";
        $db_user="mcZirafaAdmin";
        $db_password="McZirafa2023+";
        $db_name="zirafa";

        //$connection = mysqli_connect($db_host,$db_user,$db_password,$db_name);
        $connection = "mysql:host=" . $db_host . ";dbname=" . $db_name . ";charset=utf8";

        /**
         * returns connection error - string value
         */
        // if (mysqli_connect_error()) {
        //     echo mysqli_connect_error();
        //     exit;
        // }

        // return $connection;

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