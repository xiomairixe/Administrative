<?php
session_start();
require '../../connection.php'; // adjust path

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = intval($_POST['case_id']);
    $user_id = intval($_POST['user_id']);
    $note = trim($_POST['note']);

    if ($note === "") {
        echo json_encode(["success" => false, "message" => "Note cannot be empty"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO case_notes (case_id, user_id, note) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $case_id, $user_id, $note);

    if ($stmt->execute()) {
        // Fetch user name for display
        $userQuery = $conn->prepare("SELECT fullname FROM users WHERE user_id = ?");
        $userQuery->bind_param("i", $user_id);
        $userQuery->execute();
        $userResult = $userQuery->get_result()->fetch_assoc();

        echo json_encode([
            "success" => true,
            "note" => htmlspecialchars($note),
            "created_at" => date("Y-m-d H:i:s"),
            "user" => $userResult['fullname']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error"]);
    }
}
