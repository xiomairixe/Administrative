<?php
include('../../connection.php');
header('Content-Type: application/json; charset=utf-8');

$facility_id = isset($_GET['facility_ID']) ? intval($_GET['facility_ID']) : 0;
if ($facility_id <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT slot_id, slot_name, slot_start, slot_end, is_available
        FROM facility_slots
        WHERE facility_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $facility_id);
$stmt->execute();
$res = $stmt->get_result();

$slots = [];
while ($row = $res->fetch_assoc()) {
    $slots[] = [
        'slot_id'      => (int)$row['slot_id'],
        'slot_name'    => $row['slot_name'],
        'slot_start'   => $row['slot_start'],
        'slot_end'     => $row['slot_end'],
        'is_available' => (int)$row['is_available'],
        'availability' => ((int)$row['is_available'] === 1) ? 'Available' : 'Booked'
    ];
}

echo json_encode($slots);
$stmt->close();
$conn->close();