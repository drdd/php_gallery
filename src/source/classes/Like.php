<?php

namespace classes;

class Like {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    public function addLike($image_id, $user_id, $is_like) {
        $stmt = $this->db->prepare(/** @lang text */"INSERT INTO likes (image_id, user_id, is_like) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $image_id, $user_id, $is_like);
        return $stmt->execute();
    }

    public function deleteLike($image_id, $user_id) {
        $stmt = $this->db->prepare(/** @lang text */"DELETE FROM likes WHERE image_id = ? AND user_id = ?");
        $stmt->bind_param("is", $image_id, $user_id);
        return $stmt->execute();
    }

    public function editLike($image_id, $user_id, $is_like) {
        $stmt = $this->db->prepare(/** @lang text */"UPDATE likes SET is_like = ? WHERE image_id = ? AND user_id = ?");
        $stmt->bind_param("iis", $is_like, $image_id, $user_id);
        return $stmt->execute();
    }

    public function getLike($image_id, $user_id) {
        $stmt = $this->db->prepare(/** @lang text */"SELECT is_like FROM likes WHERE image_id = ? AND user_id = ?");
        $stmt->bind_param("is", $image_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if(isset($row))
            return $row['is_like']?1:-1;
        return 0;
    }
}