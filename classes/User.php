<?php

class User {
    
    /**
     * create user
     * 
     * @param object $connection - connection to database
     * @param string $first_name - first name of the user
     * @param string $second_name - second name of the user
     * @param string $email - email
     * @param integer $phone_number - phone number
     * @param string $password - hashed password
     * 
     * @return $id_user - id of user
     */

    public static function createUser($connection, $first_name, $second_name, $email, $phone_number, $user_type, $password) {
        
        $sql = "INSERT INTO user (first_name, second_name, email, phone_number, user_type, password) 
            VALUES (:first_name,:second_name,:email,:phone_number,:user_type,:password)";

            $stmt = $connection -> prepare($sql);

            $stmt -> bindValue(":first_name", $first_name, PDO::PARAM_STR);
            $stmt -> bindValue(":second_name", $second_name, PDO::PARAM_STR);
            $stmt -> bindValue(":email", $email, PDO::PARAM_STR);
            $stmt -> bindValue(":phone_number", $phone_number, PDO::PARAM_STR);
            $stmt -> bindValue(":user_type", $user_type, PDO::PARAM_STR);
            $stmt -> bindValue(":password", $password, PDO::PARAM_STR);

            try {
                if ($stmt -> execute()) {
                    $id_user = $connection -> lastInsertId();
                    return $id_user;
                } else {
                    throw new Exception("chyba pri vytvareni noveho uzivatele");
                }
            } catch(Exception $e) {
                error_log("chyba u funkce createUser", 3, "../errors/error.log");
                echo "typ chyby:" . $e->getMessage();
            }

    }

    /**
     * 
     * verification of the user s password
     * 
     * @param object $connection - connection to database
     * @param string $login_email - email from sign in form
     * @param string $login_password - password from sign in form
     * 
     * @return boolean true - if the password from database is same as from sign in form
     */

    public static function authenticationUser($connection, $login_email, $login_password) {
        $sql = "SELECT password
                FROM user
                WHERE email = :email";
        
        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":email", $login_email, PDO::PARAM_STR);
            
        try {
            if ($stmt -> execute()) {
                if($user = $stmt -> fetch()) {
                    return password_verify($login_password, $user[0]);
                }
            } else {
                throw new Exception("chyba pri autentifikaci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce authenticationUser", 3, "../errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }

    /**
     * 
     * get properties of one user using his/her email
     * 
     * @param object $connection - connection to database
     * @param string $email - email og the user
     * 
     * @return integer user ID
     */

    public static function getUserID($connection, $email) {
        $sql = "SELECT id_user
                FROM user
                WHERE email = :email";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":email", $email, PDO::PARAM_STR);

        try {
            if($stmt -> execute()){
                $result = $stmt -> fetch(PDO::FETCH_NUM);
                if ($result) {
                    $user_id = $result[0];

                    return $user_id;
                } else {
                    return false;
                }

            } else {
                throw new Exception("chyba pri ziskani ID uzivatele");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce getUserID", 3, "../errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }

    

    public static function getUserPropertiesByID($connection, $id_user, $columns = '*'){
        $sql = "SELECT $columns 
                FROM user
                WHERE id_user = :id_user";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(":id_user", $id_user, PDO::PARAM_INT);

        try {
            if($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            } else {
                throw new Exception("Získání uživatelské role selhalo");
            }
        } catch (Exception $e) {
            error_log("Chyba u funkce getUserPropertiesByID\n", 3, "../errors/error.log");
            echo "Typ chyby: " . $e->getMessage();
        }       
    }

    public static function getAllUsers($connection, $columns = '*'){
        $sql = "SELECT $columns 
                FROM user
                ORDER BY second_name, first_name ASC" ;

        $stmt = $connection->prepare($sql);

        try {
            if($stmt->execute()){
                $result = $stmt->fetchAll();
                return $result;
            } else {
                throw new Exception("Získání uživatelské role selhalo");
            }
        } catch (Exception $e) {
            error_log("Chyba u funkce getAllUsers\n", 3, "../errors/error.log");
            echo "Typ chyby: " . $e->getMessage();
        }       
    }
}