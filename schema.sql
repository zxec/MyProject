CREATE DATABASE `ThingsAreFine` CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE `ThingsAreFine`;

CREATE TABLE `users`
(
`id` int NOT NULL AUTO_INCREMENT,
`user_name` char(32) NOT NULL,
`user_password` char(128) NOT NULL,
`user_email` varchar(320) NOT NULL UNIQUE,
`date` timestamp DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
);

CREATE TABLE `projects` 
(
`id` int NOT NULL AUTO_INCREMENT, 
`user_id` int NOT NULL, 
`project_name` char(128) NOT NULL, 
PRIMARY KEY (`id`),
FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
CREATE INDEX `user_id` ON projects(`user_id`);

CREATE TABLE `tasks` 
(
`id` int NOT NULL AUTO_INCREMENT,
`user_id` int NOT NULL, 
`project_id` int NOT NULL,
`task_name` char(128) NOT NULL,
`date_now` timestamp DEFAULT CURRENT_TIMESTAMP,
`date` date NULL,
`file` varchar(512) NULL,
`completed` BOOLEAN DEFAULT 0,
PRIMARY KEY (`id`),
FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
);
CREATE INDEX `user_id` ON `tasks`(`user_id`);
CREATE INDEX `project_id` ON `tasks`(`project_id`);
CREATE FULLTEXT INDEX `name` ON `tasks`(`task_name`);
