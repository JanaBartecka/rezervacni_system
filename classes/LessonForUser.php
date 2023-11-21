<?php


class LessonForUser {

            /**
     * function which check if the user is applied to the lesson
     * 
     * @param object $connection - connection to database
     * @param integer $id_user - id of the user
     * @param integer $id_lekce - id of the lesson
     * 
     * @return integer number of application of the user for specific lesson
     */

     public static function checkUserApplied($connection, $id_user, $id_lekce) {
        $sql = "SELECT *
                FROM prihlaseni
                WHERE (id_user = :id_user AND id_lekce = :id_lekce)";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);
        
        try {
            if($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    return $result["number_of_applications"];
                } else {
                    return 0;
                }
                
            } else {
                throw new Exception("Získání prihlaseni uzivatele na lekci selhalo");
            }
        } catch (Exception $e) {
            error_log("Chyba u funkce checkUserApplied\n", 3, "/rezervacni_system/errors/error.log");
            echo "Typ chyby: " . $e->getMessage();
        }  
    }

        /**
     * create apllication of the user to the specific lesson
     * 
     * @param object $connection - connection to database
     * @param integer $id_user - id of the user
     * @param integer $id_lekce - id of the lesson
     * 
     * @return void
     */

     public static function createApplicationToLesson($connection, $id_user, $id_lekce) {
        $sql = "INSERT INTO prihlaseni (id_user, id_lekce)
        VALUES (:id_user,:id_lekce)";


        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);

        
        try {
            if ($stmt -> execute()) {
                $id = $connection -> lastInsertId();
                return $id;
            } else {
                throw new Exception("nelze vytvorit rezervaci na lekci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce createApplicationToLesson", 3, "./errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        } 
    }

        /**
     * change apllication of the user to the specific lesson
     * 
     * @param object $connection - connection to database
     * @param integer $id_user - id of the user
     * @param integer $id_lekce - id of the lesson
     * @param integer $number_of_applications - number of application of the specific user to specific lesson
     * 
     * @return void
     */

     public static function changeApplicationToLesson($connection, $id_user, $id_lekce, $number_of_applications) {
        $sql = "UPDATE prihlaseni
                SET number_of_applications = :number_of_applications
                WHERE id_user = :id_user AND id_lekce = :id_lekce";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_user", $id_user, PDO::PARAM_INT);
        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);
        $stmt -> bindValue(":number_of_applications", $number_of_applications, PDO::PARAM_INT);
        
        try {
            if ($stmt -> execute()) {
                return true;
            } else {
                throw new Exception("nelze vytvorit dalsi rezervaci na lekci");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce changeApplicationToLesson", 3, "./errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        } 
    }

    /**change value in column 'free_to_apply' based on the current application of the user
     * 
     * 
     * @param object $connection - connection to database
     * @param integer $id_lekce - id of the lesson
     * 
     * @return void
     */

     public static function getSumApplication($connection, $id_lekce) {

        $sql = "SELECT SUM(number_of_applications) AS total
                FROM prihlaseni
                WHERE id_lekce = :id_lekce";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    return $result['total'];
                } else {
                    return 0;
                }
            } else {
                throw new Exception("prihlaseni nelze zahrnout do vypisu lekci");
            }
        } catch(Exception $e) {
        error_log("chyba u funkce getSumApplication", 3, "./errors/error.log");
        echo "typ chyby:" . $e->getMessage();
        } 
     }

         /**change value in column 'free_to_apply' based on the current application of the user
     * 
     * 
     * @param object $connection - connection to database
     * @param integer $id_lekce - id of the lesson
     * 
     * @return void
     */

     public static function deleteApplication($connection, $id_lekce, $id_user) {
        $sql = "DELETE
                FROM prihlaseni
                WHERE id_lekce = :id_lekce AND id_lekce = :id_lekce";

        $stmt = $connection -> prepare($sql);

        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);
        $stmt -> bindValue(":id_user", $id_user, PDO::PARAM_INT);

        try {
            if ($stmt -> execute()) {
                return true;
            } else {
                throw new Exception("smazani prihlaseni na lekci se nepovedlo");
            }
        } catch(Exception $e) {
            error_log("chyba u funkce deleteApplication", 3, "../errors/error.log");
            echo "typ chyby:" . $e->getMessage();
        }
     }

     public static function getMailListOfAppliedUsers($connection,$id_lekce){

        require '../assets/globalVariables.php';

        $sql = "SELECT id_user
                    FROM prihlaseni
                    WHERE id_lekce = :id_lekce ";

// SELECT email, first_name, second_name 
// FROM user
// WHERE id_user IN (

        $stmt = $connection -> prepare($sql);
        echo var_dump($stmt);
        echo '<br>';
        $stmt -> bindValue(":id_lekce", $id_lekce, PDO::PARAM_INT);
        //$stmt -> bindValue(":id_user", $id_user, PDO::PARAM_INT);
        echo var_dump($stmt);
        echo '<br>';
        echo $id_lekce;
        echo '<br>';

        try {
            if ($stmt -> execute()) {
                $result = $stmt->fetchAll();
                echo var_dump($result);
                echo '<br>';
                if ($result) {
                    echo var_dump($result);
                    echo '<br>';
                    return $result;
                } else {
                    return false;
                }
            } else {
            throw new Exception("nelze ziskat mailist uzivatelu prihlasenych na lekci");
            }
        } catch(Exception $e) {
        error_log("chyba u funkce getMailListOfAppliedUsers", 3, $pathUrl . "/errors/error.log\n");
        echo "typ chyby:" . $e->getMessage();
        } 

     }

}

?>