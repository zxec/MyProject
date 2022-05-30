<main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form" action="add_project.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input <?= (isset($errors['name'])) ? ' form__input--error ' : '' ?>" type="text" name="name" id="project_name" value="<?= $_POST['name'] ?? '' ?>" placeholder="Введите название проекта">
            <?= (isset($errors['name'])) ? '<p class="form__message">' . $errors['name'] . '</p>' : '' ?>
        </div>

        <div class="form__row form__row--controls">
            <?= (!empty($errors)) ? '<p class="error-message">Пожалуйста, исправьте ошибки в форме</p>' : '' ?>
            <input class="button" type="submit" name="add_project" value="Добавить">
        </div>
    </form>
</main>