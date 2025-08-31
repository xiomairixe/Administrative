<?php
// filepath: c:\xampp\htdocs\Administrative\visitor-management\VisitorManagemet\chack_out.php
include '../../connection.php';

$visitor = null;
$feedback_saved = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitor_id = intval($_POST['visitor_id']);
    $feedback = trim($_POST['feedback']);

    // Update visitor status and save feedback
    $stmt = $conn->prepare("UPDATE visitors SET visit_status='Checked Out', notes=CONCAT(IFNULL(notes,''), '\nFeedback: ', ?) WHERE visitor_id=?");
    $stmt->bind_param("si", $feedback, $visitor_id);
    $stmt->execute();

    // Update visit_log with check_out time
    $stmt2 = $conn->prepare("UPDATE visit_log SET check_out=NOW() WHERE visitor_id=? AND check_out IS NULL");
    $stmt2->bind_param("i", $visitor_id);
    $stmt2->execute();
    $stmt2->close();

    $feedback_saved = true;
}

// If visitor_id is submitted via GET or POST, fetch visitor info
if (isset($_GET['visitor_id']) || isset($_POST['visitor_id'])) {
    $vid = intval($_GET['visitor_id'] ?? $_POST['visitor_id']);
    $res = $conn->query("SELECT * FROM visitors WHERE visitor_id=$vid");
    if ($res && $res->num_rows > 0) {
        $visitor = $res->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Visitor Check-Out</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="card shadow-lg">
    <div class="card-body">
      <h3 class="text-center mb-4">Visitor Check-Out</h3>
      <?php if ($feedback_saved): ?>
        <div class="alert alert-success">Thank you for your feedback! You have been checked out.</div>
      <?php endif; ?>
      <form method="POST" id="checkoutForm">
        <div class="mb-3">
          <label class="form-label">Visitor ID</label>
          <input type="number" class="form-control" name="visitor_id" id="visitor_id" required value="<?= htmlspecialchars($_POST['visitor_id'] ?? $_GET['visitor_id'] ?? '') ?>">
          <button type="button" class="btn btn-secondary mt-2" onclick="fetchVisitor()">Fetch Details</button>
        </div>
        <?php if ($visitor): ?>
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($visitor['full_name']) ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Company</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($visitor['company']) ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Purpose of Visit</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($visitor['purpose']) ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Host Name</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($visitor['host_name']) ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Date & Time of Visit</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($visitor['visit_datetime']) ?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Feedback / Comments</label>
          <textarea class="form-control" name="feedback" rows="3" required placeholder="Your feedback or comments..."></textarea>
        </div>
        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary btn-lg">Submit Check-Out</button>
        </div>
        <?php endif; ?>
      </form>
    </div>
  </div>
</div>
<script>
function fetchVisitor() {
  var vid = document.getElementById('visitor_id').value;
  if (vid) {
    window.location.href = '?visitor_id=' + encodeURIComponent(vid);
  }
}
</script>
</body>
</html>