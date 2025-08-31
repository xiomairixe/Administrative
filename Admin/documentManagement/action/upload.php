<?php
require '../connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $docu_type = $_POST['docu_type'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file = $_FILES['document'];
    $folder = !empty($_POST['new_folder']) ? trim($_POST['new_folder']) : trim($_POST['folder']);
    $created_by = $_SESSION['user_id'] ?? 1;

    // Folder logic (same as before)
    $folder_id = null;
    if (!empty($folder)) {
        $stmt = $conn->prepare("SELECT folder_id FROM folder WHERE folder_name = ? AND created_by = ?");
        $stmt->bind_param("si", $folder, $created_by);
        $stmt->execute();
        $stmt->bind_result($folder_id);
        $stmt->fetch();
        $stmt->close();
        if (!$folder_id) {
            $stmt = $conn->prepare("INSERT INTO folder (folder_name, created_by) VALUES (?, ?)");
            $stmt->bind_param("si", $folder, $created_by);
            $stmt->execute();
            $folder_id = $conn->insert_id;
            $stmt->close();
        }
    }

    // File upload
    $uploadDir = "uploads/" . $folder . "/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filename = basename($file["name"]);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file["tmp_name"], $targetPath)) {
        $uploaded_by = $_SESSION['user_id'] ?? 1;
        $status = 'active';

        // Insert into document table
        $stmt = $conn->prepare("INSERT INTO document (file_name, file_path, uploaded_by, folder_id, docu_type, status, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiisss", $filename, $targetPath, $uploaded_by, $folder_id, $docu_type, $status, $description);
        $stmt->execute();
        $document_id = $conn->insert_id;
        $stmt->close();

        // If legal document, also insert into legal_requests
        if ($docu_type === 'legal') {
            $request_type = $_POST['request_type'] ?? '';
            $legal_description = $_POST['legal_description'] ?? '';
            $stakeholders = $_POST['stakeholders'] ?? '';
            $priority = 'Medium'; // Or get from form if needed

            $stmt = $conn->prepare("INSERT INTO legal_requests (user_id, request_type, priority, title, description, stakeholders, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, 'Submitted', NOW(), NOW())");
            $stmt->bind_param("isssss", $uploaded_by, $request_type, $priority, $title, $legal_description, $stakeholders);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: /Administrative/Admin/documentManagement/index.php");
    exit();
}
?>
