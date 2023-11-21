<?php
    
    require "../classes/Database.php";
    require "../classes/Lesson.php";
    require "../classes/LessonForUser.php";
    require "../classes/Url.php";
    require "../classes/Auth.php";
    require "../classes/User.php";
    require "../classes/Date.php";
    require "../classes/Mail.php";
    require '../assets/globalVariables.php';

    session_start();
    
    $database = new Database();
    $connection = $database -> connectionDB();

    if (!Auth::isLoggedIn()) {
        die("nepovoleny pristup");
    } else {
        $role = $_SESSION["role"];
        $id_user = $_SESSION["user_id"];
    }

    if($role !== 'admin') {
        die("nepovoleny pristup");
    }

    $columns = 'name_lekce, day, time_start';
    $lekce = Lesson::getLesson($connection, $_GET["id_lekce"], $columns);

    if($_SERVER["REQUEST_METHOD"] === "POST" AND $role === 'admin') {
        // maillist of recepients
        $mailList = LessonForUser::getMailListOfAppliedUsers($connection, intval($_GET["id_lekce"]));

        if(Lesson::deleteLesson($connection, $_GET["id_lekce"])) {
            
            if (isset($_POST['deleteReservation'])) {
                
                $message = $_POST['messageLessonDeleted'];
                $subject = $lekce['name_lekce'] . 'v' . $lekce['day'] . 'zrusen';
                
                if($mailList) {
                    Mail::sendMail($mailList, $subject, $message);
                } else {
                    echo 'email nebylo mozne odeslat';
                }
            }

            Url::redirectUrl($pathUrl . "/index.php");
        }
    }

?>

<!DOCTYPE html>

    <?php require "../assets/head.php"; ?>

<body>
    <?php require "../assets/header.php"?>
    <main class='main'>
        <section>
            <p class='error-line'>Chcete lekci <?= $lekce['name_lekce']?> konanou dne <?= Date::DateFromDBdate($lekce['day']) ?> od <?= Date::DateFromDBtimeStart($lekce['time_start']) ?> opravdu smazat?</p>
            <form method="POST">
                <textarea name="messageLessonDeleted" >Omlouváme se, ale lekce <?= $lekce['name_lekce']?>, konaná dne <?= Date::DateFromDBdate($lekce['day']) ?> v <?= Date::DateFromDBtimeStart($lekce['time_start']) ?> hodin je z důvodu nemoci zrušena.</textarea>
                <input type="submit" name="deleteReservation" value="Smazat a odeslat mail přihlášeným"> 
                <a class='button-link' href="../lesson-item.php?id_lekce= <?= $_GET['id_lekce']?>">Zrušit</a>
            </form>
        </section>
    </main>

    <footer>
        <script src="../js/menu.js"></script>
    </footer>
</body>
</html>