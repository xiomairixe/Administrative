<?php
include ('connection.php');

$sql = "SELECT * FROM facilities";
$facilities = $conn->query($sql) or die ($conn->error);
// $row = $facilities->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ViaHale Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
  <!-- Sidebar -->
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="#" class="active"><i class="bi bi-person-plus"></i> Facilities</a>
    <a href="visitLog.php"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <div class="main-content">
    <div class="topbar mb-4">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle2" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </button>
        <nav class="nav">
          <a class="nav-link active" href="#">Home</a>
          <a class="nav-link" href="#">Contact</a>
        </nav>
      </div>
   
      <div class="profile">
        <div style="position:relative;">
          <i class="bi bi-bell"></i>
          <span class="badge">2</span>
        </div>
        <img src="# " class="profile-img" alt="profile">
        <div class="profile-info">
          <strong>R.Lance</strong><br>
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
        </div>

        <div class="row g-4">
          <?php while ($row = $facilities->fetch_assoc()) {
            $facility_id = $row['facility_id'];
            $facilityStatus = $row['status'];
                  // Determine badge color and label based on facility status
                  $badgeClass = 'bg-secondary';
                  $statusLabel = 'Available';

                  if ($facilityStatus === 'Pending') {
                    $badgeClass = 'bg-warning text-dark';
                    $statusLabel = 'Pending';
                  } elseif ($facilityStatus === 'Reserved') {
                    $badgeClass = 'bg-danger';
                    $statusLabel = 'Reserved';
                  } elseif ($facilityStatus === 'Active') {
                    $badgeClass = 'bg-success';
                    $statusLabel = 'Active';
                  }
          ?>

          <!--card markup here -->
          <div class="col-md-4 facility-card">
            <div class="card h-100 shadow-sm border-0">
              <img src="/Administrative/ReservationManagement/uploads/<?php echo $row['image']; ?>" class="card-img-top facility-image" alt="<?php echo $row['facility_name']; ?>">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo $row['facility_name']; ?></h5>
                <p class="card-text mb-1"><?php echo $row['description']; ?></p>
                <p class="text-muted mb-1"><strong>Location:</strong> <?php echo $row['location']; ?></p>
                <p class="text-muted mb-1"><strong>Capacity:</strong> <?php echo $row['capacity']; ?></p>

                <!--Status Badge -->
                <p class="mb-2">
                  <strong>Status:</strong>
                  <span class="badge <?php echo $badgeClass; ?>"><?php echo $statusLabel; ?></span>
                </p>

                <!--Action Buttons Based on Facility Status -->
                <div class="mt-auto d-flex justify-content-between">
                  <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#facilityModal"
                    data-img="/Administrative/LegalManagement/Legal/LegalAdmin/FacilityReservation/uploads<?php echo $row['image']; ?>"
                    data-name="<?php echo $row['facility_name']; ?>"
                    data-location="<?php echo $row['location']; ?>"
                    data-type="<?php echo $row['type']; ?>"
                    data-status="<?php echo $row['status']; ?>"
                    data-capacity="<?php echo $row['capacity']; ?>">
                    View Details
                  </button>

            <?php if ($facilityStatus === 'Reserved') { ?>
              <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                data-bs-target="#headVisitorModal<?php echo $facility_id; ?>">
                Approved
              </button>
            <?php } elseif ($facilityStatus === 'Pending') { ?>
              <button class="btn btn-warning btn-sm" disabled>Pending</button>
            <?php } elseif ($facilityStatus === 'Active') { ?>
              <button class="btn btn-success btn-sm" disabled>Active</button>
            <?php } else { ?>
              <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#manageModal" onclick="setfacility_id(<?php echo $facility_id; ?>)">Reserve</button>
            <?php } ?>
          </div>
          </div>
        </div>
      </div>  

    <!--Head Visitor Modal-->
    <div class="modal fade" id="headVisitorModal<?php echo $facility_id ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="action/register_head_visitor.php" method="post">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Register Head Visitor</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="reservation_id" value="<?php echo $facility_id; ?>">
              <div class="mb-3">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_number" class="form-control" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="register_head" class="btn btn-primary">Register Head Visitor</button>
            </div>
          </div>
        </form>
      </div>
    </div> <?php }  ?>

<!-- Head Visitor Modal -->
<div class="modal fade" id="headVisitorModal<?php echo $facility_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="action/register_head_visitor.php" method="post">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Register Head Visitor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="reservation_id" value="<?php echo $facility_id; ?>">
          <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Contact Number</label>
            <input type="text" name="contact_number" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="register_head" class="btn btn-primary">Register Head Visitor</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Facility Booking -->
<div class="modal fade" id="manageModal" tabindex="-1" aria-labelledby="manageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content border-0 shadow-lg">  
      <!-- Header -->
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="manageModalLabel">🛎 Manage Facility Booking</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="facilityForm" method="POST" action="action/process_reservation.php">
        <div class="modal-body">
          <input type="hidden" id="facility_id" name="facility_id">
          <div class="mb-3">
            <label for="purpose" class="form-label fw-bold">Purpose</label>
            <input type="text" class="form-control" id="purpose" name="purpose" placeholder="Enter purpose of use" required>
          </div>
          <div class="mb-3">
            <label for="numberOfUsers" class="form-label fw-bold">Number of Visitor (Including the requestor)</label>
            <input type="number" class="form-control" id="numberOfUsers" name="number_of_user" min="1" required>
          </div>
          <div class="mb-4">
            <label for="timeSlot" class="form-label fw-bold">Time Slot</label>
            <select class="form-select" id="timeSlot" name="slot" required>
              <option value="" selected disabled>Select a time slot</option>
              <option value="9:00 AM - 11:00 AM">9:00 AM - 11:00 AM</option>
              <option value="1:00 PM - 3:00 PM">1:00 PM - 3:00 PM</option>
              <option value="4:00 PM - 6:00 PM">4:00 PM - 6:00 PM</option>
            </select>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="submit" class="btn btn-success">💾 Send Request</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Facility Detail Modal -->
<div class="modal fade" id="facilityModal" tabindex="-1" aria-labelledby="facilityModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="facilityModalLabel">Facility Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img src="" id="modalImage" class="img-fluid rounded mb-3" alt="Facility Image">
        <h5 id="modalTitle"></h5>
        <p id="modalAddress" class="text-muted"></p>
        <p><strong>Type:</strong> <span id="modalType"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Size:</strong> <span id="modalSize"></span></p>
        <p><strong>Employees:</strong> <span id="modalEmployees"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Edit Facility</button>
      </div>
    </div>
  </div>
</div>
</div>

<!-- Modal Visitor Registration-->
<div class="modal fade" id="visitorModal" tabindex="-1" aria-labelledby="visitorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="visitorModalLabel">Register Visitors</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Add enctype for file upload -->
      <form id="visitorForm" method="POST" action="facility.php" enctype="multipart/form-data">
        <div class="modal-body">
          <input type="hidden" name="facility_name" id="visitorFacilityName">
          <div class="mb-3">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" class="form-control" name="fullname" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Contact Number</label>
            <input type="text" class="form-control" name="contact" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Valid ID (Upload)</label>
            <input type="file" class="form-control" name="valid_id" accept=".jpg,.jpeg,.png,.pdf" required>
            <small class="text-muted">Accepted formats: JPG, PNG, PDF</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Register Visitor</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!--Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script>
document.querySelectorAll('[data-bs-target="#facilityModal"]').forEach(button => {
  button.addEventListener('click', function () {
    const name = this.getAttribute('data-name');
    const location = this.getAttribute('data-location');
    const type = this.getAttribute('data-type');
    const status = this.getAttribute('data-status');
    const capacity = this.getAttribute('data-capacity');
    const img = this.getAttribute('data-img');

    document.getElementById('modalImage').src = img;
    document.getElementById('modalTitle').textContent = name;
    document.getElementById('modalAddress').textContent = location;
    document.getElementById('modalType').textContent = type;
    document.getElementById('modalStatus').textContent = status;
    document.getElementById('modalSize').textContent = "Capacity: " + capacity;
    document.getElementById('modalEmployees').textContent = "N/A";
  });
});

document.querySelectorAll('.open-manage-btn').forEach(button => {
  button.addEventListener('click', function () {
    const facility_id = this.getAttribute('data-facility-id');
    document.getElementById('facility_id').value = facility_id;
  });
});

document.querySelectorAll('.open-visitor-modal').forEach(button => {
  button.addEventListener('click', function () {
    const facilityName = this.getAttribute('data-facility-name');
    document.getElementById('visitorFacilityName').value = facilityName;
  });
});

function setfacility_id(facility_id) {
  document.getElementById('facility_id').value = facility_id;
  }

//VISITORS REGISTRATION
document.querySelectorAll('.open-visitor-modal').forEach(button => {
  button.addEventListener('click', function () {
    const facilityName = this.getAttribute('data-facility-name');
    const facility_id = this.getAttribute('data-facility-id');
    const numberOfUsers = this.getAttribute('data-number-of-users');

    document.getElementById('modalFacilityName').textContent = facilityName;
    document.getElementById('modalfacility_id').value = facility_id;

    const container = document.getElementById('visitorFieldsContainer');
    container.innerHTML = '';

    for (let i = 1; i <= numberOfUsers; i++) {
      const fieldset = document.createElement('div');
      fieldset.classList.add('mb-3');
      fieldset.innerHTML = `
        <h6>Visitor ${i}</h6>
        <input type="text" name="visitors[${i}][name]" class="form-control mb-2" placeholder="Full Name" required>
        <input type="text" name="visitors[${i}][company]" class="form-control mb-2" placeholder="Company/Organization" required>
        <input type="text" name="visitors[${i}][id_number]" class="form-control mb-2" placeholder="ID Number" required>
        <hr>
      `;
      container.appendChild(fieldset);
    }
  });
});
</script>
</body>
</html>