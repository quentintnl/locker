DROP TABLE IF EXISTS `Locker`;

CREATE TABLE `Locker` (
    `id` integer PRIMARY KEY AUTO_INCREMENT,
    `password` varchar(255),
    `name` varchar(255),
    `closeOrOpen` bool,
    `created_at` timestamp
);
