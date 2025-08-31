<?php
// filepath: c:\xampp\htdocs\Administrative\visitor-management\action\check_out.php
include '../connection.php';
$code = $_POST['code'] ?? '';
$res = $conn->query("SELECT visitor_id FROM visitors WHERE qr_code = '$code' OR rfid_code = '$code' LIMIT 1");
if ($row = $res->fetch_assoc()) {
    $conn->query("UPDATE visitors SET status = 'Completed', check_out = NOW() WHERE visitor_id = " . $row['visitor_id']);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Visitor not found.']);
}
?>