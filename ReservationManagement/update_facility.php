<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $facility_id = intval($_POST['facility_id']);
    $facility_name = $_POST['facility_name'];
    $location = $_POST['location'];
    $type = $_POST['type'];
    $capacity = intval($_POST['capacity']);

    // Handle image upload if provided
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $targetDir = "uploads/";
        $imageName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . time() . "_" . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            echo "image_upload_failed";
            exit;
        }
    }

    // Update query
    $query = "UPDATE facilities SET facility_name = ?, location = ?, type = ?, capacity = ?" .
             ($imagePath ? ", image = ?" : "") . " WHERE facility_id = ?";
    $params = [$facility_name, $location, $type, $capacity];
    if ($imagePath) $params[] = $imagePath;
    $params[] = $facility_id;

    $types = str_repeat("s", count($params) - 1) . "i";
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "update_failed";
    }

    $stmt->close();
    $conn->close();
}
?>
