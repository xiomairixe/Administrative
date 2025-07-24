<?php
include '../connection.php';

// Validate token from URL
$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if (empty($token)) {
    die("<h3>Invalid access: missing token.</h3>");
}

// Prepare statement to fetch reservation ID
$stmt = $conn->prepare("SELECT reservation_id FROM self_register_links WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $reservationID = $row['reservation_id'];
} else {
    die("<h3>Invalid or expired registration link.</h3>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Register as a Visitor</h4>
        </div>
        <div class="card-body">
            <form action="submit_visitor.php" method="POST">
                <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reservationID); ?>">

                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" pattern="[0-9]{10,15}" title="Enter a valid contact number" required>
                </div>

                <!-- Optional: Add CSRF token here for security -->
                <!-- <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> -->

                <button type="submit" class="btn btn-success" name="submit_visitor">Register</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
