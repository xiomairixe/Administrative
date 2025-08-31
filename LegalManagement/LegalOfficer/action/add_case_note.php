<?php
session_start();
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = intval($_POST['case_id']);
    $note = trim($_POST['note']);
    $user_id = $_SESSION['user_id'] ?? 1; // Use session or fallback

    if ($case_id && $note) {
        $stmt = $conn->prepare("INSERT INTO case_notes (case_id, user_id, note, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $case_id, $user_id, $note);
        if ($stmt->execute()) {
            // Get username for display
            $user = $conn->query("SELECT username FROM users WHERE user_id = $user_id")->fetch_assoc();
            $note_display = $user['username'] . ': ' . $note;
            echo json_encode(['success' => true, 'note_display' => $note_display]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing case or note']);
    }
}
?>