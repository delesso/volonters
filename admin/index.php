<?php
session_start();

 if (!isset($_SESSION['admin'])) {
    header("Location: ../index.php");
    exit();
 }

require_once("../php/db.php");

try {
    global $conn;

    // Заявки на уборку
    $sql = "SELECT * FROM cleaning_requests ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $cleaningRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Заявки на волонтерство
    $sql = "SELECT * FROM volunteer_applications ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $volunteerApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    echo "Ошибка базы данных: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Админ-панель</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
    <link rel="stylesheet" href="../css/styles.css" />
</head>
<body>

<h1>Админ-панель</h1>

<h2>Заявки на уборку</h2>
<table>
    <thead>
        <tr>
            <th>Имя</th>
            <th>Проблема</th>
            <th>Телефон</th>
            <th>Дата</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cleaningRequests as $request): ?>
            <tr>
                <td><?php echo htmlspecialchars($request['name']); ?></td>
                <td><?php echo htmlspecialchars($request['problem']); ?></td>
                <td><?php echo htmlspecialchars($request['phone']); ?></td>
                <td><?php echo htmlspecialchars($request['created_at']); ?></td>
                <td><?php
                    if ($request['status'] == 'new') {
                        echo 'Новая';
                    } elseif ($request['status'] == 'in_progress') {
                        echo 'В процессе';
                    }elseif ($request['status'] == 'completed') {
                        echo 'Выполнено';
                    }else{
                        echo 'Статус не найден';
                    }
                    ?>
                </td>
                <td>
                    <a href="edit_cleaning_request.php?id=<?php echo $request['id']; ?>">Изменить</a>
                    <a href="delete_cleaning_request.php?id=<?php echo $request['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить эту заявку?')">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Заявки на волонтерство</h2>
<table>
    <thead>
        <tr>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Телефон</th>
            <th>Дата</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($volunteerApplications as $application): ?>
            <tr>
                <td><?php echo htmlspecialchars($application['name']); ?></td>
                <td><?php echo htmlspecialchars($application['surname']); ?></td>
                <td><?php echo htmlspecialchars($application['phone']); ?></td>
                <td><?php echo htmlspecialchars($application['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h1>Создание новости</h1>
    <form action="create_news.php" method="post" enctype="multipart/form-data">
        <label for="title">Заголовок:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="subtitle">Краткое описание:</label><br>
        <textarea id="subtitle" name="subtitle" rows="4" cols="50"></textarea><br><br>

        <label for="content">Текст новости:</label><br>
        <textarea id="content" name="content" rows="10" cols="50" required></textarea><br><br>

        <label for="image">Изображение:</label><br>
        <input type="file" id="image" name="image"><br><br>

        <input type="submit" value="Создать новость">
    </form>
            <a href="../index.php">Выйти</a>
</body>
</html>