<?php
require_once("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id'])) {
        $_SESSION['notification'] = "Вы должны быть зарегистрированы!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $name = sanitizeInput($_POST["name"]);
    $problem = sanitizeInput($_POST["problem"]);
    $phone = sanitizeInput($_POST["phone"]);

    if (empty($name) || empty($problem) || empty($phone)) {
        $_SESSION['notification'] = "Пожалуйста, заполните все поля!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    try {
        global $conn;
        $sql = "INSERT INTO cleaning_requests (name, problem, phone) VALUES (:name, :problem, :phone)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':problem', $problem, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['notification'] = "Заявка успешно отправлена!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();


    } catch (PDOException $e) {
        $_SESSION['notification'] = "Ошибка при отправке заявки: " . $e->getMessage();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

?>