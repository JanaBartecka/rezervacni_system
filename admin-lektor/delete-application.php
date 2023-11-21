<?php

    require "../classes/Database.php";
    require "../classes/Lesson.php";
    require "../classes/LessonForUser.php";
    require '../assets/globalVariables.php';
    require '../classes/Url.php';
    require "../classes/Auth.php";
    require "../classes/Mail.php";
    require "../classes/Date.php";
    require "../classes/User.php";

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

    $number_of_applications = LessonForUser::checkUserApplied($connection,$id_user,$id_lekce);

    $lekce = Lesson::getLesson($connection, $_GET['id_lekce']);

    if($number_of_applications > 1) {
        
        LessonForUser::changeApplicationToLesson($connection,$id_user,$id_lekce,--$number_of_applications);
        // find all apllications to specific lesson from all users
        $sumApplication=LessonForUser::getSumApplication($connection,$id_lekce);
        $lekce = Lesson::getLesson($connection, $id_lekce, 'max_to_apply');
        // change value free_to_apply in table 'lekce'
        Lesson::UpdateFreeToApply($connection, $lekce['max_to_apply']-$sumApplication, $id_lekce);

    } else {
        // delete user in 'prihlaseni' table
        LessonForUser::deleteApplication($connection,$id_lekce,$id_user);
        // reassign value number_of_applications
        $number_of_applications=LessonForUser::checkUserApplied($connection,$id_user,$id_lekce);
        // find all apllications to specific lesson from all users
        $sumApplication=LessonForUser::getSumApplication($connection,$id_lekce);
        // change value free_to_apply in table 'lekce'
        Lesson::UpdateFreeToApply($connection, $lekce['max_to_apply']-$sumApplication, $id_lekce);

    }

    $mailList = User::getUserPropertiesByID($connection, $id_user, 'email')['email'];
    $subject = 'Zrušení lekce ' . $lekce['name_lekce'] . ' konané dne ' . Date::DateFromDBdate($lekce['day']) . ' od ' . Date::DateFromDBtimeStart($lekce['time_start']) . '.';
    $message = 'Vaše přihlášení na lekci ' . $lekce['name_lekce'] . ' konanou dne ' . Date::DateFromDBdate($lekce['day']) . ' od ' . Date::DateFromDBtimeStart($lekce['time_start']) . ' bylo zrušeno administrátorem.';
    echo $mailList;
    echo '<br>';
    echo $subject;
    echo '<br>';
    echo $message;
    Mail::sendMail($mailList, $subject, $message);

    Url::redirectUrl($pathUrl . '/admin-lektor/lesson-application.php?id_lekce=' . $id_lekce);
