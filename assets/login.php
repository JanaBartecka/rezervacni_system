
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
