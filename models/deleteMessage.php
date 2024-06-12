<?php
include('../config/connection.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message_id = $_POST['message_id'];

    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = :message_id");

    if ($stmt === false) {
        header("Location: ../index.php?page=dashboard");
        exit;
    }

    $stmt->bindValue(':message_id', $message_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../index.php?page=dashboard");
    } else {
        echo "Error: " . $stmt->error;
    }

    header("Location: ../index.php?page=dashboard");
    exit;
}
?>
