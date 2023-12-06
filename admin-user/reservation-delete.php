<?php

    require "../classes/LessonForUser.php";
    require "../classes/Auth.php";
    require '../assets/globalVariables.php';
    require "../classes/Database.php";
    require "../classes/Lesson.php";
    require "../classes/User.php";
    require "../classes/Date.php";
    require "../classes/Mail.php";
    require "../classes/Url.php";

    session_start();

    $role ='';

    $database = new Database();
    $connection = $database -> connectionDB();

    $id_lekce = $_GET['id_lekce'];
    
    if (Auth::isLoggedIn()) {
        $role = $_SESSION["role"];
        $id_user = $_SESSION['user_id'];

        $columns = 'first_name, second_name';
        $userName = User::getUserPropertiesByID($connection, $_SESSION["user_id"], $columns);

    } else {
        die("nepovoleny pristup");
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST" AND isset($_POST['deleteReservation'])) {

        $number_of_applications = LessonForUser::checkUserApplied($connection,$id_user ,$id_lekce);
        $lekce = Lesson::getLesson($connection, $id_lekce);

        if($number_of_applications === 1) {
            LessonForUser::deleteApplication($connection,$id_lekce,$id_user);
        } else {
            LessonForUser::changeApplicationToLesson($connection,$id_user,$id_lekce,--$number_of_applications);
        }

        // reassign value number_of_applications
        $number_of_applications=LessonForUser::checkUserApplied($connection,$id_user,$id_lekce);
        // find all apllications to specific lesson from all users
        $sumApplication=LessonForUser::getSumApplication($connection,$id_lekce);
        // change value free_to_apply in table 'lekce'
        Lesson::UpdateFreeToApply($connection, $lekce['max_to_apply']-$sumApplication, $id_lekce);
        $lekce = Lesson::getLesson($connection, $id_lekce);

        // send mail to user and organisation that the reservation was cancelled
        $columns = 'email,first_name, second_name';
        $mailList = Array(User::getUserPropertiesByID($connection, $id_user, $columns));
        $subject = 'Zrušení rezervace na lekci ' . $lekce['name_lekce'] . ' dne ' . Date::DateFromDBdate($lekce['day']);
        $message = 'Přihlášení na lekci ' . $lekce['name_lekce'] . ' konanou dne ' . Date::DateFromDBdate($lekce['day']) . ' od ' . Date::DateFromDBtimeStart($lekce['time_start']) . ' bylo zrušeno uživatelem ' . $mailList[0]['first_name'] . ' ' . $mailList[0]['second_name'] . "\nText zaslaný uživatelem:\n" . $_POST['messageByUser'];

        Mail::sendMail($mailList, $subject, $message);

        Url::redirectUrl("$pathUrl/lesson-item.php?id_lekce=$id_lekce");
    }

?>

<!DOCTYPE html>

    <?php require "../assets/head.php"; ?>

<body>
    <?php require "../assets/header.php"; ?>
    
    <main class='main'>
        <form class='registration-form' method="post">
            <textarea name="messageByUser" placeholder='Zpráva lektorce - text není nutné vyplnit.'></textarea>
            <input type="submit" name="deleteReservation" value="Zrušit přihlášení">
            <a class='button-link' href=" <?= $pathUrl ?>/lesson-item.php?id_lekce=<?= $id_lekce ?>">Zpět</a>
        </form>

    </main>

    <footer>

    </footer>

    <script src='../js/menu.js'></script>
    
</body>
</html>