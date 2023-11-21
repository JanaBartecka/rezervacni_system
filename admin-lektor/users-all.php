<?php

    require "../classes/Database.php";
    require "../classes/Lesson.php";
    require "../classes/User.php";
    require "../classes/Auth.php";
    require '../assets/globalVariables.php';

    session_start();

    $database = new Database();
    $connection = $database -> connectionDB();

    $role='';
    if (!Auth::isLoggedIn()) {
        die("nepovoleny pristup");
    } else {
        $role = $_SESSION["role"];
        $id_user = $_SESSION["user_id"];
        if ($role === 'student') {
            die("nepovoleny pristup");
        } 
    }

    $columns = 'id_user,first_name, second_name, phone_number, email';
    $allUsers = User::getAllUsers($connection, $columns);

?>

<!DOCTYPE html>

    <?php require "../assets/head.php"; ?>

<body>

    <?php require "../assets/header.php"; ?>
    <main class='main'>
        <input type="text" name="list__search" placeholder='Vyhledávání'>
        <ul class='list'>
            <?php if($allUsers) : ?>
                <?php foreach ($allUsers as $user): ?>
                    <li class='list__item'>
                        <span class='list__item--firstName'><?= htmlspecialchars($user['first_name'])?></span>
                        <span class='list__item--secondName'><?= htmlspecialchars($user['second_name'])?></span>
                        <span class='list__item--phone'><?= htmlspecialchars($user['phone_number'])?></span>
                        <span class='list__item--email'><?= htmlspecialchars($user['email'])?></span>

                        <!-- this block appears only when this page is iniciated from  'lesson-application' in order to apply user-->
                        <?php if (isset($_GET['userToApply'])): ?>
                            <a class='button-link' href="<?= $pathUrl ?>/admin-lektor/user-apply.php?id_lekce=<?= $_GET['id_lekce']?>&id_user=<?= $user['id_user']?>"> Přihlásit uživatele</a>
                        <?php endif ?>

                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class='error-line'>Nelze vypsat údaje o registrovaných uživatelích</p>
            <?php endif ?>
        </ul>
    </main>

    <footer>
        <script src="../js/menu.js"></script>
        <script src="../js/listSearch.js"></script>
    </footer>
    
</body>
</html>