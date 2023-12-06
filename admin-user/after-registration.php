<?php

    require "../classes/User.php";
    require "../classes/Database.php";
    require "../classes/Url.php";
    require "../assets/globalVariables.php";
    require "../classes/Mail.php";

    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        //$connection=connectionDB();
        $database = new Database();
        $connection=$database->connectionDB();

        $first_name = $_POST["first_name"];
        $second_name = $_POST["second_name"];
        $email = $_POST["email"];
        $phone_number = $_POST["phone_number"];
        $user_type = "student";

        // check if user is already registered in database
        if (!User::getUserID($connection, $email)) {
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $id_user = User::createUser($connection, $first_name, $second_name, $email, $phone_number, $user_type, $password);
    
            if(!empty($id_user)) {
                //restrain from fixation attack
                session_regenerate_id(true);
    
                //user is logged in
                $_SESSION["is_logged_in"] = true;
                //save user ID
                $_SESSION["user_id"] = $id_user;
                // set the role of the user
                $_SESSION["role"] = User::getUserPropertiesByID($connection, $id_user, 'user_type')['user_type'];

                // send mail to registered user
                $mailList = array(array('email' => $email,
                                 'first_name' => $first_name,
                                 'second_name' => $second_name));
                $subject = 'Registrace do systému MC Žirafa';
                $message = 'Uživatel ' . $first_name . ' ' . $second_name . ' byl registrován do rezervačního systému MC Žirafa. K přihlášení použijte e-mail ' . $email . ' a vámi zadané heslo.';
                
                Mail::sendMail($mailList, $subject, $message, false);
    
                Url::redirectUrl("$pathUrl/admin-user/user-details.php?success=1");


            } else {
                echo "uzivatele se nepodarilo pridat";
            }
        } else {
            Url::redirectUrl("$pathUrl/admin-user/user-details.php?success=0");
        }


    }
