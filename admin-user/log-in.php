<?php

    require "../classes/Database.php";
    require "../classes/User.php";
    require "../classes/Url.php";

    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $database = new Database();
        $connection = $database -> connectionDB();
        $login_email = $_POST["login_email"];
        $login_password = $_POST["login_password"];

        if(User::authenticationUser($connection, $login_email, $login_password)) {
            //get user ID
            $id_user = User::getUserID($connection, $login_email);
            //restrain from fixation attack
            session_regenerate_id(true);

            //user is logged in
            $_SESSION["is_logged_in"] = true;
            //save user ID
            $_SESSION["user_id"] = $id_user;
            // set the role of the user
            $_SESSION["role"] = User::getUserPropertiesByID($connection, $id_user, 'user_type')['user_type'];

            Url::redirectUrl("/rezervacni_system/index.php");

        } else {
            $error = "Chyba při přihlášení";
        }

    }

?>

<!DOCTYPE html>

    <?php require "../assets/head.php"; ?>

<body>
    <?php if(!empty($error)): ?>
        <p><?= $error ?></p>
        <a href="../index.php">Zpět na přihlášení</a>
    <?php endif; ?>
</body>
</html>