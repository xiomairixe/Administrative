<?php
// filepath: c:\xampp\htdocs\Administrative\ReservationManagement\add_slot.php
include('../../connection.php');
if (isset($_POST['facility_id'], $_POST['slot_name'], $_POST['slot_start'], $_POST['slot_end'])) {
    $stmt = $conn->prepare("INSERT INTO facility_slots (facility_id, slot_name, slot_start, slot_end, is_available) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("isss", $_POST['facility_id'], $_POST['slot_name'], $_POST['slot_start'], $_POST['slot_end']);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}
?>