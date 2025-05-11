<?php
session_start();

require_once("../php/db.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    die("Неверный ID заявки.");
}

try {
    global $conn;

    // Получаем данные заявки
    $sql = "SELECT * FROM cleaning_requests WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        die("Заявка не найдена.");
    }

    // Обработка отправки формы
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Проверяем статус
        $status = $_POST["status"];
        // Здесь можно добавить проверку допустимых значений статуса
        if (!in_array($status, ['new', 'in_progress', 'completed'])) {
            die("Недопустимый статус.");
        }

        $sql = "UPDATE cleaning_requests SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            header("Location: ../index.php");
            exit();
        } else {
            die("Ошибка обновления статуса.");
        }
    }

} catch (PDOException $e) {
    echo "Ошибка базы данных: " . htmlspecialchars($e->getMessage());
    die();
}
?>