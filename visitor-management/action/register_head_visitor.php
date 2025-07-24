<?php
include '../connection.php';

if (isset($_POST['register_head'])) {
    $reservationID = $_POST['reservation_id'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];

    // Insert head visitor
    $stmt = $conn->prepare("INSERT INTO visitors (reservation_id, full_name, email, contact_number, is_head) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("isss", $reservationID, $fullName, $email, $contact);
    $stmt->execute();

    // Generate unique link
    $token = bin2hex(random_bytes(8));
    $link = "self_register.php?token=$token";

    // Save token into table
    $stmt2 = $conn->prepare("INSERT INTO self_register_links (reservation_id, token) VALUES (?, ?)");
    $stmt2->bind_param("is", $reservationID, $token);
    $stmt2->execute();

    echo "<script>
      alert('Head visitor registered. Share this link with other visitors: http://localhost/Administrative/visitor-management/action/$link');
      window.location.href='facility.php';
    </script>";
}
?>
