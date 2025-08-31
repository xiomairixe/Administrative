<?php
// filepath: c:\xampp\htdocs\Administrative\visitor-management\action\check_in.php
include '../connection.php';
$code = $_POST['code'] ?? '';
$res = $conn->query("SELECT visitor_id FROM visitors WHERE qr_code = '$code' OR rfid_code = '$code' LIMIT 1");
if ($row = $res->fetch_assoc()) {
    $conn->query("UPDATE visitors SET status = 'Active', check_in = NOW() WHERE visitor_id = " . $row['visitor_id']);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Visitor not found.']);
}
?>