<?php

    require "./classes/Lesson.php";
    require "./classes/Characters.php";
    require './assets/globalVariables.php';

    $database = new Database();
    $connection = $database -> connectionDB();
    
    $role='';
    if (Auth::isLoggedIn()) {
        $role = $_SESSION["role"];
    }

    $lessonsPerPage = 12;

    if (isset($_GET['page'])) {
        $currentPage = $_GET['page'];
        $offset = $currentPage * $lessonsPerPage;
    } else {
        $offset = 0;
        $currentPage = 0;
    } 

    $filters = Lesson::getLessonsFilters($connection, $lessonsPerPage, abs($offset));

    if ($_SERVER["REQUEST_METHOD"] === "POST" AND isset($_POST['filter'])) {
        if (isset($_POST['chbox']) AND !empty([$_POST['chbox']])) {
            $filteredItems="('" . implode("','", $_POST['chbox']) . "')";
            $filteredItemsArray=$_POST['chbox'];
        } else {
            $filteredItems="('" . implode("','", $filters) . "')";
            $filteredItemsArray=[];
        }
        
    } else {
        if(!empty($filters)){
            $filteredItems="('" . implode("','", $filters) . "')";
            $filteredItemsArray=[];
        } else {
            $filteredItems=[];
        }
    }

    if ($currentPage >= 0 AND !empty($filteredItems)) {
        $lekce = Lesson::getFutureLessonsPerPage($connection, $lessonsPerPage, $offset, $filteredItems);
    } else if ($currentPage < 0 AND !empty($filteredItems)) {
        $lekce = Lesson::getPastLessonsPerPage($connection, $lessonsPerPage, abs($offset), $filteredItems);
    } else if (empty($filteredItems)) {
        $lekce=[];
    }

?>

        <section class='lessons'>
            <h1 class='lessons__headline'>Seznam lekcí</h1>

            <!-- filters -->
            <div >
                <form class="lessons__filters" method='POST'>
                    <?php foreach($filters as $filter): ?>
                        <div class="lessons__filter">
                            <?php $chboxName=Characters::removeDiacritics($filter) ?>
                            <input type="checkbox" id="<?= $chboxName ?>" name="chbox[]" value="<?= $chboxName ?>" <?= (in_array($chboxName,$filteredItemsArray) ? 'checked' : '') ?>>
                            <label for="<?= $chboxName ?>"><?= $filter ?></label>

                        </div>
                    <?php endforeach ?>
                    <?php if(!empty($filters)) : ?>
                        <input type="submit" name='filter' value="Filtrovat">
                    <?php endif ?>
                </form>
            </div>
            
            <?php if(empty($lekce)): ?>
                <p class='error-line'>Nejsou vypsány žádné lekce</p>
                <div class="lessons__pagination">
                    <?php if ($currentPage >= 0): ?>
                        <a class='button-link' href="<?= $pathUrl?>/index.php?page=<?= $currentPage-1 ?>">Předchozí</a>
                    <?php else: ?>
                        <a class='button-link' href="<?= $pathUrl?>/index.php?page=<?= $currentPage+1 ?>">Další</a>
                    <?php endif ?>
                </div>
            <?php else: ?>

                <!-- pagination -->
                <div class="lessons__pagination">
                    <a class='button-link' href="<?= $pathUrl?>/index.php?page=<?= $currentPage-1 ?>">Předchozí</a>
                    <a class='button-link' href="<?= $pathUrl?>/index.php?page=0">Nyní</a>
                    <a class='button-link' href="<?= $pathUrl?>/index.php?page=<?= $currentPage+1 ?>">Další</a>
                </div>

                <ul class='lessons__list'>
                    <?php foreach($lekce as $one_lekce): ?>
                        <?php $date = new DateTime($one_lekce["day"]);
                        $dateFormat = $date->format('j\.n\.');
                        $timeStart = new DateTime($one_lekce["time_start"]);
                        $timeStartFormat = $timeStart->format('H:i');
                        $timeEnd = new DateTime($one_lekce["time_end"]);
                        $timeEndFormat = $timeEnd->format('H:i');

                        $lessonStart = new DateTime( $one_lekce['day'] . $one_lekce['time_start']);
                        $nowMinusTimeToApply = new DateTime('now');
                        $nowMinusTimeToApply->modify($one_lekce['time_apply_to']);
                    ?>

                        <li class='lessons__item'>
                            <a class='lessons__link' href="lesson-item.php?id_lekce=<?= $one_lekce['id_lekce'] ?>">
                                <span class='lessons__date'><?= $dateFormat ?></span> 
                                <span class="lessons__time"><?= $timeStartFormat ?> - <?= $timeEndFormat ?></span> 
                                <span class="lessons__name"><?= htmlspecialchars($one_lekce["name_lekce"]) ?></span>
                                <span class="lessons__application"><?= $one_lekce["free_to_apply"]?>/<?= $one_lekce["max_to_apply"]?></span>
                            
                            <?php if($role === 'admin') : ?>
                                <div class="lessons__buttons">
                                    <a class='lessons__button lessons__edit' href="<?= $pathUrl ?>/admin-lektor/lesson-edit.php?id_lekce=<?= $one_lekce['id_lekce'] ?>"> Editovat lekci</a>
                                    <a class='lessons__button lessons__delete' href="<?= $pathUrl ?>/admin-lektor/lesson-delete.php?id_lekce=<?= $one_lekce['id_lekce'] ?>"> Smazat lekci</a>
                                    <a class='lessons__button lessons__applied' href="<?= $pathUrl ?>/admin-lektor/lesson-application.php?id_lekce=<?= $one_lekce['id_lekce'] ?>"> Seznam přihlášených</a>
                                </div>
                            <?php endif ?>
                            </a>
                        </li>
                    <?php endforeach ?>

                </ul>
            <?php endif; ?>

        </section>
