<nav class="menu">
        <div class="menu__hamburger menu__hamburger--active">
            <span class="menu__hamburger--item menu__hamburger--upper"></span>
            <span class="menu__hamburger--item menu__hamburger--middle"></span>
            <span class="menu__hamburger--item menu__hamburger--lower"></span>
        </div>
        <ul class="menu__list menu__list--closed">
            <!-- general menu -->
            <li class="menu__item"><a class="menu__link" href="<?= $pathUrl ?>/">Úvod a seznam lekcí</a></li>
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