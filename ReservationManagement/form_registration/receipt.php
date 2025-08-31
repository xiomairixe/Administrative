<?php
include '../../connection.php';

if (!isset($_GET['id'])) {
    echo "No receipt found.";
    exit;
}
$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM reservation_event_report WHERE report_id = $id");
if (!$res || $res->num_rows == 0) {
    echo "Receipt not found.";
    exit;
}
$row = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Submission Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow mx-auto" style="max-width:600px;">
        <div class="card-body">
            <h3 class="card-title text-success mb-3">Submission Successful!</h3>
            <p class="mb-2">Thank you for submitting the Post-Reservation Event Accountability Form.</p>
            <hr>
            <h5>Submission Details</h5>
            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Host Name:</strong> <?= htmlspecialchars($row['host_name']) ?></li>
                <li class="list-group-item"><strong>Department:</strong> <?= htmlspecialchars($row['department']) ?></li>
                <li class="list-group-item"><strong>Facility Reserved:</strong> <?= htmlspecialchars($row['facility_reserved']) ?></li>
                <li class="list-group-item"><strong>Date:</strong> <?= htmlspecialchars($row['reservation_date']) ?></li>
                <li class="list-group-item"><strong>Time:</strong> <?= htmlspecialchars($row['reservation_start']) ?> - <?= htmlspecialchars($row['reservation_end']) ?></li>
                <li class="list-group-item"><strong>Purpose:</strong> <?= htmlspecialchars($row['purpose']) ?></li>
                <li class="list-group-item"><strong>Expected Attendees:</strong> <?= htmlspecialchars($row['expected_attendees']) ?></li>
                <li class="list-group-item"><strong>Actual Attendees:</strong> <?= htmlspecialchars($row['actual_attendees']) ?></li>
            </ul>
            <div class="alert alert-info">
                <strong>Receipt No:</strong> <?= $row['report_id'] ?><br>
                <strong>Submission Date:</strong> <?= $row['created_at'] ?>
            </div>
            <a href="host_form.php" class="btn btn-primary">Submit Another Response</a>
        </div>
    </div>
</div>
</body>
</html>