<?php
include('../connection.php');

// Handle search and filter
$where = [];
$params = [];
$types = '';

if (!empty($_GET['search'])) {
    $where[] = "(facility_name LIKE ? OR description LIKE ? OR location LIKE ?)";
    $search = '%' . $_GET['search'] . '%';
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
    $types .= 'sss';
}
if (!empty($_GET['type'])) {
    $where[] = "type = ?";
    $params[] = $_GET['type'];
    $types .= 's';
}
if (!empty($_GET['status'])) {
    $where[] = "status = ?";
    $params[] = $_GET['status'];
    $types .= 's';
}

$sql = "SELECT * FROM facilities";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY facility_id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$facilities = $stmt->get_result();
$row = $facilities->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Facilities Reservation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="bi bi-list"></i>
  </button>

  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="#" class="active"><i class="bi bi-building"></i> Facilities</a>
    <a href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <hr>
    <a href="submenu/account.php"><i class="bi bi-person"></i> Account</a>
    <a href="submenu/setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="submenu/help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div> 

    <!-- Main Content -->
    <div class="main-content">
      <div class="topbar">
        <div>
          <div class="dashboard-title">Facilities</div>
          <div class="breadcrumbs">Home / Facilities</div>
        </div>
        <div class="profile">
          <div style="position:relative;">
            <i class="bi bi-envelope"></i>
            <span class="badge">2</span>
          </div>
          <img src="#" class="profile-img" alt="profile">
          <div class="profile-info">
            <strong>Steven</strong><br>
            <small>Admin</small>
          </div>
        </div>
      </div>

      <!-- Content Body -->
      <div class="content-body">
        <!-- Header and Add Button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <!-- Filters -->
          <form class="row mb-4 g-2" method="get" id="filterForm">
            <div class="col-md-4">
              <input type="text" class="form-control" id="searchInput" name="search" placeholder="Search facilities..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-3">
              <select class="form-select" id="typeFilter" name="type">
                <option value="">All Types</option>
                <option <?= (($_GET['type'] ?? '') == 'Office Building') ? 'selected' : '' ?>>Office Building</option>
                <option <?= (($_GET['type'] ?? '') == 'Laboratory') ? 'selected' : '' ?>>Laboratory</option>
                <option <?= (($_GET['type'] ?? '') == 'Warehouse') ? 'selected' : '' ?>>Warehouse</option>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select" id="statusFilter" name="status">
                <option value="">All Statuses</option>
                <option <?= (($_GET['status'] ?? '') == 'Operational') ? 'selected' : '' ?>>Operational</option>
                <option <?= (($_GET['status'] ?? '') == 'Maintenance Scheduled') ? 'selected' : '' ?>>Maintenance Scheduled</option>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
          </form>
          <button class="btn" style="background: #ab83fcff;" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
            <ion-icon name="add-circle-outline" class="me-1"></ion-icon> Add New Facility
          </button>
        </div>

    <!-- Facilities -->
    <div class="row g-4">
    <?php if ($row) { do { ?> 
    <div class="col-md-4">
        <div class="facility-card">
          <img src="uploads/<?php echo $row['image'];?>" class="facility-image" alt="<?php echo $row['facility_name']; ?>">
          <div class="p-3">
            <h5><?php echo $row['facility_name'];?></h5>
            <p class="mb-1"><?php echo $row['description'];?></p>
            <p class="mb-1 type-label"><?php echo $row['location'];?></p>
            <p>Status: <?php echo $row['status'];?></p>
            <p class="mb-1"><?php echo $row['capacity'];?></p>
              <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-outline-dark btn-m view-details-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#facilityModal"
                    data-id="<?php echo $row['facility_id']; ?>"
                    data-name="<?php echo htmlspecialchars($row['facility_name'], ENT_QUOTES); ?>"
                    data-location="<?php echo htmlspecialchars($row['location'], ENT_QUOTES); ?>"
                    data-type="<?php echo htmlspecialchars($row['type'], ENT_QUOTES); ?>"
                    data-status="<?php echo htmlspecialchars($row['status'], ENT_QUOTES); ?>"
                    data-capacity="<?php echo $row['capacity']; ?>"
                    data-image="<?php echo $row['image']; ?>">
                    View Details
                </button>

                <button type="button" class="btn btn-danger btn-sm remove-btn" data-id="<?php echo $row['facility_id']; ?>">
                  <i class="bi bi-trash me-1"></i> Remove Facility
                </button>

                <button class="btn btn-sm open-manage-btn"
                  style="background: #ab83fcff;"
                  data-bs-toggle="modal"
                  data-bs-target="#manageModal"
                  data-id="<?php echo $row['facility_id']; ?>"
                  data-name="<?php echo $row['facility_name']; ?>">
                  Manage Slots
                </button>
              </div>
          </div>
        </div>
      </div>
    <?php } while ($row = $facilities->fetch_assoc()); } else { ?>
      <div class="col-12 text-center text-muted">No facilities found.</div>
    <?php } ?>
    </div>
  </div>  

<!-- Add Facility Modal -->
    <div class="modal fade" id="addFacilityModal" tabindex="-1" aria-labelledby="addFacilityModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form action="facilities.php" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="addFacilityModalLabel">Add New Facility</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body row g-3">
              <!-- Name -->
              <div class="col-md-6">
                <label for="facility_name" class="form-label">Facility Name</label>
                <input type="text" class="form-control" id="facility_name" name="facility_name" required>
              </div>

              <!-- Location -->
              <div class="col-md-6">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="facilityLocation" name="location" required>
              </div>

              <!-- Capacity -->
              <div class="col-md-6">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="number" class="form-control" id="capacity" name="capacity" required>
              </div>

              <!-- Type -->
              <div class="col-md-6">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type" required>
                  <option value="">Select type</option>
                  <option>Office Building</option>
                  <option>Laboratory</option>
                  <option>Warehouse</option>
                  <option>Learning Center</option>
                  <option>Server Facility</option>
                </select>
              </div>

              <!-- Description -->
              <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
              </div>

              <!-- Slot ---->
              <div id="slotContainer">
                  <div class="slot-group mb-2">
                      <input type="text" name="slot_name[]" class="form-control mb-1" placeholder="Slot Name" required>
                      <input type="time" name="slot_start[]" class="form-control mb-1" placeholder="Start Time" required>
                      <input type="time" name="slot_end[]" class="form-control mb-1" placeholder="End Time" required>
                  </div>
              </div>
              <button type="button" class="btn btn-secondary btn-sm mb-3" onclick="addSlot()">Add Another Slot</button>


              <!-- Image Upload -->
              <div class="col-12">
                <label for="image" class="form-label">Upload Image</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" name="add_facility" class="btn btn-primary">Save Facility</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>

 <!-- Manage Facility Modal -->
    <div class="modal fade" id="manageModal" tabindex="-1" aria-labelledby="manageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg">
          
          <!-- Dark header -->
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="manageModalLabel">Manage Facility Slots</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <h6 class="fw-bold">Available Slots</h6>
            
            <ul class="list-group mb-4" id="slotList"></ul>
            <form id="addSlotForm" class="row g-2 mb-3">
              <input type="hidden" name="facility_id" id="manageFacilityId">
              <div class="col-md-3">
                <input type="text" name="slot_name" class="form-control" placeholder="Slot Name" required>
              </div>
              <div class="col-md-3">
                <input type="time" name="slot_start" class="form-control" required>
              </div>
              <div class="col-md-3">
                <input type="time" name="slot_end" class="form-control" required>
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Add Slot</button>
              </div>
            </form>

            <hr>

            <h6 class="fw-bold">Maintenance Info</h6>
            <p><strong>Last Maintenance:</strong> January 15, 2025</p>
            <p><strong>Status:</strong> <span class="text-muted">No upcoming maintenance scheduled</span></p>
            <button class="btn btn-danger btn-sm" id="requestMaintenanceBtn">🛠 Request Maintenance</button>
          </div>

          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success">💾 Save Changes</button>
          </div>
        </div>
      </div>
    </div>

   <!-- Facility Detail Modal -->
    <div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content shadow-lg">
          <div class="modal-header bg-black text-white">
            <h5 class="modal-title" id="facilityModalLabel">
              <i class="bi bi-building"></i> Facility Details
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="row g-4">
              <!-- Facility Image -->
              <div class="col-md-5 text-center">
                <img src="uploads/<?php echo $row['image'];?>" id="modalImage" class="img-fluid rounded border shadow-sm" alt="Facility Image">
              </div>

              <!-- View Mode -->
              <div class="col-md-7" id="viewMode">
                <h4 id="modalTitle" class="fw-bold mb-2"></h4>
                <p id="modalAddress" class="text-muted"><i class="bi bi-geo-alt-fill me-1"></i></p>
                <hr>
                <p><strong>Type:</strong> <span id="modalType"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus" class="badge bg-success"></span></p>
                <p><strong>Capacity:</strong> <span id="modalSize"></span></p>
                <!-- Slot Section -->
                <hr>
                <h6 class="fw-bold">Facility Slots</h6>
                <ul class="list-group mb-2" id="detailSlotList">
                  <li class="list-group-item">Loading slots...</li>
                </ul>
              </div>

              <!-- Edit Mode -->
              <div class="col-md-7 d-none" id="editMode">
                <form id="editFacilityForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                  <!-- Hidden Facility ID -->
                  <input type="hidden" id="facility_id" name="facility_id">
                  <div class="mb-3">
                    <label for="editTitle" class="form-label">Facility Name</label>
                    <input type="text" class="form-control" name="facility_name" id="editTitle" required>
                  </div>

                  <div class="mb-3">
                    <label for="editAddress" class="form-label">Location</label>
                    <input type="text" class="form-control" name="location" id="editAddress" required>
                  </div>

                  <!-- Type -->
                  <div class="col-md-6">
                    <label for="editType" class="form-label">Type</label>
                    <select class="form-control" id="editType" name="type" required>
                      <option value="">Select type</option>
                      <option>Office Building</option>
                      <option>Laboratory</option>
                      <option>Warehouse</option>
                      <option>Learning Center</option>
                      <option>Server Facility</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="editSize" class="form-label">Capacity</label>
                    <input type="number" class="form-control" name="capacity" id="editSize" required>
                  </div>

                  <div class="mb-3">
                    <label for="editImage" class="form-label">Image (optional)</label>
                    <input type="file" class="form-control" name="image" id="editImage" accept="image/*">
                  </div>

                  <!-- Facility Slots Edit Section -->
                  <div class="mb-3">
                    <label class="form-label">Facility Slots</label>
                    <ul class="list-group" id="editSlotList">
                      <li class="list-group-item">Loading slots...</li>
                    </ul>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <!-- View Mode Buttons -->
            <div id="viewButtons">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Close
              </button>
              <button type="button" class="btn" style="background: #ab83fcff;" id="editBtn">
                <i class="bi bi-pencil-square me-1"></i> Edit Facility
              </button>
            </div>

            <!-- Edit Mode Buttons -->
            <div id="editButtons" class="d-none">
              <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancel</button>
              <button type="submit" class="btn btn-success" id="saveEditBtn" form="editFacilityForm">Save Changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
function escapeHtml(str) {
  if (!str) return '';
  return String(str).replace(/[&<>"'\/]/g, function (s) {
    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#47;'})[s];
  });
}

// Fix slot display for Manage Modal and Facility Details Modal
function loadSlots(facility_id) {
  const slotList = document.getElementById('slotList');
  slotList.innerHTML = '<li class="list-group-item">Loading slots...</li>';
  fetch(`services/get_slots.php?facility_ID=${facility_id}`)
    .then(response => response.json())
    .then(data => {
      slotList.innerHTML = '';
      if (data.length === 0) {
        slotList.innerHTML = "<li class='list-group-item'>No slots available.</li>";
      } else {
        data.forEach(slot => {
          let badgeClass = slot.is_available == 1 ? "bg-success" : "bg-danger";
          let badgeText = slot.is_available == 1 ? "Available" : "Booked";
          slotList.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span>
                <strong>${escapeHtml(slot.slot_name)}</strong> &nbsp; ${escapeHtml(slot.slot_start)} - ${escapeHtml(slot.slot_end)}
              </span>
              <span>
                <span class="badge ${badgeClass} rounded-pill">${badgeText}</span>
                <button class="btn btn-sm btn-outline-danger ms-2 delete-slot-btn" data-id="${slot.slot_id}"><i class="bi bi-trash"></i></button>
              </span>
            </li>
          `;
        });
        // Attach delete event
        document.querySelectorAll('.delete-slot-btn').forEach(btn => {
          btn.onclick = function() {
            if (confirm("Delete this slot?")) {
              fetch('services/delete_slot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'slot_id=' + encodeURIComponent(this.getAttribute('data-id'))
              })
              .then(res => res.text())
              .then(result => {
                if (result.trim() === "success") {
                  loadSlots(facility_id);
                } else {
                  alert("Failed to delete slot.");
                }
              });
            }
          };
        });
      }
    });
}

// Facility Details Modal: Load slots
document.querySelectorAll(".view-details-btn").forEach(button => {
  button.addEventListener("click", () => {
    const facility_id = button.getAttribute("data-id");
    const name = button.getAttribute("data-name");
    const location = button.getAttribute("data-location");
    const type = button.getAttribute("data-type");
    const status = button.getAttribute("data-status") || "Operational";
    const capacity = button.getAttribute("data-capacity");
    const image = button.getAttribute("data-image");

    document.getElementById("modalImage").src = "uploads/" + image;
    document.getElementById("modalTitle").textContent = name;
    document.getElementById("modalAddress").textContent = location;
    document.getElementById("modalType").textContent = type;
    document.getElementById("modalStatus").textContent = status;
    document.getElementById("modalSize").textContent = capacity;

    // Load slots for this facility
    const detailSlotList = document.getElementById('detailSlotList');
    detailSlotList.innerHTML = '<li class="list-group-item">Loading slots...</li>';
    fetch(`services/get_slots.php?facility_ID=${encodeURIComponent(facility_id)}`)
      .then(res => res.json())
      .then(data => {
        detailSlotList.innerHTML = '';
        if (!data || data.length === 0) {
          detailSlotList.innerHTML = '<li class="list-group-item">No slots available.</li>';
        } else {
          data.forEach(slot => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            const timeText = (slot.slot_start && slot.slot_end) ? `${slot.slot_start} - ${slot.slot_end}` : '';
            const availabilityClass = slot.is_available == 1 ? 'bg-success' : 'bg-danger';
            const availabilityText = slot.is_available == 1 ? 'Available' : 'Booked';
            li.innerHTML = `<div><strong>${escapeHtml(slot.slot_name || '')}</strong><div class="small text-muted">${escapeHtml(timeText)}</div></div>
                            <span class="badge ${availabilityClass} rounded-pill">${availabilityText}</span>`;
            detailSlotList.appendChild(li);
          });
        }
      })
      .catch(err => {
        detailSlotList.innerHTML = '<li class="list-group-item text-danger">Error loading slots</li>';
      });
  });
});

// Open Manage Modal and load slots
document.querySelectorAll('.open-manage-btn').forEach(button => {
  button.addEventListener('click', function () {
    const facility_id = this.getAttribute('data-id');
    document.getElementById('manageFacilityId').value = facility_id;
    loadSlots(facility_id);
  });
});

// Add Slot Form
document.getElementById('addSlotForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch('services/add_slot.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(result => {
    if (result.trim() === "success") {
      loadSlots(document.getElementById('manageFacilityId').value);
      this.reset();
    } else {
      alert("Failed to add slot.");
    }
  });
});

// Edit Facility Modal
document.addEventListener("DOMContentLoaded", () => {
  const editForm = document.getElementById("editFacilityForm");
  document.getElementById("editBtn").addEventListener("click", () => toggleEdit(true));
  document.getElementById("cancelEditBtn").addEventListener("click", () => toggleEdit(false));
  function toggleEdit(isEdit) {
    document.getElementById("viewMode").classList.toggle("d-none", isEdit);
    document.getElementById("viewButtons").classList.toggle("d-none", isEdit);
    document.getElementById("editMode").classList.toggle("d-none", !isEdit);
    document.getElementById("editButtons").classList.toggle("d-none", !isEdit);
  }
  editForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(editForm);
    fetch("services/update_facility.php", {
      method: "POST",
      body: formData
    })
    .then(response => response.text())
    .then(result => {
      if (result.trim() === "success") {
        alert("Facility updated successfully!");
        location.reload();
      } else {
        alert("Update failed: " + result);
      }
    })
    .catch(err => {
      alert("Something went wrong.");
    });
  });
});

// Delete Facility
document.querySelectorAll(".remove-btn").forEach(button => {
  button.addEventListener("click", function () {
    const facility_id = this.getAttribute("data-id");
    if (confirm("Are you sure you want to remove this facility?")) {
      fetch('services/delete_facility.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'facility_id=' + encodeURIComponent(facility_id)
      })
      .then(response => response.text())
      .then(data => {
        if (data.trim() === "success") {
          alert("Facility removed successfully.");
          location.reload();
        } else {
          alert("Facility removed successfully.");
          location.reload();
        }
      })
      .catch(error => {
        alert("Something went wrong.");
      });
    }
  });
});

// Filter triggers
document.getElementById('typeFilter').addEventListener('change', function() {
  document.getElementById('filterForm').submit();
});
document.getElementById('statusFilter').addEventListener('change', function() {
  document.getElementById('filterForm').submit();
});
  </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_facility'])) {
    $facility_name = $_POST['facility_name'];
    $location = $_POST['location'];
    $capacity = $_POST['capacity'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    // Handle image upload
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $target = "uploads/" . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $_FILES['image']['name'];
        }
    }
    // Insert facility
    $stmt = $conn->prepare("INSERT INTO facilities (facility_name, location, capacity, type, description, image, status) VALUES (?, ?, ?, ?, ?, ?, 'Operational')");
    $stmt->bind_param("ssisss", $facility_name, $location, $capacity, $type, $description, $image);
    $stmt->execute();
    $facility_id = $stmt->insert_id;
    $stmt->close();

    // Insert slots
    if (!empty($_POST['slot_name'])) {
        foreach ($_POST['slot_name'] as $i => $slot_name) {
            $slot_start = $_POST['slot_start'][$i];
            $slot_end = $_POST['slot_end'][$i];
            $stmt2 = $conn->prepare("INSERT INTO facility_slots (facility_id, slot_name, slot_start, slot_end, is_available) VALUES (?, ?, ?, ?, 1)");
            $stmt2->bind_param("isss", $facility_id, $slot_name, $slot_start, $slot_end);
            $stmt2->execute();
            $stmt2->close();
        }
    }
    header("Location: facilities.php");
    exit;
}
?>