<?php
include('../config/connection.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    $subjectPattern = "/^[a-zA-Z\s]+$/";
    $messagePattern = "/^.{1,1000}$/";

    if (!preg_match($subjectPattern, $subject)) {
        echo "Invalid subject format";
        exit;
    }

    if (!preg_match($messagePattern, $message)) {
        echo "Invalid message format";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contact_messages (user_id, subject, message) VALUES (:id, :subj, :mess)");

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->errorInfo()[2]));
    }

    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':subj', $subject, PDO::PARAM_STR);
    $stmt->bindValue(':mess', $message, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: ../contact.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
