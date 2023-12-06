<form class='lessons-form-full' method="POST">
    <!-- name of the lesson -->
    <input type="text" name="name_lekce" placeholder="Název lekce" value="<?= htmlspecialchars($name_lekce)?>" required>
    <!-- day -->
    <label type='text' for="day">Den konání:
        <input type="date" name="day" value="<?= htmlspecialchars($day)?>" required>
    </label>
    <?php if(!$editLekce) : ?>
        <label for="chbox_repeat_1week">Opakování každý týden
            <input type="checkbox" name="chbox_repeat_1week">
        </label>
        <label type='text' for="endDay">Opakování do (nepovinné):
            <input type="date" name="endDay">
        </label>
    <?php endif ?>
    <!-- od -->
    <label type='text' for="time_start">Začátek:
        <input type="time" name="time_start" value="<?= htmlspecialchars($time_start)?>" required>
    </label>
    <!-- do -->
    <label type='text' for="time_end">Konec:
        <input type="time" name="time_end" value="<?= htmlspecialchars($time_end)?>" required>
    </label>
    <!-- prihlaseni do -->
    <label type='text' for="time_apply_to">Ukončit přihlášení:
        <select id="time_apply_to" name="time_apply_to" size="1">
            <option value="min30" <?=$time_apply_to === 'min30' ? ' selected="selected"' : '';?>>30 minut</option>
            <option value="hour1" <?=$time_apply_to === 'hour1' ? ' selected="selected"' : '';?>>1 hodina</option>
            <option value="hour2" <?=$time_apply_to === 'hour2' ? ' selected="selected"' : '';?>>2 hodiny</option>
            <option value="hour6" <?=$time_apply_to === 'hour6' ? ' selected="selected"' : '';?>>6 hodin</option>
            <option value="hour12" <?=$time_apply_to === 'hour12' ? ' selected="selected"' : '';?>>12 hodin</option>
            <option value="hour24" <?=$time_apply_to === 'hour24' ? ' selected="selected"' : '';?>>24 hodin</option>
        </select>
        předem
    </label>
    <!-- maximalni pocet prihlasenych -->
    <label type='text' for="max_to_apply">Maximální počet účastníků:
        <input type="number" name="max_to_apply" min="0" max="20" value="<?= htmlspecialchars($max_to_apply)?>" required>
    </label>
    

    <?php if($editLekce) : ?>
        <textarea name="message" placeholder='Zde napište text mailu, který přijde již přihlášeným. Pokud mail odeslat nechcete, stačí kliknout na tlačítko Změnit lekci, mail se pak neodešle.'></textarea>
        <input type="submit" name="editLesson" value="Změnit lekci">
        <input type="submit" name="editLessonWithEmail" value="Změnit lekci a poslat mail">
    <?php else : ?>
        <input type="submit" name="createLesson" value="Přidat lekci">
    <?php endif ?>
    <a class='button-link' href="<?= $pathUrl ?>/index.php">Zpět na seznam lekcí</a>
    
</form>