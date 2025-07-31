<?php
require 'connection.php'; // Make sure you include your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['facilityID'])) {
    $facilityID = intval($_POST['facilityID']);
    
    // Prepare and execute deletion
    $stmt = $con->prepare("DELETE FROM facility WHERE facilityID = ?");
    $stmt->bind_param("i", $facilityID);
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmt->close();
    $con->close();
}
?>
