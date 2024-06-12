<?php
require_once "../../config/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["phoneID"])) {
        try {
            $sql = "UPDATE phones SET ";
            $params = array();
            $fieldsToUpdate = array();

            if (isset($_POST["phoneName"])) {
                $fieldsToUpdate[] = "name=?";
                $params[] = $_POST["phoneName"];
            }

            if (isset($_POST["phonePrice"])) {
                $fieldsToUpdate[] = "Price=?";
                $params[] = $_POST["phonePrice"];
            }

            if (isset($_POST["phoneDescription"])) {
                $fieldsToUpdate[] = "Description=?";
                $params[] = $_POST["phoneDescription"];
            }

            if (isset($_POST["phoneFeatured"])) {
                $fieldsToUpdate[] = "Featured=?";
                $params[] = $_POST["phoneFeatured"];
            }

            if (isset($_POST["phoneManufacturer"])) {
                $fieldsToUpdate[] = "Manufacturer_ID=?";
                $params[] = $_POST["phoneManufacturer"];
            }

            if (!empty($fieldsToUpdate)) {
                $sql .= implode(", ", $fieldsToUpdate) . " WHERE ID_phone=?";
                $params[] = $_POST["phoneID"];

                $stmtUpdate = $conn->prepare($sql);
                $stmtUpdate->execute($params);

                echo json_encode(["success" => true, "message" => "Phone details updated successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "No fields provided for update."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Error updating phone details: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Phone ID is missing."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>
