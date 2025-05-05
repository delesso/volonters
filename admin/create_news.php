<?php
session_start();

require_once("../php/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = sanitizeInput($_POST["title"]);
    $subtitle = sanitizeInput($_POST["subtitle"]);
    $content = $_POST["content"];
    $image = null;
    $upload_error = null;

    // Обработка загрузки изображения
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["image"]["name"];
        $filetype = $_FILES["image"]["type"];
        $filesize = $_FILES["image"]["size"];

        // Проверяем расширение файла
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $upload_error = "Ошибка: Выберите файл правильного формата.";
        }

        // Проверяем размер файла - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) {
            $upload_error = "Ошибка: Размер файла больше 5MB.";
        }

        // Проверяем MIME-тип файла
        if (empty($upload_error) && in_array($filetype, $allowed)) {
            // Проверяем, существует ли файл перед загрузкой
            if (file_exists("../image/" . $filename)) {
                $upload_error = $filename . " уже существует.";
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], "../image/" . $filename)) {
                    $image = "./image/" . $filename; // Сохраняем путь к изображению
                } else {
                    $upload_error = "Ошибка: Не удалось загрузить файл.";
                }
            }
        } else {
            if (empty($upload_error)) {
                $upload_error = "Ошибка: Выберите файл правильного формата.";
            }
        }
    }

    try {
        global $conn;
        $sql = "INSERT INTO news (title, subtitle, image, content) VALUES (:title, :subtitle, :image, :content)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':subtitle', $subtitle, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR); 
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['message'] = "Новость успешно создана!"; 
        header("Location: index.php"); 
        exit();

    } catch (PDOException $e) {
        $_SESSION['message'] = "Ошибка базы данных: " . $e->getMessage(); 
        header("Location: index.php");
        exit();
    }

    if ($upload_error) {
      $_SESSION['message'] = $upload_error;
      header("Location: index.php");
      exit();
    }

} else {
    header("Location: index.php");
    exit();
}

?>