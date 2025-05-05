<?php
require_once("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = sanitizeInput($_POST["name"]);
    $surname = sanitizeInput($_POST["surname"]);
    $phone = sanitizeInput($_POST["phone"]);

    if (empty($name) || empty($surname) || empty($phone)) {
        $_SESSION['notification'] = "Пожалуйста, заполните все поля!";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    try {
        global $conn;
        $sql = "INSERT INTO volunteer_applications (name, surname, phone) VALUES (:name, :surname, :phone)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
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