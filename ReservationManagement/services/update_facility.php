<?php
// filepath: c:\xampp\htdocs\Administrative\ReservationManagement\services\update_facility.php
include('../../connection.php');
$facility_id = intval($_POST['facility_id']);
$facility_name = $_POST['facility_name'];
$location = $_POST['location'];
$type = $_POST['type'];
$capacity = intval($_POST['capacity']);
$image = '';

if (!empty($_FILES['image']['name'])) {
    $target = "../uploads/" . basename($_FILES['image']['name']);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $image = $_FILES['image']['name'];
    }
}

if ($image) {
    $stmt = $conn->prepare("UPDATE facilities SET facility_name=?, location=?, type=?, capacity=?, image=? WHERE facility_id=?");
    $stmt->bind_param("sssisi", $facility_name, $location, $type, $capacity, $image, $facility_id);
} else {
    $stmt = $conn->prepare("UPDATE facilities SET facility_name=?, location=?, type=?, capacity=? WHERE facility_id=?");
    $stmt->bind_param("sssii", $facility_name, $location, $type, $capacity, $facility_id);
}
if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
$stmt->close();
?>
