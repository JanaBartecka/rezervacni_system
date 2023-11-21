<?php
    require "../classes/Database.php";
    require "../classes/Lesson.php";
    require "../classes/LessonForUser.php";
    require '../assets/globalVariables.php';
    require '../classes/Url.php';
    require "../classes/Auth.php";

    session_start();

    $database = new Database();
    $connection = $database -> connectionDB();

    $role='';
    if (Auth::isLoggedIn()) {
        $role = $_SESSION["role"];
    } else {
        die("nepovoleny pristup");
    }

    $id_user = $_GET['id_user'];
    $id_lekce = $_GET['id_lekce'];

    // find if user is already applied
    $number_of_applications=LessonForUser::checkUserApplied($connection,$id_user,$id_lekce);

    if($number_of_applications >= 1) {
        LessonForUser::changeApplicationToLesson($connection,$id_user,$id_lekce,++$number_of_applications);
    } else {
        LessonForUser::createApplicationToLesson($connection,$id_user,$id_lekce);
    }

    // find all apllications to specific lesson from all users
    $sumApplication=LessonForUser::getSumApplication($connection,$id_lekce);
    // get value from column max_to_apply to refresh free_to_apply in next step
    $max_to_apply = Lesson::getLesson($connection, $id_lekce, 'max_to_apply')['max_to_apply'];
    // change value free_to_apply in table 'lekce'
    Lesson::UpdateFreeToApply($connection, $max_to_apply - $sumApplication, $id_lekce);

    Url::redirectUrl($pathUrl . '/admin-lektor/lesson-application.php?id_lekce=' . $id_lekce);
