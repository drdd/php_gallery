<?php

namespace classes;

class Comment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function addComment($image_id, $user_id, $comment) {
        $stmt = $this->db->prepare(/** @lang text */"INSERT INTO comments (image_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $image_id, $user_id, $comment);
        return $stmt->execute();
    }
    public function getCommentsByImageId($image_id) {
        $stmt = $this->db->prepare(/** @lang text */"SELECT * FROM comments WHERE image_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
