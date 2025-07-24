<?php
include '../connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $facilityID = $_POST['facility_id'];
    $purpose = $_POST['purpose'];
    $numberOfUsers = $_POST['number_of_user'];
    $slot = $_POST['slot'];

    // Get facility name using facilityID
    $facilityQuery = $conn->prepare("SELECT facility_name FROM facilities WHERE facility_id = ?");
    $facilityQuery->bind_param("i", $facilityID);
    $facilityQuery->execute();
    $facilityResult = $facilityQuery->get_result();

    if ($facilityResult->num_rows > 0) {
        $facilityRow = $facilityResult->fetch_assoc();
        $facilityName = $facilityRow['facility_name'];

        // Insert reservation request (now includes facility_id)
        $stmt = $conn->prepare("INSERT INTO reservation_requests (facility_id, facility_name, purpose, number_of_user, slot, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("issis", $facilityID, $facilityName, $purpose, $numberOfUsers, $slot);

        if ($stmt->execute()) {
            //Update facility status to 'Pending'
            $updateFacility = $conn->prepare("UPDATE facilities SET status = 'Pending' WHERE facility_id = ?");
            $updateFacility->bind_param("i", $facilityID);
            $updateFacility->execute();
            $updateFacility->close();

            echo "<script>alert('Reservation request sent successfully.'); window.location.href='../facilities.php';</script>";
        } else {
            echo "<script>alert('Failed to send reservation request.'); window.location.href='../facilities.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Invalid facility.'); window.location.href='../facilities.php';</script>";
    }

    $facilityQuery->close();
    $conn->close();
}
?>