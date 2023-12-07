<?php

class Lesson {

    /**
     * get properties of one lesson according to ID_LEKCE
     * 
     * @param object $connection - connection to database
     * @param integer id_lekce - id of one lekce
     * @param string $columns - columns which will be selected from table lekce
     * 
     * @return mixed - associative array which include information about one lekce or return null if lekce was not found
     */



    public static function getLesson($connection, $id_lekce, $columns = "*") {

        $sql = "SELECT $columns
                FROM lekce
                WHERE id_lekce = :id_lekce";
        
        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()){
                return $stmt -> fetch();
            } else {
                throw new Exception("ziskani dat o lekci selhalo");
            }
        } catch (Exception $e) {
            error_log("chyba u funkce getLesson, ziskani dat selhalo", 3, "./errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }

    }

    /**
     * update information about lesson
     * 
     * @param object $connection - connection to database
     * @param string $name_lekce - name of lesson
     * @param string $day - day of lesson
     * @param string $time_start - start time of lesson
     * @param string $time_end - end time of lesson
     * @param string $time_to_apply - time until which user can apply to lesson
     * @param integer $max_to_apply - maximum number of users which can apply
     * @param integer $id_lekce - id number of lesson
     * 
     * @return void
     */

    public static function UpdateLesson($connection, $name_lekce, $day, $time_start, $time_end, $time_apply_to, $max_to_apply, $id_lekce) {

        $sql = "UPDATE lekce
        SET name_lekce = :name_lekce,
            day = :day,
            time_start = :time_start,
            time_end = :time_end,
            time_apply_to = :time_apply_to,
            max_to_apply =:max_to_apply
        WHERE id_lekce = :id_lekce";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":name_lekce", $name_lekce, PDO::PARAM_STR);
        $stmt -> bindValue(":day", $day, PDO::PARAM_STR);
        $stmt -> bindValue(":time_start", $time_start, PDO::PARAM_STR);
        $stmt -> bindValue(":time_end", $time_end, PDO::PARAM_STR);
        $stmt -> bindValue(":time_apply_to", $time_apply_to, PDO::PARAM_STR);
        $stmt -> bindValue(":max_to_apply", $max_to_apply, PDO::PARAM_INT);
        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
                return true;
            } else {
                throw new Exception ("update lekce se nepovedl");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce updateLekce", 3, "../errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }

    }

    /**
     * funtion to delete specific lesson
     * 
     * @param object $connection - connection to database
     * @param integer $id_lekce - if of lesson
     * 
     * @return void
     */

    public static function deleteLesson($connection, $id_lekce) {
        $sql = "DELETE
                FROM lekce
                WHERE id_lekce = :id_lekce";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
                return true;
            } else {
                throw new Exception("smazani lekce se nepovedlo");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce deleteLesson", 3, "../errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }

    }

    /**
     * return all lessons from database
     * 
     * @param object $connection - connection to database
     * @param string $columns - columns which will be selected from table lesson
     * 
     * @return array $lekce - array of objects where one object is one lesson
     * 
     */
    public static function getAllLessons($connection, $columns = "*") {
        $sql="SELECT * 
                FROM lekce
                ORDER BY day ASC";

        $stmt = $connection -> prepare($sql);

        try {
            if ($stmt -> execute()) {
                return $stmt -> fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("chyba pri ziskani vsech lekci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce getAllLessons", 3, "./errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }

    /**
     * create lesson
     * 
     * @param object $connection - connection to database
     * @param string $day - day of lesson
     * @param string $time_start - start time of lesson
     * @param string $time_end - end time of lesson
     * @param string $name_lekce - name of lesson
     * @param integer $max_to_apply - maximum number of users which can apply
     * @param string $time_to_apply - time until which user can apply to lesson
     * 
     * @return void
     */

    public static function createLesson($connection, $intDay, $startDay, $endDay, $time_start, $time_end, $name_lekce, $max_to_apply, $time_apply_to) {
        
        $sql = "CALL LoadLekce(:intDay, :startDay, :endDay,:time_start,:time_end,:name_lekce,:max_to_apply,:free_to_apply,:time_apply_to)";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":intDay", $intDay, PDO::PARAM_INT);
        $stmt -> bindValue(":startDay", $startDay, PDO::PARAM_STR);
        $stmt -> bindValue(":endDay", $endDay, PDO::PARAM_STR);
        $stmt -> bindValue(":time_start", $time_start, PDO::PARAM_STR);
        $stmt -> bindValue(":time_end", $time_end, PDO::PARAM_STR);
        $stmt -> bindValue(":name_lekce", $name_lekce, PDO::PARAM_STR);
        $stmt -> bindValue(":max_to_apply", $max_to_apply, PDO::PARAM_INT);
        $stmt -> bindValue(":free_to_apply", $max_to_apply, PDO::PARAM_INT);
        $stmt -> bindValue(":time_apply_to", $time_apply_to, PDO::PARAM_STR);

        try {
            if ($stmt -> execute()) {
                // $id = $connection -> lastInsertId();
                // return $id;
                return true;
            } else {
                throw new Exception("nelze vytvorit novou lekci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce createLesson", 3, "../errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }

    /**
     * create lesson
     * 
     * @param object $connection - connection to database
     * @param string $day - day of lesson
     * @param string $time_start - start time of lesson
     * @param string $time_end - end time of lesson
     * @param string $name_lekce - name of lesson
     * @param integer $max_to_apply - maximum number of users which can apply
     * @param string $time_to_apply - time until which user can apply to lesson
     * 
     * @return void
     */

     public static function createLessonOld($connection, $day, $time_start, $time_end, $name_lekce, $max_to_apply, $time_apply_to) {
        
        $sql = "INSERT INTO lekce (day, time_start, time_end, name_lekce, max_to_apply,free_to_apply,time_apply_to) 
            VALUES (:day,:time_start,:time_end,:name_lekce,:max_to_apply,:free_to_apply,:time_apply_to)";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":day", $day, PDO::PARAM_STR);
        $stmt -> bindValue(":time_start", $time_start, PDO::PARAM_STR);
        $stmt -> bindValue(":time_end", $time_end, PDO::PARAM_STR);
        $stmt -> bindValue(":name_lekce", $name_lekce, PDO::PARAM_STR);
        $stmt -> bindValue(":max_to_apply", $max_to_apply, PDO::PARAM_INT);
        $stmt -> bindValue(":free_to_apply", $max_to_apply, PDO::PARAM_INT);
        $stmt -> bindValue(":time_apply_to", $time_apply_to, PDO::PARAM_STR);

        try {
            if ($stmt -> execute()) {
                $id = $connection -> lastInsertId();
                return $id;
            } else {
                throw new Exception("nelze vytvorit novou lekci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce createLesson", 3, "../errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }

        /**
     * update value in column free_to_apply
     * 
     * @param object $connection - connection to database
     * @param string $id_lekce - id of lesson
     * @param integer $free_to_apply - free places to apply apply
     * 
     * @return void
     */

    public static function UpdateFreeToApply($connection, $free_to_apply, $id_lekce) {

        $sql = "UPDATE lekce
                SET free_to_apply = :free_to_apply
                WHERE id_lekce = :id_lekce";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":free_to_apply", $free_to_apply, PDO::PARAM_INT);
        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
                return true;
            } else {
                throw new Exception ("update lekce se nepovedl");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce UpdateFreeToApply", 3, "../errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }

        /**
     * return future lessons from database limited by number lessonsPerPage and with specified offset
     * 
     * @param object $connection - connection to database
     * @param integer $lessonsPerPage - number of lessons per page
     * @param integer $offset - offset of the lessons from CURRENT_DATE
     * 
     * @return array $lekce - array of objects where one object is one lesson
     * 
     */
    public static function getFutureLessonsPerPage($connection, $lessonsPerPage, $offset) {
        $sql="SELECT * 
                FROM lekce
                WHERE day >= CURRENT_DATE() AND name_lekce='opakk'
                ORDER BY day ASC
                LIMIT :lessonsPerPage
                OFFSET :offset"; 

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":lessonsPerPage", $lessonsPerPage, PDO::PARAM_INT);
        $stmt -> bindValue(":offset", $offset, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
                return $stmt -> fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("chyba pri ziskani vsech lekci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce getAllLessons", 3, "./errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }

            /**
     * return future lessons from database limited by number lessonsPerPage and with specified offset
     * 
     * @param object $connection - connection to database
     * @param integer $lessonsPerPage - number of lessons per page
     * @param integer $offset - offset of the lessons from CURRENT_DATE
     * 
     * @return array $lekce - array of objects where one object is one lesson
     * 
     */
    public static function getPastLessonsPerPage($connection, $lessonsPerPage, $offset) {
        $sql="WITH pastLessons AS (
                SELECT * 
                FROM lekce
                WHERE day < CURRENT_DATE() AND name_lekce='opakk'
                ORDER BY day DESC
                LIMIT :lessonsPerPage
                OFFSET :offset)
              SELECT * FROM pastLessons
              ORDER BY day ASC";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":lessonsPerPage", $lessonsPerPage, PDO::PARAM_INT);
        $stmt -> bindValue(":offset", $offset, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
                return $stmt -> fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("chyba pri ziskani vsech lekci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce getAllLessons", 3, "./errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }

    /**
     * return future lessons from database limited by number lessonsPerPage and with specified offset
     * 
     * @param object $connection - connection to database
     * @param integer $lessonsPerPage - number of lessons per page
     * @param integer $offset - offset of the lessons from CURRENT_DATE
     * 
     * @return array $lekce - array of objects where one object is one lesson
     * 
     */
    public static function getLessonsFiltered($connection, $lessonsPerPage, $offset) {
        $sql="SELECT DISTINCT name_lekce
                FROM lekce
                ORDER BY day ASC
                LIMIT :lessonsPerPage
                OFFSET :offset";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":lessonsPerPage", $lessonsPerPage, PDO::PARAM_INT);
        $stmt -> bindValue(":offset", $offset, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
                return $stmt -> fetchAll(PDO::FETCH_ASSOC);
            } else {
                throw new Exception("chyba pri ziskani vsech lekci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce getAllLessons", 3, "./errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
    }


    /**
     * get all lessons on which user with id_user is applied
     * 
     * @param object $connection - connection to database
     * @param string $id_user - id of user
     * 
     * @return array accociative array with data about each lesson - name of the lesson, day, time start and time end
     */
    public static function getLessonByUser($connection,$id_user){

        $sql = "SELECT lekce.id_lekce, lekce.name_lekce, lekce.day, lekce.time_start, lekce.time_end, prihlaseni.number_of_applications
                FROM lekce
                JOIN prihlaseni
                ON prihlaseni.id_lekce = lekce.id_lekce
                WHERE id_user = :id_user
                ORDER BY lekce.day ASC";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_user", $id_user, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
            $result = $stmt->fetchAll();
            if ($result) {
                return $result;
            } else {
                return 0;
            }
            } else {
            throw new Exception("nelze vypsat seznam lekci");
            }
        } catch(Exception $e) {
        error_log("chyba u funkce getLessonByUser", 3, "./errors/error.log");
        echo "typ chyby:" . $e->getMessage();
        } 

     }

    /**
     * get all lessons on which user with id_user is applied
     * 
     * @param object $connection - connection to database
     * @param string $id_user - id of user
     * 
     * @return array accociative array with data about each user - first_name, second_name, phone_number, email, id_user, number_of_applications
     */
    public static function getUsersByLesson ( $connection,$id_lekce ) {

        $sql = "SELECT user.first_name, user.second_name, user.phone_number, user.email, user.id_user, prihlaseni.number_of_applications
                FROM user
                JOIN prihlaseni
                ON prihlaseni.id_user = user.id_user
                WHERE prihlaseni.id_lekce = :id_lekce";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
            $result = $stmt->fetchAll();
            if ($result) {
                return $result;
            } else {
                return 0;
            }
            } else {
            throw new Exception("nelze vypsat seznam prihlasenych");
            }
        } catch(Exception $e) {
        error_log("chyba u funkce getUsersByLesson", 3, "./errors/error.log");
        echo "typ chyby:" . $e->getMessage();
        } 

     }

    
}