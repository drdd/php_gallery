<?php

include "db_conf.php";
/** @var object $conn */
/** @var string $dbname */

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_GET['rm'])) {
    $result = $conn->query("SHOW DATABASES LIKE '$dbname'");
    if ($result->num_rows > 0) {
        $conn->query("DROP DATABASE $dbname");
        $conn->query("CREATE DATABASE $dbname");
        echo "База данных очищена";
    } else {
        echo "База данных не существует.";
    }
}else {

#   -- Таблица для хранения информации об альбомах
    $albums = /** @lang text */
        "CREATE TABLE albums (
            album_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id VARCHAR(32),
            title VARCHAR(255) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";
    $conn->query($albums);
#   -- Таблица для хранения информации об изображениях
    $images = /** @lang text */
        "CREATE TABLE images (
            image_id INT AUTO_INCREMENT PRIMARY KEY,
            album_id INT,
            user_id VARCHAR(32),
            title VARCHAR(255) NOT NULL,
            description TEXT,
            file_path VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (album_id) REFERENCES albums(album_id) ON DELETE CASCADE
        );";
    $conn->query($images);
#   -- Таблица для хранения информации о лайках и дизлайках
    $likes =/** @lang text */
        "CREATE TABLE likes (
            like_id INT AUTO_INCREMENT PRIMARY KEY,
            image_id INT,
            user_id VARCHAR(32),
            is_like BOOLEAN,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (image_id) REFERENCES images(image_id) ON DELETE CASCADE
        );";
    $conn->query($likes);
#   -- Таблица для хранения комментариев к изображениям
    $comments= /** @lang text */
        "CREATE TABLE comments (
            comment_id INT AUTO_INCREMENT PRIMARY KEY,
            image_id INT,
            user_id VARCHAR(32),
            comment TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (image_id) REFERENCES images(image_id) ON DELETE CASCADE
        );";
    $conn->query($comments);

    $engine = /** @lang text */
        "ALTER TABLE images ENGINE=InnoDB;
         ALTER TABLE likes ENGINE=InnoDB;
         ALTER TABLE comments ENGINE=InnoDB;";
    $conn->query($engine);
}
$conn->close();