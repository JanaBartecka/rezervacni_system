<?php

    require "./classes/Auth.php";
    require './assets/globalVariables.php';
    require './classes/User.php';
    require "./classes/Database.php";

    session_start();

    $role='';

    if (Auth::isLoggedIn()) {
        $role = $_SESSION["role"];
        $id_user = $_SESSION["user_id"];

        $database = new Database();
        $connection = $database -> connectionDB();

    }
    

?>

<!DOCTYPE html>

    <?php require "./assets/head.php"; ?>

<body>

    <?php require "./assets/header.php"; ?>

    <main class='main'>
        <p class='p__line2'>V případě problému s naším rezervačním systémem nás prosím kontaktujte na telefonním čísle <a class='button-link' href="tel:+420775 029 590">775 029 590</a> nebo napište na e-mail <a class='button-link' href="mailto:info@mc-zirafa.cz">info@mc-zirafa.cz</a>.</p>
    </main>

    <footer>

    </footer>

    <script src='./js/menu.js'></script>
</body>
</html>