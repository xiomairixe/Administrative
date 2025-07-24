<?php
include '../connection.php';

if (isset($_POST['submit_visitor'])) {
    $reservationID = $_POST['reservation_id'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];

    $stmt = $conn->prepare("INSERT INTO visitors (reservation_id, full_name, email, contact_number, is_head) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("isss", $reservationID, $fullName, $email, $contact);

    if ($stmt->execute()) {
        echo "Registration successful.";
        // Redirect to facilities page
        header("Location: ../facilities.php");
    } else {
        echo "Failed to register visitor.";
    }
}
?>
