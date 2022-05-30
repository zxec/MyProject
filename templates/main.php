<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="searched" value="<?= $_GET['searched'] ?? '' ?>" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="<?= isset($_GET['id']) ? 'id' : (isset($_GET['menu']) ? 'menu' : '') ?>" value="<?= isset($_GET['id']) ? $_GET['id'] : (isset($_GET['menu']) ? $_GET['menu'] : '') ?>">
        <input type="hidden" name="show_completed" value="1">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item <?= (!isset($_GET['menu'])) ? ' tasks-switch__item--active ' : '' ?>">Все задачи</a>
            <a href="/index.php?menu=agenda" class="tasks-switch__item <?= (isset($_GET['menu']) && $_GET['menu'] === 'agenda') ? ' tasks-switch__item--active ' : '' ?>">Повестка дня</a>
            <a href="/index.php?menu=tomorrow" class="tasks-switch__item <?= (isset($_GET['menu']) && $_GET['menu'] === 'tomorrow') ? ' tasks-switch__item--active ' : '' ?>">Завтра</a>
            <a href="/index.php?menu=overdue" class="tasks-switch__item <?= (isset($_GET['menu']) && $_GET['menu'] === 'overdue') ? ' tasks-switch__item--active ' : '' ?>">Просроченные</a>
        </nav>
        <label class="checkbox">
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?= (!empty($_GET['show_completed'])) ? ' checked ' : '' ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <?= ($error) ? '<span style="color:#ff0000; text-align:center;">' . $error . '</span>' : '' ?>

    <table class="tasks">
        <?php
        foreach ($tasks as $key => $value) {
            if (empty($_GET['show_completed']) && $value['completed']) {
                continue;
            }
        ?>
            <tr class="tasks__item task <?= ($value['completed'] == 1) ? ' task--completed ' : ((dateCheck("$value[date]")) ? ' task--important ' : '') ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox click" type="checkbox" name="task_completed" value="<?= $value['id'] ?>" <?= $value['completed'] ? ' checked ' : '' ?>>
                        <span class="checkbox__text"><?= $value['task_name'] ?></span>
                    </label>
                </td>
                <td class="task__file">
                    <?= ($value['file']) ? '<a class="download-link" href="' . $value['file'] . '">' . basename($value['file']) . '</a>' : '' ?>
                </td>
                <td class="task__date"><?= $value['date'] ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</main>

<script type="text/javascript">
    $(document).ready(function() {
        $('.click').click(function() {
            var task = $(this).val();
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    task_completed: task
                },
                success: function() {
                    location.reload();
                }
            })
        });
    });
</script>