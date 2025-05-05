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
        $status = $_POST["status"];

        $sql = "UPDATE cleaning_requests SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: index.php");
        exit();
    }


} catch (PDOException $e) {
    echo "Ошибка базы данных: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Редактирование заявки на уборку</title>
</head>
<body>

<h1>Редактирование заявки на уборку</h1>

<form method="post">
    <label for="status">Статус:</label>
    <select name="status" id="status">
        <option value="new" <?php echo ($request['status'] == 'new') ? 'selected' : ''; ?>>Новая</option>
        <option value="in_progress" <?php echo ($request['status'] == 'in_progress') ? 'selected' : ''; ?>>В процессе</option>
        <option value="completed" <?php echo ($request['status'] == 'completed') ? 'selected' : ''; ?>>Выполнена</option>
    </select>
    <br><br>
    <button type="submit">Сохранить</button>
</form>

<a href="index.php">Вернуться в админ-панель</a>

</body>
</html>