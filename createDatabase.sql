DROP TABLE IF EXISTS `Password`;
DROP TABLE IF EXISTS `TryPassword`;
DROP TABLE IF EXISTS `Locker`;

CREATE TABLE `Password` (
    `id` integer PRIMARY KEY AUTO_INCREMENT,
    `pin` integer,
    `created_at` timestamp
);

CREATE TABLE `TryPassword` (
    `id` integer PRIMARY KEY AUTO_INCREMENT,
    `pin` integer,
    `created_at` timestamp
);

CREATE TABLE `Locker` (
    `id` integer PRIMARY KEY AUTO_INCREMENT,
    `password_id` integer,
    `name` varchar(255),
    `created_at` timestamp
);

ALTER TABLE `Locker` ADD FOREIGN KEY (`password_id`) REFERENCES `Password` (`id`);

SELECT * FROM Password