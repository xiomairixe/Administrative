<?php
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facility_id'])) {
    $facility_id = intval($_POST['facility_id']);
    
    // Prepare and execute deletion
    $stmt = $conn->prepare("DELETE FROM facilities WHERE facility_id = ?");
    $stmt->bind_param("i", $facility_id);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmt->close();
    $con->close();
}
?>
