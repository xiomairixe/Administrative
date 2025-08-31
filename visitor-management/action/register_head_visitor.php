<?php
include '../connection.php';

if (isset($_POST['register_head'])) {
    $reservationID = $_POST['reservation_id'];
    $passCode = $_POST['pass_code'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact_number'];

    error_log("reservation_id: " . $reservationID);

    // Check if reservation exists
    $check = $conn->prepare("SELECT request_id FROM reservation_requests WHERE request_id = ?");
    $check->bind_param("i", $reservationID);
    $check->execute();
    $check->store_result();
    if ($check->num_rows == 0) {
        echo "<script>alert('Reservation does not exist.'); window.location.href='../facilities.php';</script>";
        exit();
    }
    $check->close();

    // Check if 12-digit code exists and is unused
    $codeCheck = $conn->prepare("SELECT id FROM code WHERE request_id = ? AND code = ?");
    $codeCheck->bind_param("is", $reservationID, $passCode);
    $codeCheck->execute(); 
    $codeCheck->store_result();
    if ($codeCheck->num_rows == 0) {
        echo "<script>alert('Invalid or already used 12-digit pass code.'); window.location.href='../facilities.php';</script>";
        exit();
    }
    // Get code id for deletion
    $codeCheck->bind_result($code_id);
    $codeCheck->fetch();
    $codeCheck->close();

    // Insert head visitor
    $stmt = $conn->prepare("INSERT INTO visitors (reservation_id, full_name, email, contact_number, is_head) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("isss", $reservationID, $fullName, $email, $contact);
    $stmt->execute();

    // Invalidate the code (delete it so it can't be reused)
    $delCode = $conn->prepare("DELETE FROM code WHERE id = ?");
    $delCode->bind_param("i", $code_id);
    $delCode->execute();
    $delCode->close();

    // Generate unique link for self registration
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
