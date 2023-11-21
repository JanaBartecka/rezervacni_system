<?php

    require "../classes/Auth.php";
    require "../classes/Database.php";
    require "../classes/Lesson.php";
    require "../classes/User.php";
    require "../classes/Form.php";
    require '../assets/globalVariables.php';

    session_start();

    $database = new Database();
    $connection = $database -> connectionDB();

    $editLekce = false;

    if (!Auth::isLoggedIn()) {
        die("nepovoleny pristup");
    } else {
        $role = $_SESSION["role"];
        $id_user = $_SESSION["user_id"];

    }

    $name_lekce=null;
    $day=null;
    $time_start=null;
    $time_end=null;
    $time_apply_to='';
    $max_to_apply=8;

    $LessonSuccessAdd=false;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $time_apply_to = Form::setTimeApplyToDB($_POST["time_apply_to"]);

        if (isset($_POST['chbox_repeat_1week'])) {

            if(Lesson::createLesson($connection, 7, $_POST["day"], $_POST["endDay"], $_POST["time_start"], $_POST["time_end"], $_POST["name_lekce"], $_POST["max_to_apply"], $time_apply_to) >= 1) {
            
                $LessonSuccessAdd=true;
            }
        } else {
            
            if(Lesson::createLesson($connection, 0, $_POST["day"], $_POST["day"], $_POST["time_start"], $_POST["time_end"], $_POST["name_lekce"], $_POST["max_to_apply"], $time_apply_to) >= 1) {
            
                $LessonSuccessAdd=true;
            }
        }

        if ($LessonSuccessAdd) {
            $name_lekce=$_POST["name_lekce"];
            $day=$_POST["day"];
            $time_start=$_POST["time_start"];
            $time_end=$_POST["time_end"];
            $max_to_apply=$_POST["max_to_apply"];
        }



    }


?>



<!DOCTYPE html>

    <?php require "../assets/head.php"; ?>

<body>

    <?php require "../assets/header.php"; ?>

    <main class='main'>
        <h1 class='lessons__headline'>Přidat lekci</h1>
        <section class="add-form">
            <?php require "../assets/form-lesson.php"; ?>
            
            <?php if($LessonSuccessAdd === true) : ?>
                <p class='error-line'>Lekce byla úspěšně přidána</p>
            <?php endif ?>
        </section>
    </main>
    <footer></footer>

    <script src="<?= $pathUrl ?>/js/menu.js"></script>
</body>
</html>