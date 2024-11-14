<?php

namespace classes;

class Album {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createAlbum($title, $description, $user_id) {
        $stmt = $this->db->prepare(/** @lang text */"INSERT INTO albums (title, description, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description,$user_id);
        return $stmt->execute();
    }

    public function editAlbum($id, $title, $description) {
        $stmt = $this->db->prepare(/** @lang text */"UPDATE albums SET title = ?, description = ? WHERE album_id = ?");
        $stmt->bind_param("ssi", $title, $description, $id);
        return $stmt->execute();
    }

    public function getAlbumById($id) {
        $stmt = $this->db->prepare(/** @lang text */"SELECT album_id as id, title, description, user_id FROM albums WHERE album_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            die("Ошибка выполнения запроса: " . $this->db->error);
        }

        return $result->fetch_assoc();
    }

    public function getAlbums() {
        $query = "/** @lang text */
            SELECT a.album_id as id, a.user_id, a.title, a.description, COUNT(p.image_id) as image_count
            FROM albums a
            LEFT JOIN images p ON a.album_id = p.album_id
            GROUP BY a.album_id, a.title, a.description
            ";
        $result = $this->db->query($query);

        if ($result === false) {
            die("Ошибка выполнения запроса: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}