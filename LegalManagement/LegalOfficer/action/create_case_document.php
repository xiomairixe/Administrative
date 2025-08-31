<?php
session_start();
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = intval($_POST['case_id']);
    $title = trim($_POST['title']);
    $type = trim($_POST['type']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'] ?? 1;

    if ($case_id && $title && $type && $content) {
        // Save as a text file (or you can save to DB only)
        $upload_dir = "../uploads/case_$case_id/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $file_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $title) . ".txt";
        $file_path = $upload_dir . $file_name;
        $db_file_path = "uploads/case_$case_id/" . $file_name;
        file_put_contents($file_path, $content);

        $stmt = $conn->prepare("INSERT INTO case_documents (case_id, title, type, version, status, file_path, uploaded_at) VALUES (?, ?, ?, '', 'Draft', ?, NOW())");
        $stmt->bind_param("isss", $case_id, $title, $type, $db_file_path);
        if ($stmt->execute()) {
            $document = [
                'title' => $title,
                'type' => $type,
                'version' => '',
                'status' => 'Draft',
                'file_path' => $db_file_path
            ];
            echo json_encode(['success' => true, 'document' => $document]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    }
}
?>