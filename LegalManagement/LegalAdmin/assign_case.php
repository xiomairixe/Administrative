<?php
require '../connection.php';

$request_id = intval($_POST['request_id']);
$assigned_to = intval($_POST['assigned_to']);
$note = trim($_POST['note']);

// Get legal request info
$stmt = $conn->prepare("SELECT title, user_id, description FROM legal_requests WHERE request_id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$stmt->bind_result($title, $requestor_id, $description);
$stmt->fetch();
$stmt->close();

// Get client name from users table
$client_name = '';
if ($requestor_id) {
    $stmt = $conn->prepare("SELECT fullname FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $requestor_id);
    $stmt->execute();
    $stmt->bind_result($client_name);
    $stmt->fetch();
    $stmt->close();
}

// Insert into cases table (now with user_id)
$stmt = $conn->prepare("INSERT INTO cases (user_id, name, client, status, assigned_to, start_date) VALUES (?, ?, ?, 'Active', ?, CURDATE())");
$stmt->bind_param("issi", $requestor_id, $title, $client_name, $assigned_to);
$success = $stmt->execute();
$case_id = $conn->insert_id;
$stmt->close();

// Optionally, add note to case_notes
if ($success && !empty($note)) {
    $stmt = $conn->prepare("INSERT INTO case_notes (case_id, user_id, note) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $case_id, $assigned_to, $note);
    $stmt->execute();
    $stmt->close();
}

// Update legal_requests status to 'Active'
if ($success) {
    $stmt = $conn->prepare("UPDATE legal_requests SET status='Active' WHERE request_id=?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$case_sql = "SELECT c.*, u.username AS assigned_name 
             FROM cases c 
             LEFT JOIN users u ON c.assigned_to = u.user_id
             ORDER BY c.start_date DESC";
?>