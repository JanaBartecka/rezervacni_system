<?php
    require "../classes/Auth.php";
    require "../classes/User.php";
    require '../assets/globalVariables.php';
    require "../classes/Database.php";
    
    session_start();

    $role='';
    
    if (Auth::isLoggedIn()) {
        $role = $_SESSION["role"];
        $id_user = $_SESSION["user_id"];

        $database = new Database();
        $connection = $database -> connectionDB();

        $columns = 'first_name, second_name';
        $userName = User::getUserPropertiesByID($connection, $id_user, $columns);
    }

?>


<!DOCTYPE html>

    <?php require "../assets/head.php"; ?>

<body>

    <?php require "../assets/header.php"; ?>

    <main class='main'>
        <?php if (isset($_GET['success'])): ?>
            <?php if ($_GET['success'] == 1) : ?>
                <p class='error-line'>Byl/a jste úspěšně zarigistrován/a.</p>
            <?php else :  ?>
                <p class='error-line'>Nebyl/a jste zaregistrována, váš e-mail již v naší databázi je zaregistrován. </p>
            <?php endif ?>
        <?php endif ?>
    </main>

    <footer>

    </footer>

    <script src='../js/menu.js'></script>
    <script src='../js/registration-form.js'></script>
</body>
</html>