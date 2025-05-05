<?php
require_once("db.php");

try {
    global $conn;
    $sql = "SELECT * FROM news ORDER BY created_at DESC LIMIT 6, 1000";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = '';
    foreach ($news as $item) {
        $output .= '<div class="news__card-item">';
        $output .= '<img src="' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['title']) . '">';
        $output .= '<div class="news__card-title">' . htmlspecialchars($item['title']) . '</div>';
        $output .= '<div class="news__card-subtitle">' . htmlspecialchars($item['subtitle']) . '</div>';
        $output .= '</div>';
    }
    echo $output;
} catch (PDOException $e) {
    echo "Ошибка базы данных: " . $e->getMessage();
    die();
}
?>