<?php

    require './assets/globalVariables.php';
    require "./classes/Auth.php";

    $role='';
    

?>

<!DOCTYPE html>

    <?php require "./assets/head.php"; ?>

<body>

    <?php require "./assets/header.php"; ?>

    <main class='main'>
        <section class="registration-form">
            <h1 class='registration-form__headline' >Registrační formulář</h1>
            <form class='registration-form__form' action="./admin-user/after-registration.php" method="POST">
                <input type="text" name="first_name" placeholder="Křestní jméno" required>
                <input type="text" name="second_name" placeholder="Přijmení" required>
                <input type="email" name="email" placeholder="E-mail" required>
                <input type="tel" name="phone_number" placeholder="Telefonní číslo" required>
                <input type="password" name="password" placeholder="Heslo" required>
                <input type="password" name="password_check" placeholder="Heslo znovu" required>
                <p class='registration-form__passwordConfirm'></p>
                <input class='registration-form__submit' type="submit" value="Zaregistrovat">
            </form>
        </section>
    </main>

    <footer>

    </footer>

    <script src='./js/menu.js'></script>
    <script src='./js/registration-form.js'></script>
</body>
</html>