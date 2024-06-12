<?php
session_start();
require_once '../config/connection.php';
require_once '../models/functions.php';

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: ../index.php?page=home');
    exit;
}

function authenticate($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $current_time = time();
        $lock_time = 30;
        $attempt_limit = 3;
        $attempt_window = 300;

        if ($user['failed_login_attempts'] >= $attempt_limit && ($current_time - strtotime($user['last_failed_login'])) <= $attempt_window) {
            $remaining_lock_time = $lock_time - ($current_time - strtotime($user['last_failed_login']));
            if ($remaining_lock_time > 0) {
                $_SESSION['lock_message'] = "Account is locked.";
                $_SESSION['lock_timer'] = $remaining_lock_time;
                header('Location: ../index.php?page=login');
                exit;
            } else {
                // Reset failed attempts after lock time passes
                $stmt = $conn->prepare("UPDATE users SET failed_login_attempts = 0 WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user['user_id']);
                $stmt->execute();
                $user['failed_login_attempts'] = 0;
            }
        }

        if ($password === $user['password']) {
            $_SESSION['logged_in'] = true;
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id'];

            $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW(), failed_login_attempts = 0 WHERE user_id = :user_id");
            $updateStmt->bindParam(':user_id', $user['user_id']);
            $updateStmt->execute();

            header('Location: ../index.php?page=home');
            exit;
        } else {
            $attempts = $user['failed_login_attempts'] + 1;
            $stmt = $conn->prepare("UPDATE users SET failed_login_attempts = :attempts, last_failed_login = NOW() WHERE user_id = :user_id");
            $stmt->bindParam(':attempts', $attempts);
            $stmt->bindParam(':user_id', $user['user_id']);
            $stmt->execute();

            $_SESSION['error_message'] = "Incorrect password.";
        }
    } else {
        $_SESSION['error_message'] = "Email not found.";
    }

    header('Location: ../index.php?page=login');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        authenticate($conn, $email, $password);
    }
}
?>
