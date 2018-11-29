CREATE TABLE IF NOT EXISTS `users` (
    `id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `level` TINYINT UNSIGNED NOT NULL
) ENGINE = InnoDB CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `menus` (
    `food_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `food_name` VARCHAR(100) NOT NULL,
    `food_category` VARCHAR(100) NOT NULL,
    `food_image_url` VARCHAR(255),
    `food_description` VARCHAR(255) NOT NULL,
    `food_price` INT(11) NOT NULL,
    `food_available` TINYINT UNSIGNED NOT NULL
) ENGINE = InnoDB CHARSET=utf8;
