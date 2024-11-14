<?php
function render_template($tpl, $data) {
    ob_start();
    if (file_exists($tpl)) {
        extract($data);
        require($tpl);
    } else {
        print('шаблона ненайден: '.$tpl);
    }
    return ob_get_clean();
}

function getPictureWord($number) {
    $cases = [2, 0, 1, 1, 1, 2];
    $titles = ["картинка", "картинки", "картинок"];
    return $number . " " . $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
}

function generateUniqueFileName($originalFileName) {
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $dateTime = new DateTime();
    $timestamp = $dateTime->format('YmdHis');
    $uniqueFileName = $timestamp . '_' . session_id();
    if (!empty($extension)) {
        $uniqueFileName .= '.' . $extension;
    }
    return $uniqueFileName;
}

function createThumbnail($sourcePath, $destinationPath, $thumbSize = 255) {
    if (!file_exists($sourcePath)) {
        die("Файл не найден: " . $sourcePath);
    }
    if (!extension_loaded('gd')) {
        die("Расширение GD не загружено.");
    }

    list($width, $height, $type) = getimagesize($sourcePath);

    if ($width > $height) {
        $newHeight = $thumbSize;
        $newWidth = $thumbSize * ($width / $height);
    } else {
        $newWidth = $thumbSize;
        $newHeight = $thumbSize / ($width / $height);
    }

    $tempImage = imagecreatetruecolor($newWidth, $newHeight);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            throw new Exception("Расширение неподдерживается");
    }

    imagecopyresampled($tempImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    $thumbnail = imagecreatetruecolor($thumbSize, $thumbSize);

    imagecopyresampled($thumbnail, $tempImage, 0, 0, ($newWidth - $thumbSize) / 2, ($newHeight - $thumbSize) / 2, $thumbSize, $thumbSize, $thumbSize, $thumbSize);

    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumbnail, $destinationPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumbnail, $destinationPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumbnail, $destinationPath);
            break;
    }

    imagedestroy($sourceImage);
    imagedestroy($tempImage);
    imagedestroy($thumbnail);
}