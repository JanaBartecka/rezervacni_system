<?php

    require "../classes/Database.php";
    require "../classes/Lesson.php";
    require "../classes/LessonForUser.php";
    require "../classes/Url.php";
    require "../classes/User.php";
    require "../classes/Auth.php";
    require "../classes/Mail.php";
    require "../classes/Form.php";
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

    $editLekce = false;

    if (is_numeric($_GET['id_lekce']) AND isset($_GET['id_lekce'])) {
        
        $one_lekce=Lesson::getLesson($connection, $_GET['id_lekce']);

        $editLekce = true;

        if ($one_lekce) {
            $name_lekce=$one_lekce["name_lekce"];
            $day=$one_lekce["day"];
            $time_start=$one_lekce["time_start"];
            $time_end=$one_lekce["time_end"];
            $time_apply_to=Form::getTimeApplyToFromDB($one_lekce["time_apply_to"]);
            $max_to_apply=$one_lekce["max_to_apply"];
            $id_lekce=$one_lekce["id_lekce"];
        } else {
            die("Lekce nenalezena");
        }
//tady nutno upravit
    } else {
        die("lekce nenalezena");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $name_lekce=$_POST["name_lekce"];
        $day=$_POST["day"];
        $time_start=$_POST["time_start"];
        $time_end=$_POST["time_end"];
        $time_apply_to = Form::setTimeApplyToDB($_POST["time_apply_to"]);
        
        if ($one_lekce['max_to_apply'] - $one_lekce['free_to_apply'] < $_POST["max_to_apply"]) {
            $max_to_apply=$_POST["max_to_apply"];
        } else {
            $max_to_apply=$one_lekce['max_to_apply'];
        }
        
        $message = $_POST['message'];
        $subject = 'Probehla zmena v lekci ' . $name_lekce . ' konane dne ' . $day . ' od ' . $time_start . '.';

        if(Lesson::UpdateLesson($connection, $name_lekce, $day, $time_start, $time_end, $time_apply_to, $max_to_apply, $id_lekce)) {

            if (isset($_POST['editLessonWithEmail'])) {
                if($mailList = LessonForUser::getMailListOfAppliedUsers($connection, $id_lekce)) {
                    Mail::sendMail($mailList, $subject, $message);
                } else {
                    echo 'email nebylo mozne odeslat';
                }
            }

            // update free_to_apply column 
            // find all apllications to specific lesson from all users
            $sumApplication=LessonForUser::getSumApplication($connection,$_GET['id_lekce']);
            // change value free_to_apply in table 'lekce'
            Lesson::UpdateFreeToApply($connection, $max_to_apply-$sumApplication, $_GET['id_lekce']);

            Url::redirectUrl("$pathUrl/lesson-item.php?id_lekce=$id_lekce");
        }
    }

?>


<!DOCTYPE html>
    <?php require "../assets/head.php"; ?>
<body>
    <?php require "../assets/header.php"; ?>

    <main class='main'>
        <section class="add-form">
            <?php require "../assets/form-lesson.php"; ?>
        </section>
    </main>

    <footer>
        <script src="../js/menu.js"></script>
    </footer>
</body>
</html>