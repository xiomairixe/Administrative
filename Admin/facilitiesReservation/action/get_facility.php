<?php
include 'connection.php'; // your DB config

if (isset($_GET['facilityID'])) {
    $facilityID = $_GET['facilityID'];

    $stmt = $conn->prepare("SELECT * FROM facility WHERE facilityID = ?");
    $stmt->bind_param("i", $facilityID);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Facility not found']);
    }

    $stmt->close();
    $con->close();
} else {
    echo json_encode(['error' => 'No ID provided']);
}
