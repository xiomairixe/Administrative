<?php
session_start();
require 'connection.php'; // Make sure this file sets up $pdo (PDO connection)

// You should have user authentication; for demo, we'll use a static user_id
$user_id = $_SESSION['user_id'] ?? 1; // Replace with real session user

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $request_type = $_POST['request_type'] ?? '';
    $priority = $_POST['priority'] ?? 'Medium';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $deadline = $_POST['deadline'] ?? null;
    $stakeholders = trim($_POST['stakeholders'] ?? '');

    // Insert into legal_requests
    $stmt = $conn->prepare("INSERT INTO legal_requests (user_id, request_type, priority, title, description, deadline, stakeholders, status, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Submitted', NOW(), NOW())");
    $stmt->execute([
        $user_id,
        $request_type,
        $priority,
        $title,
        $description,
        $deadline ?: null,
        $stakeholders
    ]);
    $request_id = $conn->lastInsertId();

    // Handle file uploads
    if (!empty($_FILES['documents']['name'][0])) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        foreach ($_FILES['documents']['name'] as $i => $name) {
            $tmpName = $_FILES['documents']['tmp_name'][$i];
            $size = $_FILES['documents']['size'][$i];
            $error = $_FILES['documents']['error'][$i];

            if ($error === UPLOAD_ERR_OK && $size <= 10 * 1024 * 1024) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed = ['pdf', 'doc', 'docx', 'jpg', 'png'];
                if (in_array($ext, $allowed)) {
                    $newName = uniqid('doc_', true) . '.' . $ext;
                    $dest = $uploadDir . $newName;
                    if (move_uploaded_file($tmpName, $dest)) {
                        // Insert into your document table
                        $stmtDoc = $conn->prepare("INSERT INTO document (file_name, file_path, uploaded_by, uploaded_at) VALUES (?, ?, ?, NOW())");
                        $stmtDoc->execute([$name, 'uploads/' . $newName, $user_id]);
                        $document_id = $conn->lastInsertId();

                        // Link document to request
                        $stmtLink = $conn->prepare("INSERT INTO legal_request_documents (request_id, document_id, uploaded_at) VALUES (?, ?, NOW())");
                        $stmtLink->execute([$request_id, $document_id]);
                    }
                }
            }
        }
    }

    header("Location: RequestForm.php?success=1");
    exit;
} else {
    header("Location: RequestForm.php");
    exit;
}
?>