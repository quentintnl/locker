DROP TABLE IF EXISTS `Locker`;

CREATE TABLE `Locker` (
    `id` integer PRIMARY KEY AUTO_INCREMENT,
    `password` integer,
    `name` varchar(255),
    `status` bool,
    `created_at` timestamp
);