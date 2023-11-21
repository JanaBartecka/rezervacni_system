<?php
    require "../classes/Auth.php";
    require "../classes/Database.php";
    require "../classes/Lesson.php";
    require "../classes/User.php";
    require '../assets/globalVariables.php';

    session_start();

    $role ='';

    $database = new Database();
    $connection = $database -> connectionDB();
    
    if (Auth::isLoggedIn()) {
        $role = $_SESSION["role"];
        $id_user = $_SESSION['user_id'];

        $columns = 'first_name, second_name';
        $userName = User::getUserPropertiesByID($connection, $_SESSION["user_id"], $columns);

    } else {
        die("nepovoleny pristup");
    }

    $lessonsList = Lesson::getLessonByUser($connection,$id_user);

?>

<!DOCTYPE html>

    <?php require "../assets/head.php"; ?>

<body>

    <?php require "../assets/header.php"; ?>

    <main class='main'>
        <ul class='list'>
            <?php if($lessonsList) : ?>
                <?php foreach($lessonsList as $oneLesson): ?>
                    <li class='list__item'>
                        <span><?= htmlspecialchars($oneLesson['name_lekce']) ?></span>
                        <span><?= $oneLesson['time_start'] ?></span>
                        <span><?= htmlspecialchars($oneLesson['number_of_applications']) ?> přihlášení</span>
                        <div class="list__buttons">
                            <a class='button-link list__button' href="<?= $pathUrl ?>/lesson-item.php?id_lekce=<?= $oneLesson['id_lekce'] ?>"> Více informací</a>
                        </div>
                    </li>
                <?php endforeach ?>
            <?php else: ?>
                    <p class='error-line'>Nejste přihlášen/přihlášena na žádnou lekci.</p>
                
                
            <?php endif ?>
        </ul>
    </main>

    <footer>
        
    </footer>

    <script src="../js/menu.js"></script>
    
</body>
</html>