<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    // Set status to 'active' or whatever your normal status is
    $stmt = $conn->prepare("UPDATE document SET status = 'active' WHERE document_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
header("Location: ../archive.php");
exit;