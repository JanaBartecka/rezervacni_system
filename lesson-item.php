<?php

    require "./classes/Database.php";
    require "./classes/Lesson.php";
    require "./classes/LessonForUser.php";
    require "./classes/Auth.php";
    require "./classes/User.php";
    require "./classes/Date.php";
    require './assets/globalVariables.php';

    $database = new Database();
    $connection = $database -> connectionDB();

    $role ='';
    $userMustLogIn=false;
    $userIsApplied=false;

    session_start();

    if (is_numeric($_GET['id_lekce']) AND isset($_GET['id_lekce'])) {
        
        $lekce = Lesson::getLesson($connection, $_GET['id_lekce']);
        
        if (Auth::isLoggedIn()) {
            $userLoggedIn=true;

            $number_of_applications = LessonForUser::checkUserApplied($connection,$_SESSION["user_id"],$_GET['id_lekce']);

            $role = $_SESSION["role"];
            $id_user = $_SESSION["user_id"];

            $columns = 'first_name, second_name';
            $userName = User::getUserPropertiesByID($connection, $id_user, $columns);

        } else {
            $userLoggedIn=false;
        }

    } else {
        $one_lekce=null;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" AND isset($_POST['deleteReservation'])) {
        if($number_of_applications === 1) {
            LessonForUser::deleteApplication($connection,$_GET['id_lekce'],$id_user);
        } else {
            LessonForUser::changeApplicationToLesson($connection,$id_user,$_GET['id_lekce'],--$number_of_applications);
        }

        // reassign value number_of_applications
        $number_of_applications=LessonForUser::checkUserApplied($connection,$id_user,$_GET['id_lekce']);
        // find all apllications to specific lesson from all users
        $sumApplication=LessonForUser::getSumApplication($connection,$_GET['id_lekce']);
        // change value free_to_apply in table 'lekce'
        Lesson::UpdateFreeToApply($connection, $lekce['max_to_apply']-$sumApplication, $_GET['id_lekce']);
        $lekce = Lesson::getLesson($connection, $_GET['id_lekce']);
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" AND isset($_POST['createReservation'])) {

        $userMustLogIn=false;
        $userIsApplied=false;

        if (!Auth::isLoggedIn()) {
            $userMustLogIn=true;
        } else {
            if ($number_of_applications >= 1) {
                $userIsApplied = true;
            } else {
                try {
                    // final application to lesson
                    if (LessonForUser::createApplicationToLesson($connection,$_SESSION["user_id"],$_GET['id_lekce'])) {
                    // reassign value number_of_applications
                    $number_of_applications=LessonForUser::checkUserApplied($connection,$_SESSION["user_id"],$_GET['id_lekce']);
                    // find all apllications to specific lesson from all users
                    $sumApplication=LessonForUser::getSumApplication($connection,$_GET['id_lekce']);
                    // change value free_to_apply in table 'lekce'
                    Lesson::UpdateFreeToApply($connection, $lekce['max_to_apply']-$sumApplication, $_GET['id_lekce']);
                    $lekce = Lesson::getLesson($connection, $_GET['id_lekce']);
                    } else {
                        throw new Exception("nelze vytvorit dalsi rezervaci na lekci");
                    } 
                } catch (Exception $e) {
                        error_log("chyba pri vytvoreni rezervace", 3, "./errors/error.log");
                        echo "typ chyby:" . $e->getMessage();
                } 
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" AND isset($_POST['createOtherReservation'])) {

        $userMustLogIn=false;
        $userIsApplied=false; 

        try {
            // final application to lesson
            if (LessonForUser::changeApplicationToLesson($connection,$_SESSION["user_id"],$_GET['id_lekce'],++$number_of_applications)) {
            // reassign value number_of_applications
            $number_of_applications=LessonForUser::checkUserApplied($connection,$_SESSION["user_id"],$_GET['id_lekce']);
            // find all apllications to specific lesson from all users
            $sumApplication=LessonForUser::getSumApplication($connection,$_GET['id_lekce']);
            // change value free_to_apply in table 'lekce'
            Lesson::UpdateFreeToApply($connection, $lekce['max_to_apply']-$sumApplication, $_GET['id_lekce']);
            $lekce = Lesson::getLesson($connection, $_GET['id_lekce']);
            } else {
                throw new Exception("nelze vytvorit dalsi rezervaci na lekci");
            } 
        } catch (Exception $e) {
                error_log("chyba pri vytvoreni dalsi rezervace", 3, "./errors/error.log");
                echo "typ chyby:" . $e->getMessage();
        } 

    }
    
?>


<!DOCTYPE html>

    <?php require "./assets/head.php"; ?>

<body>
    <?php require "./assets/header.php"; ?>

    <main class='main'>
        <h1 class='lessons__headline'>Informace o lekci</h1>
        <section class='lessons-item'>
            <!-- List of information about lesson or not found info -->
            <?php if($lekce === null): ?>
                <p class='error-line'>Lekce nenalezena</p>
            <!-- lesson IS FOUND -->
            <?php else: ?>
                <p class='lessons__name'><?=  htmlspecialchars($lekce["name_lekce"]) ?></p>
                <p class='lessons__time'>Čas konání: <?= Date::DateFromDBdate($lekce["day"]) ?> od <?= Date::DateFromDBtimeStart($lekce["time_start"]) ?> do <?= Date::DateFromDBtimeEnd($lekce["time_end"]) ?></p>
                <p >Počet volných míst: <?= htmlspecialchars($lekce["free_to_apply"])?></p>
            
                <!-- information about user´s reservation to this specific lesson if user IS APPLIED-->
                <?php if ($userLoggedIn) : ?>
                    <?php if ($number_of_applications ===0) : ?>
                        <p>Na tuto lekci nemáte rezervaci</p>
                    <?php else: ?>
                        <p>Na tuto lekci máte <?= $number_of_applications ?> přihlášení</p>
                        <!-- delete reservation to lesson -->
                        <?php if (Date::DateFromDBlessonStart($lekce['day'], $lekce['time_start']) > Date:: DateFromDBapplyTo($lekce['day'], $lekce['time_start'], $lekce['time_apply_to'])) : ?>
                            <form method="POST">
                                <input type="submit" name="deleteReservation" value="Zrušit přihlášení">
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- allow to apply if time limit is not crossed -->
                <?php if (Date::DateFromDBlessonStart($lekce['day'], $lekce['time_start']) > Date:: DateFromDBapplyTo($lekce['day'], $lekce['time_start'], $lekce['time_apply_to'])) : ?>
                    <!-- possibility to APPLY to lesson if there are free places -->
                    <?php if($lekce['free_to_apply'] > 0 AND isset($lekce['free_to_apply'])) : ?>
                        <!-- reservation of the lesson -->
                        <form method="POST">
                            <input type="submit" name="createReservation" value="Rezervovat">
                            <!--  info that user must LOG IN if want to apply to lesson-->
                            <?php if ($userMustLogIn): ?>
                                <p class='error-line'>Pro rezervování lekce je nutné se nejprve přihlásit</p>
                            <?php endif; ?>
                            <!-- user is ALREADY APPLIED to the lesson -->
                            <?php if ($userIsApplied): ?>
                                <p class='error-line'>Na tuto lekci již jste přihlášeni. Chcete opravdu vytvořit další rezervaci?</p>
                                <form method="post">
                                    <input type="submit" name="createOtherReservation" value="Ano">
                                </form>
                            <?php endif; ?>
                        </form>
                    <?php else: ?>
                        <p class='error-line'>Na tuto lekci již neni možné se přihlásit, kapacita byla naplněna.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class='error-line'>Vypršel časový limit pro přihlášení/odhlášení</p>
                <?php endif ?>
                        
                <!-- admin buttons to delete and edit lesson -->
                <?php if($role === 'admin'): ?>
                    <section class="lessons__buttons">
                        <a class='button-link lessons__edit' href="./admin-lektor/lesson-edit.php?id_lekce=<?= $lekce['id_lekce']?>">Editovat</a>
                        <a class='button-link lessons__delete' href="./admin-lektor/lesson-delete.php?id_lekce=<?= $lekce['id_lekce']?>">Smazat</a>
                    </section>
                <?php endif; ?>

                <a class='button-link' href=" <?= $pathUrl ?>/index.php">Zpět na seznam lekcí</a>
                
            <?php endif; ?>

        </section>

    </main>

    <footer>
        <script src='./js/menu.js'></script>
    </footer>

</body>

</html>