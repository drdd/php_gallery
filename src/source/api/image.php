<?php
session_start();

include_once "../../db_conf.php";
/** @var object $conn */
include_once "../../helpers.php";

require_once '../classes/Image.php';
require_once '../classes/Comment.php';
require_once '../classes/Like.php';

use classes\Image;
use classes\Comment;
use classes\Like;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case "set_like":
            $like = new Like($conn);
            $lk = $like->getLike($_POST['image_id'],session_id());
            switch ($_POST['like']){
                case -1:
                    if ($lk == 0)
                        $like->addLike($_POST['image_id'],session_id(),false);
                    else
                        $like->editLike($_POST['image_id'],session_id(),false);
                    break;
                case 0:
                    $like->deleteLike($_POST['image_id'],session_id());
                    break;
                case 1:
                    if ($lk == 0)
                        $like->addLike($_POST['image_id'],session_id(),true);
                    else
                        $like->editLike($_POST['image_id'],session_id(),true);
                    break;
            }
            $image = new Image($conn);
            $img = $image->getImageById($_POST['image_id']);
            echo json_encode(['status' => 'success', 'like_count' => $img['like_count'], 'dislike_count' => $img['dislike_count']]);
            break;
        case "add_comment":
            $comment = new Comment($conn);
            $comment->addComment($_POST['image_id'], session_id(), $_POST['text']);
            $comments['comments'] = "";
            foreach ($comment->getCommentsByImageId($_POST['image_id']) as $cmt)
                $comments['comments'] .= render_template('../tpl/comment.php', $cmt);
            $comments['status'] = 'success';
            echo json_encode($comments);
            break;
        case "get_image":
            $image = new Image($conn);
            $comment = new Comment($conn);
            $like = new Like($conn);

            $img = $image->getImageById($_POST['id']);

            $img['like'] = $like->getLike($_POST['id'],session_id());
            $img['comments'] = "";
            foreach ($comment->getCommentsByImageId($_POST['id']) as $cmt)
                $img['comments'] .= render_template('../tpl/comment.php', $cmt);

            $img['status'] = 'success';
            echo json_encode($img);
            break;
        case "edit_image":
            $image = new Image($conn);
            $image->editImage($_POST['image_id'], $_POST['title'], $_POST['description']);
            echo json_encode(['status' => 'success']);
            break;
        case "add_image":
            if (isset($_FILES['img']) ) {
                $file_name = generateUniqueFileName($_FILES['img']['name']);
                if (move_uploaded_file($_FILES['img']['tmp_name'], '../img/' . $file_name)) {
                    $sourcePath = '../img/' . $file_name;
                    $destinationPath = '../thumbs/' . $file_name;
                    createThumbnail($sourcePath, $destinationPath);
                    $image = new Image($conn);
                    $image->addImage($_POST['album_id'], session_id(), $_POST['title'], $_POST['description'], $file_name);
                    echo json_encode(['status' => 'success']);
                } else
                    echo json_encode(['status' => 'error', 'message' => 'Файл не загружен']);

            }else
                echo json_encode(['status' => 'error', 'message' => 'Файл не загружен']);
            break;
        default:
            echo json_encode(["status"=>"error", "msg"=>"Нет данных"]);
            break;
    }

} else
    echo json_encode(['status' => 'error', 'message' => 'Невернаый метод']);
