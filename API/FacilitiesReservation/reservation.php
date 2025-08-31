<?php
include '../../connection.php';

// Handle reservation request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserve_facility'])) {
    $facility_id = intval($_POST['facility_id']);
    $facility_name = $_POST['facility_name'];
    $purpose = $_POST['purpose'];
    $number_of_user = intval($_POST['number_of_user']);
    $slot_id = intval($_POST['slot']);
    $slot_label = $_POST['slot_label'] ?? '';
    $reservation_date = $_POST['reservation_date'];
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $duration = $_POST['duration'];
    $recurrence = $_POST['recurrence'];
    $employee_id = $_POST['employee_id'];
    $full_name = $_POST['full_name'];
    $department = $_POST['department'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $equipment_needed = $_POST['equipment_needed'];
    $room_setup = $_POST['room_setup'];
    $catering = $_POST['catering'];
    $catering_details = $_POST['catering_details'];
    $special_requests = $_POST['special_requests'];
    $access_level = $_POST['access_level'] ?? '';
    $visitor_access = $_POST['visitor_access'] ?? '';
    $visitor_access_details = $_POST['visitor_access_details'] ?? '';
    $security_escort = $_POST['security_escort'] ?? '';
    $status = 'Pending';
    $code = rand(100000, 999999);

    $stmt = $conn->prepare("INSERT INTO reservation_requests (
        facility_id, facility_name, purpose, number_of_user, slot_id, slot, reservation_date, start_time, end_time, duration, recurrence,
        employee_id, full_name, department, contact_number, email, equipment_needed, room_setup, catering, catering_details, special_requests,
        access_level, visitor_access, visitor_access_details, security_escort, status, code
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "isssisssssssssssssssssssssi", // 27 chars, last is i for $code
        $facility_id,          // i
        $facility_name,        // s
        $purpose,              // s
        $number_of_user,       // s (change to i if INT in DB)
        $slot_id,              // i
        $slot_label,           // s
        $reservation_date,     // s
        $start_time,           // s
        $end_time,             // s
        $duration,             // s (change to d if DECIMAL in DB)
        $recurrence,           // s
        $employee_id,          // s
        $full_name,            // s
        $department,           // s
        $contact_number,       // s
        $email,                // s
        $equipment_needed,     // s
        $room_setup,           // s
        $catering,             // s
        $catering_details,     // s
        $special_requests,     // s
        $access_level,         // s
        $visitor_access,       // s
        $visitor_access_details,// s
        $security_escort,      // s
        $status,               // s
        $code                  // i
    );
    $stmt->execute();
    $stmt->close();

    echo "<script>
        alert('Request sent successfully!');
        window.location.href = 'index.php';
    </script>";
    exit();
}

// Fetch facilities
$facilities = $conn->query("SELECT * FROM facilities");

// Fetch slots for AJAX
if (isset($_GET['get_slots']) && isset($_GET['facility_id'])) {
    $fid = intval($_GET['facility_id']);
    $date = $_GET['date'] ?? date('Y-m-d');
    $slots = [];
    $res = $conn->query("SELECT * FROM facility_slots WHERE facility_id = $fid AND is_available = 1");
    while ($row = $res->fetch_assoc()) {
        $slots[] = [
            'slot_id' => $row['slot_id'],
            'slot_name' => $row['slot_name'],
            'slot_start' => $row['slot_start'],
            'slot_end' => $row['slot_end']
        ];
    }
    echo json_encode($slots);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Facilities Reservation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f7f8fa; font-family: 'Inter', Arial, sans-serif; }
        .search-bar { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(140,140,200,0.07); padding: 1rem; margin-bottom: 1.5rem; }
        .facility-card { background: #fff; border-radius: 14px; box-shadow: 0 2px 8px rgba(140,140,200,0.07); overflow: hidden; margin-bottom: 1.5rem; }
        .facility-card img { width: 100%; height: 180px; object-fit: cover; }
        .facility-card-body { padding: 1.2rem 1.2rem 1rem 1.2rem; }
        .facility-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.3rem; }
        .facility-location, .facility-capacity { color: #6c757d; font-size: 1.05rem; }
        .facility-amenities { margin: 0.7rem 0 0.7rem 0; }
        .facility-amenities .badge { background: #eef2ff; color: #4311a5; font-size: 0.95rem; margin-right: 0.3rem; }
        .facility-desc { color: #444; font-size: 1.02rem; margin-bottom: 0.7rem; }
        .reserve-btn { background: #2563eb; color: #fff; font-weight: 600; border-radius: 8px; font-size: 1.08rem; }
        .reserve-btn:hover { background: #1d4ed8; }
        .modal-header { background: #4311a5; color: #fff; }
        .modal-title { font-weight: 700; }
        .slot-select { margin-bottom: 1rem; }
        .slot-badge { background: #e0e7ff; color: #4311a5; font-size: 0.98rem; margin-right: 0.3rem; }
    </style>
</head>
<body>
<div class="container py-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
    <div class="search-bar d-flex flex-wrap gap-3 align-items-center">
        <div class="flex-grow-1">
            <input type="text" id="searchInput" class="form-control" placeholder="Search facilities...">
        </div>
        <div>
            <select id="capacityFilter" class="form-select">
                <option value="">All Capacities</option>
                <option value="30">30 people</option>
                <option value="50">50 people</option>
                <option value="200">200 people</option>
            </select>
        </div>
    </div>
    <div class="row" id="facilityList">
        <?php while ($row = $facilities->fetch_assoc()): ?>
        <div class="col-md-4 facility-item" data-name="<?= htmlspecialchars($row['facility_name']) ?>" data-capacity="<?= intval($row['capacity']) ?>">
            <div class="facility-card">
                <img src="<?= htmlspecialchars($row['image'] ? '/Administrative/ReservationManagement/uploads/' . $row['image'] : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80') ?>" alt="Facility Image">
                <div class="facility-card-body">
                    <div class="facility-title"><?= htmlspecialchars($row['facility_name']) ?></div>
                    <div class="facility-location"><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></div>
                    <div class="facility-capacity"><strong>Capacity:</strong> <?= intval($row['capacity']) ?> people</div>
                    <div class="facility-amenities">
                        <strong>Amenities:</strong>
                        <?php
                        // Demo amenities, you can fetch from DB if available
                        $amenities = [];
                        if ($row['facility_name'] == 'Main Conference Room') $amenities = ['Projector','Video conferencing','Whiteboard'];
                        if ($row['facility_name'] == 'Training Lab') $amenities = ['Computers','Smart board','Audio system'];
                        if ($row['facility_name'] == 'Auditorium') $amenities = ['Stage','Audio system','Lighting','Tiered seating'];
                        foreach ($amenities as $am) echo '<span class="badge">'.$am.'</span>';
                        ?>
                    </div>
                    <div class="facility-desc">
                        <?php
                        // Demo descriptions, you can fetch from DB if available
                        if ($row['facility_name'] == 'Main Conference Room') echo 'Large conference room suitable for meetings and presentations';
                        elseif ($row['facility_name'] == 'Training Lab') echo 'Equipped training room with individual workstations';
                        elseif ($row['facility_name'] == 'Auditorium') echo 'Large auditorium for company-wide events and presentations';
                        else echo htmlspecialchars($row['description']);
                        ?>
                    </div>
                    <button class="btn reserve-btn w-100 mt-2" data-bs-toggle="modal" data-bs-target="#reserveModal"
                        data-id="<?= $row['facility_id'] ?>"
                        data-name="<?= htmlspecialchars($row['facility_name']) ?>"
                        data-capacity="<?= intval($row['capacity']) ?>"
                        >Reserve Now</button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Reservation Modal -->
<div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content" method="POST" id="reservationForm">
      <div class="modal-header">
        <h5 class="modal-title" id="reserveModalLabel">Facility Reservation Request</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- 🧍 Requestor Information -->
        <h6 class="fw-bold mb-2">Requestor Information</h6>
        <div class="col-md-12">
            <label class="form-label">Employee ID</label>
            <input type="text" class="form-control" name="employee_id">
          </div>
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="full_name" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Department / Team</label>
            <input type="text" class="form-control" name="department" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="text" class="form-control" name="contact_number" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" required>
          </div>
        </div>

        <!-- 📅 Reservation Details -->
        <h6 class="fw-bold mb-2">Reservation Details</h6>
        <input type="hidden" name="facility_id" id="modalFacilityId">
        <input type="hidden" name="facility_name" id="modalFacilityName">
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Facility Name / Type</label>
            <input type="text" class="form-control" name="facility_type" id="modalFacilityType" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Date of Reservation</label>
            <input type="date" class="form-control" name="reservation_date" id="reservation_date" required>
          </div>
          <div class="col-md-12">
            <label class="form-label">Available Slots</label>
            <select class="form-select" name="slot" id="modalSlot" required>
              <option value="">Select a slot...</option>
              <!-- Slots will be loaded dynamically based on facility and date -->
            </select>
            <div class="form-text">Slots are based on facility and selected date.</div>
          </div>
          <div class="col-md-4">
            <label class="form-label">Duration (hours)</label>
            <input type="number" class="form-control" name="duration" id="duration" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label">Purpose of Reservation</label>
            <input type="text" class="form-control" name="purpose" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Number of Attendees</label>
            <input type="number" class="form-control" name="number_of_user" id="modalNumberOfUser" min="1" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Recurrence</label>
            <select class="form-select" name="recurrence">
              <option>One-time</option>
              <option>Daily</option>
              <option>Weekly</option>
              <option>Monthly</option>
            </select>
          </div>
        </div>

        <!-- 📦 Resource Requirements -->
        <h6 class="fw-bold mb-2">Resource Requirements</h6>
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Equipment Needed</label>
            <input type="text" class="form-control" name="equipment_needed" placeholder="e.g., Projector, Whiteboard">
          </div>
          <div class="col-md-6">
            <label class="form-label">Room Setup Preference</label>
            <select class="form-select" name="room_setup">
              <option>Theater</option>
              <option>U-shape</option>
              <option>Classroom</option>
              <option>Boardroom</option>
              <option>Other</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Catering / Refreshments</label>
            <select class="form-select" name="catering">
              <option>No</option>
              <option>Yes</option>
            </select>
            <input type="text" class="form-control mt-2" name="catering_details" placeholder="Details (if Yes)">
          </div>
        </div>

        <!-- 🔐 Security & Access -->
        <h6 class="fw-bold mb-2">Special Requests / Notes</h6>
        <div class="col-md-12">
            <label class="form-label">Special Requests / Notes</label>
            <textarea class="form-control" name="special_requests" rows="2"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="reserve_facility" class="btn btn-primary w-100">Submit Reservation Request</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search/filter functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('.facility-item').forEach(function(card) {
            card.style.display = card.dataset.name.toLowerCase().includes(val) ? '' : 'none';
        });
    });
    document.getElementById('capacityFilter').addEventListener('change', function() {
        let val = this.value;
        document.querySelectorAll('.facility-item').forEach(function(card) {
            if (!val || card.dataset.capacity == val) card.style.display = '';
            else card.style.display = 'none';
        });
    });

    // Modal: populate and load slots
    var reserveModal = document.getElementById('reserveModal');
    reserveModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var facilityId = button.getAttribute('data-id');
        var facilityName = button.getAttribute('data-name');
        var capacity = button.getAttribute('data-capacity');
        document.getElementById('modalFacilityId').value = facilityId;
        document.getElementById('modalFacilityName').value = facilityName;
        document.getElementById('modalNumberOfUser').max = capacity;

        // Load slots via AJAX
        var slotSelect = document.getElementById('modalSlot');
        slotSelect.innerHTML = '<option value="">Loading slots...</option>';
        fetch('?get_slots=1&facility_id=' + facilityId)
            .then(response => response.json())
            .then(data => {
                slotSelect.innerHTML = '';
                if (data.length === 0) {
                    slotSelect.innerHTML = '<option value="">No available slots</option>';
                } else {
                    data.forEach(function(slot) {
                        let label = slot.slot_name ? slot.slot_name + ' (' + slot.slot_start + ' - ' + slot.slot_end + ')' : slot.slot_start + ' - ' + slot.slot_end;
                        slotSelect.innerHTML += '<option value="' + label + '">' + label + '</option>';
                    });
                }
            })
            .catch(() => {
                slotSelect.innerHTML = '<option value="">Error loading slots</option>';
            });
    });

    // When date changes, reload slots for selected facility and date
    document.getElementById('reservation_date').addEventListener('change', function() {
      loadFacilitySlots();
    });

    // When modal is shown, load slots for default date (today)
    reserveModal.addEventListener('show.bs.modal', function (event) {
      setTimeout(loadFacilitySlots, 300); // delay to ensure facilityId is set
    });

    function loadFacilitySlots() {
      var facilityId = document.getElementById('modalFacilityId').value;
      var reservationDate = document.getElementById('reservation_date').value;
      var slotSelect = document.getElementById('modalSlot');
      slotSelect.innerHTML = '<option value="">Loading slots...</option>';
      fetch(`?get_slots=1&facility_id=${facilityId}&date=${reservationDate}`)
        .then(response => response.json())
        .then(data => {
          slotSelect.innerHTML = '';
          if (data.length === 0) {
            slotSelect.innerHTML = '<option value="">No available slots</option>';
          } else {
            data.forEach(function(slot) {
              let label = slot.slot_name ? slot.slot_name + ' (' + slot.slot_start + ' - ' + slot.slot_end + ')' : slot.slot_start + ' - ' + slot.slot_end;
              slotSelect.innerHTML += `<option value="${slot.slot_id}" data-start="${slot.slot_start}" data-end="${slot.slot_end}">${label}</option>`;
            });
          }
        })
        .catch(() => {
          slotSelect.innerHTML = '<option value="">Error loading slots</option>';
        });
    }

    // When slot changes, update start/end time fields
    document.getElementById('modalSlot').addEventListener('change', function() {
      var selected = this.options[this.selectedIndex];
      document.getElementById('start_time').value = selected.getAttribute('data-start') || '';
      document.getElementById('end_time').value = selected.getAttribute('data-end') || '';
      calcDuration();
    });

    // Duration auto-calc
    document.querySelector('[name="start_time"]').addEventListener('change', calcDuration);
    document.querySelector('[name="end_time"]').addEventListener('change', calcDuration);
    function calcDuration() {
      let start = document.getElementById('start_time').value;
      let end = document.getElementById('end_time').value;
      if (start && end) {
        let s = start.split(':');
        let e = end.split(':');
        let duration = (parseInt(e[0]) + parseInt(e[1])/60) - (parseInt(s[0]) + parseInt(s[1])/60);
        document.getElementById('duration').value = duration > 0 ? duration.toFixed(2) : '';
      }
    }
    // Facility type autofill
    document.querySelectorAll('.reserve-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.getElementById('modalFacilityType').value = btn.closest('.facility-card-body').querySelector('.facility-title').textContent;
      });
    });
    // ...existing JS for slot loading, search/filter...
});
</script>
</body>
</html>