<?php
session_start();

require_once("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {

    $email = sanitizeInput($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $login_error = "Пожалуйста, заполните все поля.";
        $_SESSION['login_error'] = $login_error;
    } else {

        try {

            global $conn;

      
            $sql = "SELECT id, name, email, role, password FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);


                if ($result) {

                   if (password_verify($password, $result["password"])) {
                        $_SESSION["email"] = $result["email"];
                        $_SESSION["id"] = $result["id"];

                        if (isset($result["name"])) { // Check if name exists
                            $_SESSION["name"] = $result["name"];
                        }

                        if ($result["role"] === 'admin') {
                            $_SESSION["admin"] = true;
                        }
                        unset($_SESSION['login_error']);

                        if (headers_sent()) {
                            die('Headers already sent. Cannot redirect.');
                        } else {
                            header("Location: ../index.php");
                            exit();
                        }

                    } else {
                        $login_error = "Неверный пароль.";
                        $_SESSION['login_error'] = $login_error;
                    }
                } else {
                    $login_error = "Пользователь с таким email не найден.";
                    $_SESSION['login_error'] = $login_error;
                }


            } else {
                $login_error = "Ошибка базы данных: Ошибка при подготовке запроса.";
                $_SESSION['login_error'] = $login_error;
            }

        } catch (PDOException $e) {
            $login_error = "Ошибка базы данных: " . $e->getMessage();
            $_SESSION['login_error'] = $login_error;
        }


    }
}

if (isset($_SESSION['login_error'])) {
    if (headers_sent()) {
        die('Headers already sent. Cannot redirect.');
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>