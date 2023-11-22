<?php
    
    if (Auth::isLoggedIn()) {
        $columns = 'first_name, second_name, email, phone_number';
        $userName = User::getUserPropertiesByID($connection, $id_user, $columns);
    }
?>

<header>
    <section class='login'>
        <img class='login__icon login__icon--closed' src="<?= $pathUrl ?>/images/user-icon.svg" alt="logo přihlášení">
    <?php if (!Auth::isLoggedIn()) :?>
        <div class="login__section login__section--closed">
            <h2 class='login__headline'>Přihlášení</h2>
            <form class='login__form' method="POST" action="./admin-user/log-in.php">
                <input type="email" name="login_email" placeholder="E-mail">
                <input type="password" name="login_password" placeholder="Heslo">
                <input type="submit" value="Přihlásit se">
            </form>
            <p class='login__registration'>Pokud nejste zaregistrovaní, můžete se 
            <a class='button-link' href="<?= $pathUrl ?>/registration-form.php">zaregistrovat&nbspZDE</a>.</p>
        </div>
    <?php else: ?>
        <p class='login__userName'><?= $userName['first_name'] ?> <?= $userName['second_name'] ?></p>

        <div class='login_userDetails login_userDetails--closed'>
            <p>Jméno:<?= $userName['first_name']?></p>
            <p>Přijmení:<?= $userName['second_name']?></p>
            <p>E-mail:<?= $userName['email']?></p>
            <p>Telefonní číslo:<?= $userName['phone_number']?></p>
        </div>
    <?php endif; ?>
    </section>

    <nav class="menu">
        <div class="menu__hamburger menu__hamburger--active">
            <span class="menu__hamburger--item menu__hamburger--upper"></span>
            <span class="menu__hamburger--item menu__hamburger--middle"></span>
            <span class="menu__hamburger--item menu__hamburger--lower"></span>
        </div>
        <ul class="menu__list menu__list--closed">
            <!-- general menu -->
            <li class="menu__item"><a class="menu__link" href="<?= $pathUrl ?>">Úvod a seznam lekcí</a></li>
            <!-- menu for admin -->
            <?php if ($role === 'admin') : ?>
                <li class="menu__item"><a class="menu__link" href="<?= $pathUrl ?>/admin-lektor/lesson-add.php">Přidat lekci</a></li>
                <li class="menu__item"><a class="menu__link" href="<?= $pathUrl ?>/admin-lektor/users-all.php">Seznam všech registrovaných</a></li>
            <?php endif; ?>
            <!-- menu for LOGGED IN user -->
            <?php if (Auth::isLoggedIn()) :?>
                <li class="menu__item"><a class="menu__link" href="<?= $pathUrl ?>/admin-user/lessons-my.php">Mé lekce</a></li>
                <li class="menu__item"><a class="menu__link" href="<?= $pathUrl ?>/admin-user/log-out.php">Odhlásit</a></li>
            <?php endif; ?>
        </ul>
    </nav>

</header>