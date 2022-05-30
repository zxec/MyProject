<main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?= (isset($errors['name'])) ? ' form__input--error ' : '' ?>" type="text" name="name" id="name" value="<?= $_POST['name'] ?? '' ?>" placeholder="Введите название">
            <?= (isset($errors['name'])) ? '<p class="form__message">' . $errors['name'] . '</p>' : '' ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?= (isset($errors['project'])) ? ' form__input--error ' : '' ?>" name="project" id="project">
                <?php
                foreach ($projects as $key => $value) {
                ?>
                    <option <?= (isset($_POST['project']) && $_POST['project'] === $value['id']) ? ' selected ' : '' ?> value="<?= $value['id'] ?>"><?= $value['project_name'] ?></option>
                <?php
                }
                ?>
            </select>
            <?= (isset($errors['project'])) ? '<p class="form__message">' . $errors['project'] . '</p>' : '' ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date <?= (isset($errors['date'])) ? ' form__input--error ' : '' ?>" type="text" name="date" id="date" value="<?= $_POST['date'] ?? '' ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <?= (isset($errors['date'])) ? '<p class="form__message">' . $errors['date'] . '</p>' : '' ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="file" id="file" value="<?= $_POST['file'] ?? '' ?>">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <?= (!empty($errors)) ? '<p class="error-message">Пожалуйста, исправьте ошибки в форме</p>' : '' ?>
            <input class="button" type="submit" name="addTask" value="Добавить">
        </div>
    </form>
</main>