<?php
    
    if (Auth::isLoggedIn()) {
        $columns = 'first_name, second_name, email, phone_number';
        $userName = User::getUserPropertiesByID($connection, $id_user, $columns);
    }
?>

<header>

    <section class='login'>
        <img class='login__icon login__icon--closed' src="<?= $pathUrl ?>/images/user-icon.svg" alt="logo přihlášení">
        <?php require 'login.php' ?> 
    </section>

    <?php require 'menu.php' ?> 

</header>