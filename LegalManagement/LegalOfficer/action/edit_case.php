<?php
session_start();
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = intval($_POST['case_id']);
    $name = trim($_POST['name']);
    $client = trim($_POST['client']);
    $status = trim($_POST['status']);

    if ($case_id && $name && $client && $status) {
        $stmt = $conn->prepare("UPDATE cases SET name = ?, client = ?, status = ? WHERE case_id = ?");
        $stmt->bind_param("sssi", $name, $client, $status, $case_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'name' => $name, 'client' => $client, 'status' => $status]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    }
}
?>