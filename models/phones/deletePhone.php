<?php
session_start();
include "../../config/connection.php";

if (isset($_POST['phoneID'])) {
    $phoneID = $_POST['phoneID'];

    $query = "DELETE FROM phone_color WHERE ID_phone = :phoneID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":phoneID", $phoneID);
    $stmt->execute();

    $query = "DELETE FROM phones WHERE ID_phone = :phoneID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":phoneID", $phoneID);
    $stmt->execute();

    $response = ['success' => true];
} else {
    $response = ['success' => false];
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
