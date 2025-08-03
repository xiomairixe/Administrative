<?php
include("../connection.php");

// Debug mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Approve via GET request
if (isset($_GET['request_id']) && isset($_GET['status']) && $_GET['status'] == 'Rejected') {
    $reservation_id = $_GET['request_id'];

    // Update reservation to Approved
    $updateReservation = "UPDATE reservation_requests SET status = 'Rejected' WHERE request_id = '$reservation_id'";
    if ($conn->query($updateReservation)) {
        // Get facility name
        $getFacilityName = "SELECT facility_name FROM reservation_requests WHERE request_id = '$reservation_id'";
        $result = $conn->query($getFacilityName);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $facilityName = $row['facility_name'];

            // Update facility status
            $updateFacilityStatus = "UPDATE facilities SET status = 'Available' WHERE facility_name = '$facilityName'";
            $conn->query($updateFacilityStatus);
        }
    }

    // Redirect back to reservation_requests.php
    header("Location: ../request.php");
    exit();
} else {
    echo "Invalid or missing parameters.";
}
?>
