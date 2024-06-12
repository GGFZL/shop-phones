<?php
session_start();
require_once '../config/connection.php';

function authenticate($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role_id_for_user = 2;

        $usernameRegex = '/^[a-zA-Z]{4,}$/';
        $emailRegex = '/^[a-zA-Z][a-zA-Z0-9]*@[a-z]+\.[a-z]+$/';
        $passwordRegex = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/';

        $error_message = '';
        if (!preg_match($usernameRegex, $username)) {
            $error_message = 'Username must start with a letter and contain only lowercase letters.';
        } elseif (!preg_match($emailRegex, $email)) {
            $error_message = 'Please enter a valid email address.';
        } elseif (!preg_match($passwordRegex, $password)) {
            $error_message = 'Password must contain at least one letter, one number, one special character, and be at least 6 characters long.';
        }

        if (empty($error_message)) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $error_message = "Email already registered.";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, $role_id_for_user)");
                $stmt->execute([$username, $email, $password]);

                $user = authenticate($conn, $email, $password);
                if ($user) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['role_id'] = $user['role_id'];
                    $_SESSION['username'] = $user['username'];

                    $_SESSION['success_message'] = "Successfully registered and signed in.";

                    header('Location: ../index.php?page=home');
                    exit;
                } else {
                    $error_message = "Failed to automatically sign in after registration.";
                }
            }
        }
    }
}
?>
