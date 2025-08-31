<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $company = $_POST['company'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $visitor_type = $_POST['visitor_type'];
    $purpose = $_POST['purpose'];
    $host_name = $_POST['host_name'];
    $visit_datetime = $_POST['visit_datetime'];
    $visit_duration = $_POST['visit_duration'];
    $notes = $_POST['notes'];
    $id_type = $_POST['id_type'];
    $id_number = $_POST['id_number'];
    $vehicle_plate = $_POST['vehicle_plate'];
    $repeat_flag = isset($_POST['repeat_flag']) ? 1 : 0;
    $face_data = $_POST['face_data'] ?? null;
    $consent_biometric = isset($_POST['consent_biometric']) ? 1 : 0;
    $consent_privacy = isset($_POST['consent_privacy']) ? 1 : 0;
    $access_level = $_POST['access_level'];
    $signature = $_POST['signature'] ?? null;

    $stmt = $conn->prepare("INSERT INTO visitors (
        full_name, company, contact_number, email, visitor_type, purpose, host_name, visit_datetime, visit_duration, notes,
        id_type, id_number, vehicle_plate, repeat_flag, face_data, consent_biometric, consent_privacy, access_level, signature
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssssssssisisss",
        $full_name, $company, $contact_number, $email, $visitor_type, $purpose, $host_name, $visit_datetime, $visit_duration, $notes,
        $id_type, $id_number, $vehicle_plate, $repeat_flag, $face_data, $consent_biometric, $consent_privacy, $access_level, $signature
    );
    $stmt->execute();
    $visitor_id = $stmt->insert_id;
    $stmt->close();

    // Automatically check-in visitor in visit_log
    $stmt2 = $conn->prepare("INSERT INTO visit_log (visitor_id, check_in) VALUES (?, NOW())");
    $stmt2->bind_param("i", $visitor_id);
    if (!$stmt2->execute()) {
        die("Check-in error: " . $stmt2->error);
    }
    $stmt2->close();

    // Redirect to avoid duplicate check-in on refresh
    echo '<script>alert("Visitor registered and checked in successfully!");window.location.href="?success=1";</script>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Visitor Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="card shadow-lg">
    <div class="card-body">
      <h3 class="text-center mb-4">Visitor Registration</h3>
      <form id="visitorForm" method="POST">
        <!-- 🧍 Basic Visitor Information -->
        <h5 class="mb-3">🧍 Basic Visitor Information</h5>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="full_name" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Company / Organization</label>
            <input type="text" class="form-control" name="company" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="text" class="form-control" name="contact_number" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Visitor Type</label>
            <select class="form-select" name="visitor_type" required>
              <option value="">Select...</option>
              <option>Vendor</option>
              <option>Interviewee</option>
              <option>VIP</option>
              <option>Delivery</option>
              <option>Other</option>
            </select>
          </div>
        </div>

        <!-- 📅 Visit Details -->
        <h5 class="mb-3">📅 Visit Details</h5>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label">Purpose of Visit</label>
            <input type="text" class="form-control" name="purpose" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Person to Visit / Host Name</label>
            <input type="text" class="form-control" name="host_name" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Date & Time of Visit</label>
            <input type="datetime-local" class="form-control" name="visit_datetime" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Visit Duration / Expiry Time</label>
            <input type="text" class="form-control" name="visit_duration" placeholder="e.g. 2 hours or 5:00 PM" required>
          </div>
          <div class="col-md-12">
            <label class="form-label">Notes / Special Instructions</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
          </div>
        </div>

        <!-- 🪪 Identity Verification -->
        <h5 class="mb-3">🪪 Identity Verification</h5>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <label class="form-label">Valid ID Type</label>
            <input type="text" class="form-control" name="id_type" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Valid ID Number</label>
            <input type="text" class="form-control" name="id_number" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Vehicle Plate Number</label>
            <input type="text" class="form-control" name="vehicle_plate">
          </div>
          <div class="col-md-4">
            <div class="form-check mt-4">
              <input class="form-check-input" type="checkbox" name="repeat_flag" id="repeat_flag">
              <label class="form-check-label" for="repeat_flag">Repeat Visitor</label>
            </div>
          </div>
        </div>

        <!-- 📸 Biometric & Security -->
        <h5 class="mb-3">📸 Biometric & Security</h5>
        <div class="mb-3">
          <label class="form-label">Facial Scan / Photo Capture</label>
          <div id="my_camera"></div>
          <input type="hidden" name="face_data" id="face_data">
          <button type="button" class="btn btn-secondary mt-2" onclick="take_snapshot()">Capture Face</button>
          <div id="results" class="mt-2"></div>
        </div>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="consent_biometric" id="consent_biometric" required>
              <label class="form-check-label" for="consent_biometric">
                I consent to biometric data collection.
              </label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="consent_privacy" id="consent_privacy" required>
              <label class="form-check-label" for="consent_privacy">
                I acknowledge the <a href="#" target="_blank">Privacy Notice</a>.
              </label>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Access Level / Zone</label>
            <input type="text" class="form-control" name="access_level">
          </div>
        </div>

        <!-- ✍️ Legal & Compliance -->
        <h5 class="mb-3">✍️ Legal & Compliance</h5>
        <div class="mb-3">
          <label class="form-label">Digital Signature</label>
          <canvas id="signature-pad" width="300" height="100" style="border:1px solid #ccc;"></canvas>
          <input type="hidden" name="signature" id="signature_data">
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">Clear Signature</button>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary btn-lg">Submit Registration</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- WebcamJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script>
Webcam.set({
  width: 220,
  height: 180,
  image_format: 'jpeg',
  jpeg_quality: 90
});
Webcam.attach('#my_camera');

function take_snapshot() {
  Webcam.snap(function(data_uri) {
    document.getElementById('face_data').value = data_uri;
    document.getElementById('results').innerHTML = '<img src="'+data_uri+'" class="img-thumbnail" width="120"/>';
  });
}

// Signature pad
let canvas = document.getElementById('signature-pad');
let ctx = canvas.getContext('2d');
let drawing = false;
canvas.addEventListener('mousedown', function(e) { drawing = true; ctx.beginPath(); });
canvas.addEventListener('mouseup', function(e) { drawing = false; });
canvas.addEventListener('mouseout', function(e) { drawing = false; });
canvas.addEventListener('mousemove', drawSignature);
function drawSignature(e) {
  if (!drawing) return;
  let rect = canvas.getBoundingClientRect();
  ctx.lineWidth = 2;
  ctx.lineCap = 'round';
  ctx.strokeStyle = '#222';
  ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
  ctx.stroke();
  ctx.beginPath();
  ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
}
function clearSignature() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  document.getElementById('signature_data').value = '';
}
document.getElementById("visitorForm").addEventListener("submit", function(e){
  document.getElementById("signature_data").value = canvas.toDataURL();
});
</script>
</body>
</html>
