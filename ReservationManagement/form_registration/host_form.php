<?php
include '../../connection.php';

// Handle AJAX autofill
if (isset($_GET['fetch_request']) && is_numeric($_GET['fetch_request'])) {
    $rid = intval($_GET['fetch_request']);
    $res = $conn->query("SELECT * FROM reservation_requests WHERE request_id=$rid");
    if ($row = $res->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Not found']);
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $request_id = $_POST['request_id'];
  $host_name = $_POST['host_name'];
  $department = $_POST['department'];
  $contact_number = $_POST['contact_number'];
  $email = $_POST['email'];
  $facility_reserved = $_POST['facility_reserved'];
  $reservation_date = $_POST['reservation_date'];
  $reservation_start = $_POST['reservation_start'];
  $reservation_end = $_POST['reservation_end'];
  $purpose = $_POST['purpose'];
  $expected_attendees = $_POST['expected_attendees'];
  $actual_attendees = $_POST['actual_attendees'];
  $attendees_list = $_POST['attendees_list'];
  $external_guests = $_POST['external_guests'];
  $no_show_list = $_POST['no_show_list'];
  $equipment_used = $_POST['equipment_used'];
  $room_setup = $_POST['room_setup'];
  $issues_encountered = $_POST['issues_encountered'];
  $issue_description = $_POST['issue_description'];
  $facility_condition = $_POST['facility_condition'];
  $feedback = $_POST['feedback'];
  $handover_confirmation = isset($_POST['handover_confirmation']) ? 1 : 0;
  $handover_time = $_POST['handover_time'];
  $handover_staff = $_POST['handover_staff'];
  $incident_report = $_POST['incident_report'];
  $incident_reference = $_POST['incident_reference'];
  $ack_terms = isset($_POST['ack_terms']) ? 1 : 0;
  $digital_signature = $_POST['digital_signature'];

  $stmt = $conn->prepare("INSERT INTO reservation_event_report (
      request_id, host_name, department, contact_number, email, facility_reserved, reservation_date, reservation_start, reservation_end, purpose,
      expected_attendees, actual_attendees, attendees_list, external_guests, no_show_list,
      equipment_used, room_setup, issues_encountered, issue_description, facility_condition, feedback,
      handover_confirmation, handover_time, handover_staff, incident_report, incident_reference, ack_terms, digital_signature
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->bind_param(
      "isssssssssiiissssssssssisssi",
      $request_id, $host_name, $department, $contact_number, $email, $facility_reserved, $reservation_date, $reservation_start, $reservation_end, $purpose,
      $expected_attendees, $actual_attendees, $attendees_list, $external_guests, $no_show_list,
      $equipment_used, $room_setup, $issues_encountered, $issue_description, $facility_condition, $feedback,
      $handover_confirmation, $handover_time, $handover_staff, $incident_report, $incident_reference, $ack_terms, $digital_signature
  );

  if ($stmt->execute()) {
    echo "<div class='alert alert-success mt-4'>Form submitted successfully!</div>";
  } else {
    echo "<div class='alert alert-danger mt-4'>Error submitting form: " . $stmt->error . "</div>";
  }

  $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Post-Reservation Event Accountability Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="card shadow-lg rounded-4">
    <div class="card-body p-4">
      <h2 class="mb-4 text-center">📑 Post-Reservation Event Accountability Form</h2>
      <!-- Request ID Autofill -->
      <div class="mb-4">
        <label class="form-label">Reservation Request ID</label>
        <div class="input-group">
          <input type="number" min="1" id="fetchRequestId" class="form-control" placeholder="Enter Request ID">
          <button type="button" class="btn btn-outline-primary" onclick="fetchReservation()">Fetch</button>
        </div>
        <div id="fetchStatus" class="form-text text-danger"></div>
      </div>
      <form action="" method="POST" enctype="multipart/form-data" id="accountForm">
        <input type="hidden" name="request_id" id="request_id">

        <!-- Host & Event Details -->
        <h4 class="mb-3">🧑‍💼 Host & Event Details</h4>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Host Name / Team Lead</label>
            <input type="text" name="host_name" id="host_name" class="form-control" pattern="[A-Za-z\s]+" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Department / Team</label>
            <input type="text" name="department" id="department" class="form-control" pattern="[A-Za-z\s]+" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="tel" name="contact_number" id="contact_number" class="form-control" pattern="[0-9]+" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Facility Reserved</label>
            <input type="text" name="facility_reserved" id="facility_reserved" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Reservation Date</label>
            <input type="date" name="reservation_date" id="reservation_date" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Reservation Time</label>
            <input type="time" name="reservation_start" id="reservation_start" class="form-control" required>
            <small class="text-muted">Start</small>
            <input type="time" name="reservation_end" id="reservation_end" class="form-control mt-1" required>
            <small class="text-muted">End</small>
          </div>
          <div class="col-12">
            <label class="form-label">Purpose of Event</label>
            <textarea name="purpose" id="purpose" class="form-control" rows="2" required></textarea>
          </div>
        </div>
        <hr class="my-4">

        <!-- Attendance Tracking -->
        <h4 class="mb-3">👥 Attendance Tracking</h4>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Total Number of Expected Attendees</label>
            <input type="number" name="expected_attendees" id="expected_attendees" class="form-control" min="0" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Actual Number of Attendees</label>
            <input type="number" name="actual_attendees" id="actual_attendees" class="form-control" min="0" required>
          </div>
          <div class="col-12">
            <label class="form-label">List of Attendees (Excel file)</label>
            <input type="file" name="attendees_excel" id="attendees_excel" class="form-control" accept=".xlsx,.xls">
            <textarea name="attendees_list" id="attendees_list" class="form-control mt-2" rows="3" readonly placeholder="Attendees will be auto-filled from Excel"></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">External Guests (Name & Affiliation)</label>
            <textarea name="external_guests" id="external_guests" class="form-control" rows="2"></textarea>
          </div>
          <div class="col-12">
            <label class="form-label">No-show List (optional)</label>
            <textarea name="no_show_list" id="no_show_list" class="form-control" rows="2"></textarea>
          </div>
        </div>

        <hr class="my-4">

        <!-- 🛠️ Facility Usage & Feedback -->
        <h4 class="mb-3">🛠️ Facility Usage & Feedback</h4>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Equipment Used</label>
            <input type="text" name="equipment_used" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Room Setup Used</label>
            <input type="text" name="room_setup" class="form-control">
          </div>
          <div class="col-12">
            <label class="form-label">Issues Encountered</label>
            <select name="issues_encountered" class="form-select">
              <option value="No">No</option>
              <option value="Yes">Yes</option>
            </select>
            <textarea name="issue_description" class="form-control mt-2" placeholder="If Yes, describe"></textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">Facility Condition After Use</label>
            <select name="facility_condition" class="form-select">
              <option>Clean</option>
              <option>Damaged</option>
              <option>Needs Maintenance</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Feedback on Facility (optional)</label>
            <textarea name="feedback" class="form-control" rows="2"></textarea>
          </div>
        </div>

        <hr class="my-4">

        <!-- 📑 Compliance & Handover -->
        <h4 class="mb-3">📑 Compliance & Handover</h4>
        <div class="row g-3">
          <div class="col-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="handover_confirmation" id="handoverCheck">
              <label class="form-check-label" for="handoverCheck">Handover Confirmation</label>
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Time of Handover</label>
            <input type="time" name="handover_time" class="form-control">
          </div>
          <div class="col-md-8">
            <label class="form-label">Staff Involved in Handover (Name & Role)</label>
            <input type="text" name="handover_staff" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">Incident Report Filed</label>
            <select name="incident_report" class="form-select">
              <option>No</option>
              <option>Yes</option>
            </select>
            <input type="text" name="incident_reference" class="form-control mt-2" placeholder="Reference Number (if Yes)">
          </div>
          <div class="col-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="ack_terms" id="termsCheck" required>
              <label class="form-check-label" for="termsCheck">I acknowledge the Terms & Conditions</label>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label">Digital Signature of Host / Team Lead</label>
            <input type="text" name="digital_signature" class="form-control" placeholder="Type full name as signature" required>
          </div>
        </div>

        <div class="d-grid mt-4">
          <button type="submit" class="btn btn-primary btn-lg">Submit Form</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
function fetchReservation() {
  const rid = document.getElementById('fetchRequestId').value;
  if (!rid) return;
  fetch('?fetch_request=' + encodeURIComponent(rid))
    .then(r => r.json())
    .then(data => {
      if (data.error) {
        document.getElementById('fetchStatus').textContent = 'Request not found!';
        return;
      }
      document.getElementById('fetchStatus').textContent = '';
      document.getElementById('request_id').value = data.request_id;
      document.getElementById('facility_reserved').value = data.facility_name || '';
      document.getElementById('reservation_date').value = data.reservation_date || '';
      document.getElementById('reservation_start').value = data.start_time || '';
      document.getElementById('reservation_end').value = data.end_time || '';
      document.getElementById('purpose').value = data.purpose || '';
      document.getElementById('department').value = data.department || '';
      document.getElementById('contact_number').value = data.contact_number || '';
      document.getElementById('email').value = data.email || '';
      // Optionally autofill host_name if available
      if (data.full_name) document.getElementById('host_name').value = data.full_name;
    });
}

// Excel to textarea
document.getElementById('attendees_excel').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function(e) {
    const data = new Uint8Array(e.target.result);
    const workbook = XLSX.read(data, {type: 'array'});
    const sheet = workbook.Sheets[workbook.SheetNames[0]];
    const rows = XLSX.utils.sheet_to_json(sheet, {header:1});
    let text = '';
    rows.forEach(row => {
      text += row.join(', ') + "\n";
    });
    document.getElementById('attendees_list').value = text.trim();
  };
  reader.readAsArrayBuffer(file);
});

// Input restrictions for string fields (allow only letters and spaces)
document.querySelectorAll('input[pattern="[A-Za-z\\s]+"]').forEach(function(input) {
  input.addEventListener('input', function() {
    this.value = this.value.replace(/[^A-Za-z\s]/g, '');
  });
});
</script>
</body>
</html>
