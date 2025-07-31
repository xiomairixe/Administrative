<?php
include('connection.php');

//Display existing facilities
$sql = "SELECT * FROM facilities";
$facilities = $conn->query($sql) or die ($conn->error);
$row = $facilities->fetch_assoc();

if (isset($_POST['add_facility'])) {
    $facility_name = $_POST['facility_name'];
    $type = $_POST['type'];
    $status = "Available"; // Default status
    $capacity = intval($_POST['capacity']);
    $description = $_POST['description'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $image = null; // Handle case where no image is uploaded
    }

    $sql = "INSERT INTO facilities (facility_name, type, status, capacity, description, image)
            VALUES ('$facility_name', '$type', '$status', $capacity, '$description', '$image')";

    if (mysqli_query($conn, $sql)) {
        header("Location: facilities.php?added=1");
        exit();
    } else {
        echo "Error adding facility: " . mysqli_error($conn);
    }
}

// if (isset($_POST['update_facility'])) {
//     $facility_id = intval($_POST['facility_id']);
//     $facility_name = $_POST['facility_name'];
//     $facility_type = $_POST['facility_type'];
//     $status = $_POST['status'];
//     $capacity = intval($_POST['capacity']);
//     $description = $_POST['description'];

//     $sql = "UPDATE facilities SET 
//             facility_name = '$facility_name',
//             facility_type = '$facility_type',
//             status = '$status',
//             capacity = $capacity,
//             description = '$description'
//             WHERE facilityID = $facility_id";

//     if (mysqli_query($conn, $sql)) {
//         header("Location: facilities.php?updated=1");
//         exit();
//     } else {
//         echo "Error updating facility: " . mysqli_error($conn);
//     }
// }

// if (isset($_GET['delete_id'])) {
//     $facility_id = intval($_GET['delete_id']);
//     $sql = "DELETE FROM facilities WHERE facilityID = $facility_id";

//     if (mysqli_query($conn, $sql)) {
//         header("Location: facilities.php?deleted=1");
//         exit();
//     } else {
//         echo "Error deleting facility: " . mysqli_error($conn);
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ViaHale Dashboard</title>
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
    <a href="notifications.php"><i class="bi bi-bell"></i> Notifications</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
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
          <div class="row mb-4 g-2">
          <div class="col-md-4">
            <input type="text" class="form-control" id="searchInput" placeholder="Search facilities...">
          </div>
          <div class="col-md-3">
            <select class="form-select" id="typeFilter">
              <option value="">All Types</option>
              <option value="Office Building">Office Building</option>
              <option value="Laboratory">Laboratory</option>
              <option value="Warehouse">Warehouse</option>
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="statusFilter">
              <option value="">All Statuses</option>
              <option value="Operational">Operational</option>
              <option value="Maintenance Scheduled">Maintenance Scheduled</option>
            </select>
          </div>
        </div>
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
              <ion-icon name="add-circle-outline" class="me-1"></ion-icon> Add New Facility
            </button>
        </div>

    <!-- Facilities -->
    <div class="row g-4">
    <?php do { ?> 
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
                <button class="btn btn-outline-dark btn-sm view-details-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#facilityModal"
                    data-id="<?php echo $row['facility_id']; ?>"
                    data-name="<?php echo htmlspecialchars($row['facility_name'], ENT_QUOTES); ?>"
                    data-location="<?php echo htmlspecialchars($row['location'], ENT_QUOTES); ?>"
                    data-type="<?php echo htmlspecialchars($row['type'], ENT_QUOTES); ?>"
                    data-capacity="<?php echo $row['capacity']; ?>"
                    data-image="<?php echo $row['image']; ?>">
                    View Details
                </button>

                <button type="button" class="btn btn-danger btn-sm remove-btn" data-id="<?php echo $row['facility_id']; ?>">
                  <i class="bi bi-trash me-1"></i> Remove Facility
                </button>

                <button class="btn btn-primary btn-sm open-manage-btn"
                  data-bs-toggle="modal"
                  data-bs-target="#manageModal"
                  data-id="<?php echo $row['facility_id']; ?>"
                  data-name="<?php echo $row['facility_name']; ?>">
                  Manage
                </button>
              </div>
          </div>
        </div>
      </div>
    <?php } while ($row = $facilities->fetch_assoc()); ?>
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
            <h5 class="modal-title" id="manageModalLabel">🛎 Manage Facility</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <h6 class="fw-bold">Available Slots</h6>
            
            <ul class="list-group mb-4" id="slotList">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                9:00 AM - 11:00 AM
                <span class="badge bg-success rounded-pill">Available</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                1:00 PM - 3:00 PM
                <span class="badge bg-danger rounded-pill">Booked</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                4:00 PM - 6:00 PM
                <span class="badge bg-warning text-dark rounded-pill">Pending</span>
              </li>
            </ul>

            <button class="btn btn-primary btn-sm mb-3" id="addSlotBtn">➕ Add New Slot</button>

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
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="facilityModalLabel">
              <i class="bi bi-building"></i> Facility Details
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="row g-4">
              <!-- Facility Image -->
              <div class="col-md-5 text-center">
                <img src="" id="modalImage" class="img-fluid rounded border shadow-sm" alt="Facility Image">
              </div>

              <!-- View Mode -->
              <div class="col-md-7" id="viewMode">
                <h4 id="modalTitle" class="fw-bold mb-2"></h4>
                <p id="modalAddress" class="text-muted"><i class="bi bi-geo-alt-fill me-1"></i></p>
                <hr>
                <p><strong>Type:</strong> <span id="modalType"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus" class="badge bg-success"></span></p>
                <p><strong>Capacity:</strong> <span id="modalSize"></span></p>
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
              <button type="button" class="btn btn-primary" id="editBtn">
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
     document.querySelectorAll('.open-manage-btn').forEach(button => {
    button.addEventListener('click', function () {
      const name = this.getAttribute('data-name');
      const type = this.getAttribute('data-type');
      const status = this.getAttribute('data-status');

      document.getElementById('modalFacilityName').textContent = name;
      document.getElementById('modalFacilityType').textContent = type;
      document.getElementById('modalFacilityStatus').textContent = status;
    });
  });

  document.querySelectorAll('[data-bs-target="#facilityModal"]').forEach(btn => {
    btn.addEventListener('click', function () {
      const card = this.closest('.facility-card');
      const imgSrc = card.querySelector('img').src;
      const title = card.querySelector('h5').textContent;
      const address = card.querySelector('p:nth-of-type(1)').textContent;
      const type = card.querySelector('.type-label').textContent.replace("Type: ", "");
      const status = card.querySelector('.status-badge').textContent;
      const sizeEmployees = card.querySelectorAll('p')[3].textContent.split('|');

      document.getElementById('modalImage').src = imgSrc;
      document.getElementById('modalTitle').textContent = title;
      document.getElementById('modalAddress').textContent = address;
      document.getElementById('modalType').textContent = type;
      document.getElementById('modalStatus').textContent = status;
      document.getElementById('modalSize').textContent = sizeEmployees[0].trim();
      document.getElementById('modalEmployees').textContent = sizeEmployees[1].trim();
    });
  });

  function addSlot() {
    const slotGroup = document.createElement('div');
    slotGroup.classList.add('slot-group', 'mb-2');
    slotGroup.innerHTML = `
        <input type="text" name="slot_name[]" class="form-control mb-1" placeholder="Slot Name" required>
        <input type="time" name="slot_start[]" class="form-control mb-1" placeholder="Start Time" required>
        <input type="time" name="slot_end[]" class="form-control mb-1" placeholder="End Time" required>
    `;
    document.getElementById('slotContainer').appendChild(slotGroup);
}

document.addEventListener("DOMContentLoaded", function() {
  const manageModal = new bootstrap.Modal(document.getElementById('manageModal'));
  
  document.querySelectorAll('.open-manage-btn').forEach(button => {
    button.addEventListener('click', () => {
      const facility_id = button.getAttribute('data-id');
      const slotList = document.getElementById('slotList');
      slotList.innerHTML = '<li class="list-group-item">Loading slots...</li>';

      fetch(`get_slots.php?facility_ID=${facility_id}`)
        .then(response => response.json())
        .then(data => {
          slotList.innerHTML = '';
          data.forEach(slot => {
            const badgeClass = slot.availability === 'Available' ? 'bg-success'
                              : slot.availability === 'Booked' ? 'bg-danger'
                              : 'bg-secondary';

            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center';
            li.innerHTML = `${slot.slot_start} - ${slot.slot_end}
              <span class="badge ${badgeClass} rounded-pill">${slot.availability}</span>`;
            slotList.appendChild(li);
          });
        });
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const manageModal = document.getElementById("manageModal");

  manageModal.addEventListener("show.bs.modal", function (event) {
    const button = event.relatedTarget;
    const facility_id = button.getAttribute("data-id");
    const slotList = document.getElementById("slotList");

    // Clear old slots
    slotList.innerHTML = "<li class='list-group-item'>Loading slots...</li>";

    fetch(`get_slots.php?facility_ID=${facility_id}`)
      .then((response) => response.json())
      .then((data) => {
        slotList.innerHTML = ""; // Clear previous

        if (data.length === 0) {
          slotList.innerHTML = "<li class='list-group-item'>No slots available.</li>";
        } else {
          data.forEach((slot) => {
            let badgeClass = "bg-success";
            if (slot.availability === "Booked") badgeClass = "bg-danger";

            const listItem = `
              <li class="list-group-item d-flex justify-content-between align-items-center">
                ${slot.slot_start} - ${slot.slot_end}
                <span class="badge ${badgeClass} rounded-pill">${slot.availability}</span>
              </li>
            `;
            slotList.innerHTML += listItem;
          });
        }
      })
      .catch((error) => {
        slotList.innerHTML = "<li class='list-group-item text-danger'>Error loading slots</li>";
        console.error("Error fetching slots:", error);
      });
  });
});

//EDIT FACILITY
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("facilityModal");

  const modalImage = document.getElementById("modalImage");
  const modalTitle = document.getElementById("modalTitle");
  const modalAddress = document.getElementById("modalAddress");
  const modalType = document.getElementById("modalType");
  const modalStatus = document.getElementById("modalStatus");
  const modalSize = document.getElementById("modalSize");

  const viewMode = document.getElementById("viewMode");
  const editMode = document.getElementById("editMode");
  const viewButtons = document.getElementById("viewButtons");
  const editButtons = document.getElementById("editButtons");

  const editForm = document.getElementById("editFacilityForm");

  // Open modal and populate fields
  document.querySelectorAll(".view-details-btn").forEach(button => {
    button.addEventListener("click", () => {
      const facility_id = button.getAttribute("data-id");
      const name = button.getAttribute("data-name");
      const location = button.getAttribute("data-location");
      const type = button.getAttribute("data-type");
      const status = button.getAttribute("data-status");
      const capacity = button.getAttribute("data-capacity");
      const image = button.getAttribute("data-image");

      // View mode
      modalImage.src = image;
      modalTitle.textContent = name;
      modalAddress.textContent = location;
      modalType.textContent = type;
      modalStatus.textContent = status;
      modalSize.textContent = capacity;

      // Edit mode form values
      editForm.facility_id.value = facility_id;
      editForm.facility_name.value = facility_name;
      editForm.location.value = location;
      editForm.type.value = type;
      editForm.status.value = status;
      editForm.capacity.value = capacity;

      // Default to view mode
      toggleEdit(false);
    });
  });

  // Edit button
  document.getElementById("editBtn").addEventListener("click", () => toggleEdit(true));

  // Cancel button
  document.getElementById("cancelEditBtn").addEventListener("click", () => toggleEdit(false));

  // Toggle view/edit modes
  function toggleEdit(isEdit) {
    viewMode.classList.toggle("d-none", isEdit);
    viewButtons.classList.toggle("d-none", isEdit);
    editMode.classList.toggle("d-none", !isEdit);
    editButtons.classList.toggle("d-none", !isEdit);
  }

  // Save Changes via AJAX
  editForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(editForm);

    fetch("update_facility.php", {
      method: "POST",
      body: formData
    })
    .then(response => response.text())
    .then(result => {
      if (result.trim() === "success") {
        alert("Facility updated successfully!");
        location.reload(); // Refresh to reflect changes
      } else {
        alert("Update failed: " + result);
      }
    })
    .catch(err => {
      console.error("Error:", err);
      alert("Something went wrong.");
    });
  });
});

//DELETE FACILITY
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".remove-btn").forEach(button => {
    button.addEventListener("click", function () {
      const facility_id = this.getAttribute("data-id");

      if (confirm("Are you sure you want to remove this facility?")) {
        fetch('delete_facility.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'facility_id=' + encodeURIComponent(facility_id)
        })
        .then(response => response.text())
        .then(data => {
          if (data.trim() === "success") {
            alert("Facility removed successfully.");
            location.reload();
          } else {
            alert("Failed to remove facility.");
            location.reload();
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("Something went wrong.");
        });
      }
    });
  });
});
  </script>
</body>
</html>