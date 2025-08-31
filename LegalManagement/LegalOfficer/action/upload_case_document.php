<?php
session_start();
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = intval($_POST['case_id']);
    $title = trim($_POST['title']);
    $type = trim($_POST['type']);
    $version = trim($_POST['version']);
    $status = trim($_POST['status']);
    $user_id = $_SESSION['user_id'] ?? 1;

    if ($case_id && $title && $type && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $upload_dir = "../uploads/case_$case_id/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $file_name = basename($file['name']);
        $file_path = $upload_dir . $file_name;
        $db_file_path = "uploads/case_$case_id/" . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO case_documents (case_id, title, type, version, status, file_path, uploaded_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("isssss", $case_id, $title, $type, $version, $status, $db_file_path);
            if ($stmt->execute()) {
                $document = [
                    'title' => $title,
                    'type' => $type,
                    'version' => $version,
                    'status' => $status,
                    'file_path' => $db_file_path
                ];
                echo json_encode(['success' => true, 'document' => $document]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'File upload failed']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    }
}
?>