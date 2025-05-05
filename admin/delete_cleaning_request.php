<?php
session_start();

require_once("../php/db.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Sanitize the ID

if ($id <= 0) {
    die("Неверный ID заявки.");
}

try {
    global $conn;

    // Удаляем заявку
    $sql = "DELETE FROM cleaning_requests WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: index.php");
    exit();


} catch (PDOException $e) {
    echo "Ошибка базы данных: " . $e->getMessage();
    die();
}
?>