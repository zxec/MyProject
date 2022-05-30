INSERT INTO `users` (`user_name`, `user_password`, `user_email`) VALUES -- добавление пользователей в БД
('Константин', MD5('123'), 'qwert@mail.ru'),
('Алексей', MD5('321'), 'trewq@mail.ru');

INSERT INTO `projects` (`user_id`, `project_name`) VALUES -- добавление проектов в БД
(1, 'Входящие'),
(1, 'Учеба'),
(1, 'Работа'),
(1, 'Домашние дела'),
(1, 'Авто');

INSERT INTO `tasks` (`user_id`, `project_id`, `task_name`, `date`, `completed`) VALUES -- добавление задач в БД
(1, 1, 'Встреча с другом', '2022-04-05', DEFAULT),
(1, 2, 'Сделать задание первого раздела', '2019-12-21', 1),
(1, 3, 'Собеседование в IT компании', '2022-12-01', DEFAULT),
(1, 3, 'Выполнить тестовое задание', '2022-04-07', DEFAULT),
(1, 4, 'Купить корм для кота', DEFAULT, DEFAULT),
(1, 4, 'Заказать пиццу', DEFAULT, DEFAULT);

SELECT `user_name` FROM `users`; -- получение имени пользователя
SELECT `project_name` FROM `projects`; -- получение всех проектов для вывода проектов
SELECT `task_name`, `date`, `completed` FROM `tasks`; -- получение информации о задачах для вывода задач

SELECT `project_name` FROM `projects` WHERE `user_id` = 1; -- получить список из всех проектов для одного пользователя
SELECT `task_name` FROM `tasks` WHERE `id` = 1; -- получить список из всех задач для одного проекта
UPDATE `tasks` SET `completed` = 1 WHERE `id` = 1; -- пометить задачу как выполненную
UPDATE `tasks` SET `task_name` = 'Название' WHERE `id` = 1; -- обновить название задачи по её идентификатору