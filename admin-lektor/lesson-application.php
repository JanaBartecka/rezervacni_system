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

    }

    $appliedUsers = Lesson::getUsersByLesson($connection, $_GET['id_lekce']);
    $free_to_apply = Lesson::getLesson($connection, $_GET['id_lekce'], 'free_to_apply')['free_to_apply'];
?>

<!DOCTYPE html>

    <?php require "../assets/head.php"; ?>

<body>
    <?php require "../assets/header.php"; ?>
    
    <main class='main'>
        <?php if($appliedUsers): ?>
            <ul class='list'>
                <?php foreach($appliedUsers as $oneUser): ?>
                    <li class='list__item'>
                        <span><?= htmlspecialchars($oneUser['first_name']) ?></span>
                        <span><?= htmlspecialchars($oneUser['second_name']) ?></span>
                        <span><?= htmlspecialchars($oneUser['phone_number']) ?></span>
                        <span><?= htmlspecialchars($oneUser['email']) ?></span>
                        <span><?= htmlspecialchars($oneUser['number_of_applications']) ?> přihlášení</span>                        
                    </li>
                    <!-- delete applied user -->
                    <a class='button-link lessons__delete' href="<?= $pathUrl ?>/admin-lektor/delete-application.php?id_lekce=<?= $_GET['id_lekce'] ?>&id_user=<?= $oneUser['id_user']?>"> Smazat přihlášeného</a>
                <?php endforeach ?>
            </ul>
        <?php else: ?>
            <p class='error-line'>Nebyli nalezeni žádní přihlášení na lekci.</p>
        <?php endif ?>
        <!-- add user to apply -->
        <?php if($free_to_apply >= 0): ?>
            <a class='button-link' href="<?= $pathUrl ?>/admin-lektor/users-all.php?userToApply=1&id_lekce=<?= $_GET['id_lekce']?>" > Přiřadit registrovaného uživatele</a>
        <?php else: ?>
            <p class='error-line'>Kapacita naplněna</p>
        <?php endif ?>
        <a class='button-link' href=" <?= $pathUrl ?>/index.php">Zpět na seznam lekcí</a>

    </main>

    <footer>
        <script src="../js/menu.js"></script>
    </footer>
    
</body>
</html>