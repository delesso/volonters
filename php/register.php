<?php
require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {

    $name = sanitizeInput($_POST["name"]);
    $email = sanitizeInput($_POST["email"]);
    $password = $_POST["password"];
    $repeatpassword = $_POST["repeatpassword"];

    if (empty($name) || empty($email) || empty($password) || empty($repeatpassword)) {
        echo "Пожалуйста, заполните все поля.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Некорректный формат email.";
    } elseif ($password !== $repeatpassword) {
        echo "Пароли не совпадают.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($conn !== null) {
            try {
                $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $stmt_check->execute([$email]);
                $result_check = $stmt_check->fetch();

                if ($result_check) {
                } else {

                    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $email, $hashed_password]);

                    header("Location: ../index.php");
                    exit();
                }
            } catch(PDOException $e) {
                echo "Ошибка при регистрации: " . $e->getMessage();
            }
        } else {
            echo "Ошибка: Не удалось подключиться к базе данных.";
        }
    }
}
