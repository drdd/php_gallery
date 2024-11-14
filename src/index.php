<?php
session_start();
?>
<!doctype html>
<html lang="ru" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Альбомы">
    <title>Галирея</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="source/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="source/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="theme-color" content="#7952b3">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        html {
            height: 100%;
        }
        body {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
        .wrapper {
            min-height: 100%;
            height: auto !important;
        }
        .footer {
            margin: -30px 0 0 0;
            height: 100px;
            position: relative;
        }
    </style>
</head>
<body>
<header></header>
<main class="h-100 wrapper">
<?php
include_once "helpers.php";

include "db_conf.php";
/** @var object $conn */


if (isset($_GET['album'])){
    include_once "source/tpl/album.php";
}else{
    include_once "source/tpl/main.php";
}

$conn->close();
?>
</main>
<footer class="footer text-muted py-5">
    <div class="container">
        <p class="mb-1">Тестовое задание для управления проектирования информационных технологий
            КУП "Центр информационных технологий Мингорисполкома"</p>
    </div>
</footer>
</body>
</html>
