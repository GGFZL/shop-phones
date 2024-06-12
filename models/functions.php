<?php

function getAll($tableName) {
    global $conn;

    $query = "SELECT * FROM $tableName";

    return $conn->query($query)->fetchAll();
}

function getFeatured(){
    global $conn;
    $sql = "SELECT * from phones WHERE Featured = 1";
    return $conn->query($sql)->fetchAll();
}

function getPhoneData() {
    global $conn;
    $sql = "SELECT phones.ID_phone, phones.Featured, phones.name, phones.Price, phones.Image, phones.Description, manufacturers.name AS manufacturer_name, GROUP_CONCAT(colors.name SEPARATOR ', ') AS colors
        FROM phones
        INNER JOIN phone_color ON phones.ID_phone = phone_color.ID_phone
        INNER JOIN colors ON phone_color.ID_color = colors.ID_color
        INNER JOIN manufacturers ON phones.Manufacturer_ID = manufacturers.ID_manufacturer
        GROUP BY phones.ID_phone";
        
    $stmt = $conn->query($sql);

    return $stmt->fetchAll();
}

function addPhone($phoneName, $phonePrice, $phoneImage, $phoneDescription, $phoneFeatured, $phoneManufacturer){
    global $conn;

    $query = "INSERT INTO phones (name, Price, Image, Description, Featured, Manufacturer_ID) 
                VALUES (:name, :price, :imageName, :desc, :featured, :idManufacturer)";

    $lastPhoneID = getLastPhoneID($conn);
    $newPhoneID = $lastPhoneID + 1;

    $query = "INSERT INTO phones (ID_phone, name, Price, Image, Description, Featured, Manufacturer_ID) 
            VALUES (:id, :name, :price, :imageName, :desc, :featured, :idManufacturer)";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $newPhoneID);
    $stmt->bindParam(":name", $phoneName);
    $stmt->bindParam(":price", $phonePrice);
    $stmt->bindParam(":imageName", $phoneImage);
    $stmt->bindParam(":desc", $phoneDescription);
    $stmt->bindParam(":featured", $phoneFeatured);
    $stmt->bindParam(":idManufacturer", $phoneManufacturer);
    
    // Execute the query
    $result = $stmt->execute();
    
    return $result;
}

function addPhoneColor($phoneID, $colorID){
    global $conn;

    $query = "INSERT INTO phone_color (ID_phone, ID_color) VALUES (:phoneID, :colorID)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":phoneID", $phoneID);
    $stmt->bindParam(":colorID", $colorID);
    
    $result = $stmt->execute();

    return $result;
}

function getLastPhoneID($conn) {
    $query = "SELECT MAX(ID_phone) AS last_id FROM phones";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['last_id'] ? $row['last_id'] : 0;
}

function getPhoneDataForPage($selectedBrands, $selectedColors, $perPage, $offset, $sortOption) {
    global $conn;

    $orderBy = getOrderByClause($sortOption);

    $brandFilter = '';
    if (!empty($selectedBrands)) {
        $brandFilter = 'AND phones.Manufacturer_ID IN (' . implode(',', array_map('intval', $selectedBrands)) . ')';
    }

    $colorFilter = '';
    if (!empty($selectedColors)) {
        $colorFilter = 'AND phone_color.ID_color IN (' . implode(',', array_map('intval', $selectedColors)) . ')';
    }

    $sql = "SELECT phones.ID_phone, phones.Featured, phones.name, phones.Price, phones.Image, phones.Description, manufacturers.name AS manufacturer_name, GROUP_CONCAT(colors.name SEPARATOR ', ') AS colors
            FROM phones
            INNER JOIN phone_color ON phones.ID_phone = phone_color.ID_phone
            INNER JOIN colors ON phone_color.ID_color = colors.ID_color
            INNER JOIN manufacturers ON phones.Manufacturer_ID = manufacturers.ID_manufacturer
            WHERE 1=1 $brandFilter $colorFilter
            GROUP BY phones.ID_phone
            $orderBy
            LIMIT :offset, :perPage";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function getTotalPhoneCount($searchTerm = null, $selectedBrands = [], $selectedColors = []) {
    global $conn;

    $brandFilter = '';
    if (!empty($selectedBrands)) {
        $brandFilter = 'AND phones.Manufacturer_ID IN (' . implode(',', array_map('intval', $selectedBrands)) . ')';
    }

    $colorFilter = '';
    if (!empty($selectedColors)) {
        $colorFilter = 'AND phone_color.ID_color IN (' . implode(',', array_map('intval', $selectedColors)) . ')';
    }

    $sql = "SELECT COUNT(DISTINCT phones.ID_phone) AS total FROM phones
            INNER JOIN phone_color ON phones.ID_phone = phone_color.ID_phone
            WHERE 1=1 $brandFilter $colorFilter";

    if (!empty($searchTerm)) {
        $sql .= " AND phones.name LIKE :searchTerm";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($searchTerm)) {
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    }

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

function searchPhones($searchTerm, $selectedBrands, $selectedColors, $perPage, $offset, $sortOption) {
    global $conn;

    $orderBy = getOrderByClause($sortOption);

    $brandFilter = '';
    if (!empty($selectedBrands)) {
        $brandFilter = 'AND phones.Manufacturer_ID IN (' . implode(',', array_map('intval', $selectedBrands)) . ')';
    }

    $colorFilter = '';
    if (!empty($selectedColors)) {
        $colorFilter = 'AND phone_color.ID_color IN (' . implode(',', array_map('intval', $selectedColors)) . ')';
    }

    $sql = "SELECT phones.ID_phone, phones.Featured, phones.name, phones.Price, phones.Image, phones.Description, manufacturers.name AS manufacturer_name, GROUP_CONCAT(colors.name SEPARATOR ', ') AS colors
            FROM phones
            INNER JOIN phone_color ON phones.ID_phone = phone_color.ID_phone
            INNER JOIN colors ON phone_color.ID_color = colors.ID_color
            INNER JOIN manufacturers ON phones.Manufacturer_ID = manufacturers.ID_manufacturer
            WHERE phones.name LIKE :searchTerm $brandFilter $colorFilter
            GROUP BY phones.ID_phone
            $orderBy
            LIMIT :offset, :perPage";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function getTotalPhoneCountt($searchTerm = null) {

    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM phones";
    if (!empty($searchTerm)) {
    $sql .= " WHERE name LIKE :searchTerm";
    }
    $stmt = $conn->prepare($sql);

    if (!empty($searchTerm)) {
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    }

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalCount = $result['total'];

    return $totalCount;
}

function getOrderByClause($sortOption) {
    switch ($sortOption) {
        case 'price_desc':
            return "ORDER BY phones.Price DESC";
        case 'price_asc':
            return "ORDER BY phones.Price ASC";
        case 'name_desc':
            return "ORDER BY phones.name DESC";
        case 'name_asc':
            return "ORDER BY phones.name ASC";
        default:
            return "ORDER BY phones.ID_phone";
    }
}

function countLoginsToday($conn) {
    $query = "SELECT COUNT(*) as login_count FROM users WHERE DATE(last_login) = CURDATE()";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['login_count'];
}

function getLoginsToday($conn) {
    $query = "SELECT username, email, last_login FROM users WHERE DATE(last_login) = CURDATE()";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createThumbnail($sourcePath, $thumbPath, $thumbWidth, $thumbHeight) {
    list($width, $height, $type) = getimagesize($sourcePath);

    $source = null;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }

    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $thumbPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $thumbPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $thumbPath);
            break;
    }

    imagedestroy($source);
    imagedestroy($thumb);
}

?>