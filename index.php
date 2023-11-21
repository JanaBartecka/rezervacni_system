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
        <h1 class='main__headline'>Rezervační systém MC Žirafa</h1>
<p>test</p>
        <?php require "./assets/lessons-list.php"; ?>

    </main>

    <footer>

    </footer>

    <script src='./js/menu.js'></script>
    
</body>
</html>