DROP TABLE IF EXISTS `Raspberry`;
DROP TABLE IF EXISTS `Locker`;
DROP TABLE IF EXISTS `Ip`;

CREATE TABLE Raspberry (
                           id INT PRIMARY KEY AUTO_INCREMENT,
                           ip VARCHAR(255) NOT NULL
);

CREATE TABLE Locker (
                        id INT PRIMARY KEY AUTO_INCREMENT,
                        name VARCHAR(255) NOT NULL,
                        password VARCHAR(255) NOT NULL,
                        pin INT NOT NULL,
                        close_or_open BOOLEAN NOT NULL,
                        ip_id INT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (ip_id) REFERENCES Raspberry(id)
);

INSERT INTO `Raspberry`(`id`, `ip`)
VALUES ('1','192.168.47.95'),
       ('2','192.169.4.50');

INSERT INTO `Locker`(`name`, `password`, `pin`, `close_or_open`, `ip_id`)
VALUES  ('Ben','$2y$10$VZgm5eA7DLgQBlzY8b0rm.EI1V7ZGLMQqA/i0WKdrujmaN7Y49tSq','27','1','1'),
        ('Bob','$2y$10$8ZpkYfi7blv5zxO5vk9dXOk0Iw8rLWkJHvUogG4bOCopE18.GUfGK','17','1','1');
