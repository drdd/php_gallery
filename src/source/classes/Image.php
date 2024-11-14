<?php

namespace classes;

class Image {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addImage($album_id, $user_id, $title, $description, $file_path) {
        $stmt = $this->db->prepare(/** @lang text */"INSERT INTO images (album_id, user_id, title, description, file_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $album_id, $user_id, $title, $description, $file_path);
        return $stmt->execute();
    }

    public function editImage($image_id, $title, $description) {
        $stmt = $this->db->prepare(/** @lang text */"UPDATE images SET title = ?, description = ? WHERE image_id = ?");
        $stmt->bind_param("ssi", $title, $description, $image_id);
        return $stmt->execute();
    }

    public function getImageById($image_id) {
        $stmt = $this->db->prepare("/** @lang text */
            SELECT i.*, 
            (SELECT COUNT(*) FROM likes WHERE image_id = i.image_id AND is_like = 1) AS like_count,
            (SELECT COUNT(*) FROM likes WHERE image_id = i.image_id AND is_like = 0) AS dislike_count,
            (SELECT COUNT(*) FROM comments WHERE image_id = i.image_id) AS comment_count
            FROM images i
            WHERE i.image_id = ?
            ");
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function getImagesByAlbumId($album_id) {
        $stmt = $this->db->prepare("/** @lang text */
            SELECT i.*, 
            (SELECT COUNT(*) FROM likes WHERE image_id = i.image_id AND is_like = 1) AS like_count,
            (SELECT COUNT(*) FROM likes WHERE image_id = i.image_id AND is_like = 0) AS dislike_count,
            (SELECT COUNT(*) FROM comments WHERE image_id = i.image_id) AS comment_count
            FROM images i
            WHERE i.album_id = ?
            ");
        $stmt->bind_param("i", $album_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}