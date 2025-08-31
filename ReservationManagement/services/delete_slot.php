<?php
// filepath: c:\xampp\htdocs\Administrative\ReservationManagement\delete_slot.php
include('../../connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot_id = intval($_POST['slot_id']);
    $sql = "DELETE FROM facility_slots WHERE slot_id = $slot_id";
    if ($conn->query($sql)) {
        echo "success";
    } else {
        echo "error";
    }
}