<?php
require_once '../classes/Album.php';
use classes\Album;

session_start();
include "../../db_conf.php";
/** @var object $conn */

$json = file_get_contents('php://input');

// Декодируем JSON в объект
$data = json_decode($json);

if (isset($data->action)){
    switch ($data->action) {
        case "add_album":
            if(empty($data->title) || empty($data->description)){
                http_response_code(200);
                echo json_encode(["msg"=>"Заполните поля"]);
            }else {
                $album = new Album($conn);
                $album->createAlbum($data->title, $data->description, session_id());
                http_response_code(201);
            }
            break;
        case "get_album":
            if(empty($data->id)){
                http_response_code(204);
                echo json_encode(["msg"=>"Нет данных"]);
            }else {
                $album = new Album($conn);
                $row = $album->getAlbumById($data->id);
                if (empty($row)) {
                    echo json_encode(["status"=>"204", "msg"=>"Нет данных"]);
                    exit();
                }
                if ($row['user_id'] != session_id()) {
                    echo json_encode(["status"=>"403", "msg"=>"Нет прав доступа к контенту"]);
                }else {
                    http_response_code(200);
                    $row['status'] = "200";
                    echo json_encode($row);
                }
            }
            break;
        case "edit_album":
            if(empty($data->title) || empty($data->description) || empty($data->id)){
                echo json_encode(["msg"=>"Заполните поля", "status"=>"204"]);
            }else {
                $album = new Album($conn);
                $album->editAlbum($data->id, $data->title, $data->description);
                http_response_code(201);
            }
            break;
        default:
            echo json_encode(["status"=>"204", "msg"=>"Нет данных"]);
            break;
    }
}else
    echo "нет данных";

exit();



$user_id = session_id();
$title = "Название альбома";
$description = "Описание альбома";

$sql = /** @lang text */"INSERT INTO albums (user_id, title, description) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $user_id, $title, $description);

if ($stmt->execute()) {
    echo "Новый альбом успешно добавлен.";
} else {
    echo "Ошибка: " . $stmt->error;
}

// Закрываем соединение
$stmt->close();
$conn->close();
