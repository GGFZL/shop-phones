<?php
session_start();
include "../../config/connection.php";
include '../functions.php';

if (isset($_POST['submit'])) {
    $phoneName = $_POST['phoneName'];
    $phonePrice = $_POST['phonePrice'];
    $phoneImage = $_FILES['phoneImage']['name'];
    $phoneDescription = $_POST['phoneDescription'];
    $phoneColors = $_POST['phoneColors'];
    $phoneFeatured = $_POST['phoneFeatured'];
    $phoneManufacturer = $_POST['phoneManufacturer'];

    $fileTemp = $_FILES['phoneImage']['tmp_name'];
    $exp = explode(".", $phoneImage);
    $extension = end($exp);
    $newFileName = time() . "." . $extension;
    $uploadPath = "../../assets/images/" . $newFileName;
    $upload = move_uploaded_file($fileTemp, $uploadPath);

    if ($upload) {
        $thumbnailPath = "../../assets/thumbnails/thumb_" . $newFileName;
        createThumbnail($uploadPath, $thumbnailPath, 200, 200);

        $insertPhone = addPhone($phoneName, $phonePrice, $newFileName, $phoneDescription, $phoneFeatured, $phoneManufacturer);

        if ($insertPhone) {
            $lastPhoneID = getLastPhoneID($conn);

            foreach ($phoneColors as $colorID) {
                $insertColor = addPhoneColor($lastPhoneID, $colorID);
                if (!$insertColor) {
                    echo "Failed to add phone color.";
                    exit();
                }
            }

            $_SESSION['success_message'] = 'Phone successfully added.';
            header("Location: ../../index.php?page=admin");
            exit();
        } else {
            echo "Failed to add phone.";
        }
    } else {
        echo "Failed to upload image.";
    }
}

?>
