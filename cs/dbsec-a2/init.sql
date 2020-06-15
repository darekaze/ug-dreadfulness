CREATE SCHEMA IF NOT EXISTS dbsa2;
USE dbsa2;
SET block_encryption_mode = 'aes-256-cbc';
SET @init_vector = RANDOM_BYTES(16);

CREATE TABLE IF NOT EXISTS Users (
    `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_name` VARCHAR(100) NOT NULL UNIQUE,
    `user_password` VARCHAR(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS Notes (
    `note_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `note_title` VARCHAR(100) NOT NULL,
    `note_content` TEXT,
    `user_id` INT UNSIGNED NOT NULL,
    `create_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `is_encrypted` TINYINT UNSIGNED NOT NULL DEFAULT 0,
    FOREIGN KEY (`user_id`) REFERENCES Users(`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;

---
-- Sample user and data

INSERT INTO `users` VALUES
(1,'dardar','$2y$10$CJOnFDf1XOhFurrSXgjeYuR3imUfQZEp2WEgZycysRfB6drTumlqK'),
(2,'bbb','$2y$10$186zBOv..Ytu3zF/fxhXtu.0hm2HTSJ1ArlaKbX63WQ8kaF4/l.HC'),
(3,'regular','$2y$10$eWlK5c2dU9xUWCU15hwTye5rRwrwNseAoGIyQaWEGss3EOGdOQypG');

INSERT INTO `notes` VALUES
(1,'asdasdasd','asdasdasdasdasd',1,'2020-04-12 13:44:02',0),
(2,'sdasdasdasdasd','£\nÖ½ÇH‚$k³ñšH',1,'2020-04-12 13:44:13',1),
(3,'bbbbbb','0C?zécëU§|³b%y›W',1,'2020-04-12 15:57:56',1),
(4,'My name','My name is bbb',2,'2020-04-12 16:16:28',0),
(5,'xxx','¾¢;c´Ä¾ËÝoDû†Ÿì',2,'2020-04-12 16:16:41',1),
(6,'Hi there','This is just some sample content.',3,'2020-04-12 17:10:10',0),
(7,'This is secret!!! (pw: 12345)','TÃ²XîäæTw¡Žl°a¡¶Ü‚*½™A^YÄu9x\0$‰g«S¯ÃAHmú^srÙ©TÔÓìç@ ™Œ$TWî',3,'2020-04-12 17:10:37',1);
