<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file = $_FILES['document'];
    $folder = !empty($_POST['new_folder']) ? $_POST['new_folder'] : $_POST['folder'];

    // 1. Resolve folder_id from folder name
    if (!empty($_POST['new_folder'])) {
        // Insert new folder if it doesn't exist and get its ID
        $stmt = $conn->prepare("INSERT INTO folder (folder_name) VALUES (?)");
        $stmt->bind_param("s", $folder);
        $stmt->execute();
        $folder_id = $conn->insert_id;
        $stmt->close();
    } else {
        // Get folder_id from existing folder
        $stmt = $conn->prepare("SELECT folder_id FROM folder WHERE folder_name = ?");
        $stmt->bind_param("s", $folder);
        $stmt->execute();
        $stmt->bind_result($folder_id);
        $stmt->fetch();
        $stmt->close();
    }

    // 2. File upload
    $uploadDir = "uploads/" . $folder . "/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = basename($file["name"]); // ← fix this, was incorrectly using "folder_name"
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        // 3. Insert into document table
        $stmt = $conn->prepare("INSERT INTO document (title, content, file_name, folder_id, upload_date) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssi", $title, $description, $filename, $folder_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: /Administrative/Admin/documentManagement/index.php");
    exit();
}
?>
