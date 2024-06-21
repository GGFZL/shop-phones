<?php
include "../config/connection.php";
include "functions.php";

try {
    $sql = "SELECT ID_phone, Image FROM phones WHERE Image IS NOT NULL";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $phoneId = $row['ID_phone'];
            $phoneImage = $row['Image'];
            $sourcePath = "../assets/images/" . $phoneImage;
            $thumbnailFileName = "thumb_" . $phoneImage;
            $thumbPath = "../assets/thumbnails/" . $thumbnailFileName;

            if (file_exists($thumbPath)) {
                echo "Thumbnail već postoji za telefon ID $phoneId. Preskačem generisanje thumbnala.<br>";
                continue;
            }

            if (createThumbnail($sourcePath, $thumbPath, 200, 200)) {
                echo "Thumbnail kreiran za telefon ID $phoneId.<br>";
            } else {
                echo "Greška pri kreiranju thumbnaila za telefon ID $phoneId.<br>";
            }
        }
    } else {
        echo "Nema telefona koji zahtevaju thumbnail.<br>";
    }
} catch (PDOException $e) {
    echo "Greška: " . $e->getMessage();
}

$conn = null;
?>
