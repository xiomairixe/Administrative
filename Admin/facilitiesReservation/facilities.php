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
        $target_dir = "/Administrative/ReservationManagement/uploads";
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
  <meta charset="UTF-8">
  <title>Legal Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="../style.css">
  <style>
  body {
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
      background: #fafbfc;
      color: #22223b;
      font-size: 16px;
    }

    /* Sidebar */
    .sidebar {
      background: #181818ff;
      color: #fff;
      min-height: 100vh;
      border: none;
      width: 220px;
      position: fixed;
      left: 0;
      top: 0;
      z-index: 1040;
      transition: left 0.3s;
      overflow-y: auto;
      padding: 1rem 0.3rem 1rem 0.3rem;
      scrollbar-width: none; /* Firefox */
      height: 100vh;
      -ms-overflow-style: none;  /* IE/Edge */
    }
    .sidebar::-webkit-scrollbar {
      display: none;    
      width: 0px;
      background: transparent;
      display: none; /* Chrome, Safari, Opera */
    }
    .sidebar a, .sidebar button {
      color: #bfc7d1;
      background: none;
      border: none;
      font-size: 0.95rem;
      padding: 0.45rem 0.7rem;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 0.7rem;
      margin-bottom: 0.1rem;
      transition: background 0.2s, color 0.2s;
      width: 100%;
      text-align: left;
      white-space: nowrap;
    }
    .sidebar a.active,
    .sidebar a:hover,
    .sidebar button.active,
    .sidebar button:hover {
      background: linear-gradient(90deg, #9A66ff 0%, #4311a5 100%);
      color: #fff;
    }
    .sidebar hr {
      border-top: 1px solid #232a43;
      margin: 0.7rem 0;
    }
    .sidebar .nav-link ion-icon {
      font-size: 1.2rem;
      margin-right: 0.3rem;
    }

    /* Topbar */
    .topbar {
      padding: 0.7rem 1.2rem 0.7rem 1.2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-top: 0 !important;
    }
    .topbar .nav-link {
      color: #22223b;
      font-weight: 500;
      font-size: 1.08rem;
      background: none;
      border: none;
    }
    .topbar .nav-link.active,
    .topbar .nav-link:hover { 
      text-decoration: underline;
    }
    .topbar .profile {
      display: flex;
      align-items: center;
      gap: 1.2rem;
    }
    .topbar .profile .bi-bell {
      font-size: 1.5rem;
      color: #9a66ff;
      position: relative;
    }
    .topbar .profile .badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #9a66ff;
      color: #fff;
      font-size: 0.7rem;
      border-radius: 50%;
      padding: 2px 6px;
    }
    .topbar .profile-img {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 0.7rem;
      border: 2px solid #e0e7ff;
    }
    .topbar .profile-info {
      line-height: 1.1;
    }
    .topbar .profile-info strong {
      font-size: 1.08rem;
      font-weight: 600;
      color: #22223b;
    }
    .topbar .profile-info small {
      color: #6c757d;
      font-size: 0.93rem;
    }

    /* Dashboard Title & Breadcrumbs */
    .dashboard-title {
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
      font-size: 1.7rem;
      font-weight: 700;
      margin-bottom: 1.2rem;
      color: #22223b;
    }
    .breadcrumbs {
      color: #3b82f6;
      font-size: 0.98rem;
      text-align: right;
    }

    /* Stats Cards */
    .stats-cards {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 2.2rem;
      flex-wrap: wrap;
    }
    .stats-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 8px rgba(140, 140, 200, 0.07);
      flex: 1;
      padding: 1.5rem 1.2rem;
      text-align: center;
      min-width: 170px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
      border: 1px solid #f0f0f0;
    }
    .stats-card .icon {
      background: #ede9fe;
      color: #4311a5;
      border-radius: 50%;
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }
    .stats-card .label {
      font-size: 1.08rem;
      color: #6c757d;
      margin-bottom: 0.2rem;
    }
    .stats-card .value {
      font-size: 1.6rem;
      font-weight: 700;
      color: #22223b;
    }

    /* Dashboard Row & Cards */
    .dashboard-row {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }
    .dashboard-col {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 8px rgba(140, 140, 200, 0.07);
      padding: 1.5rem 1.2rem;
      flex: 1;
      min-width: 0;
      min-width: 320px;
      margin-bottom: 1rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      border: 1px solid #f0f0f0;
    }
    .dashboard-col h5 {
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
      font-size: 1.13rem;
      font-weight: 600;
      margin-bottom: 1.1rem;
      color: #22223b;
    }

    /* Table */
    .table {
      font-size: 0.98rem;
      color: #22223b;
    }
    .table th {
      color: #6c757d;
      font-weight: 600;
      border: none;
      background: transparent;
    }
    .table td {
      border: none;
      background: transparent;
    }

    /* Status Badge */
    .status-badge {
      padding: 3px 12px;
      border-radius: 12px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
    }
    .status-badge.online {
      background: #dbeafe;
      color: #2563eb;
    }
    .status-badge.offline {
      background: #fee2e2;
      color: #b91c1c;
    }

    /* Responsive */

    @media (max-width: 1200px) {
      .main-content {
        padding: 1rem 0.3rem 1rem 0.3rem;
      }
      .sidebar {
        width: 180px;
        padding: 1rem 0.3rem;
      }
      .main-content {
        margin-left: 180px;
      }
    }

    @media (max-width: 900px) {
      .sidebar {
        left: -220px;
        width: 180px;
        padding: 1rem 0.3rem;
      }
      .sidebar.show {
        left: 0;
      }
      .main-content {
        margin-left: 0;
        padding: 1rem 0.5rem 1rem 0.5rem;
      }
      .sidebar-toggle {
        display: block;
      }
      .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.7rem 0.5rem;
      }
      .topbar .profile {
        margin-top: 0.7rem;
      }
    }
    @media (max-width: 700px) {
      .dashboard-title {
        font-size: 1.1rem;
      }
      .main-content {
        padding: 0.7rem 0.2rem 0.7rem 0.2rem;
      }
      .card-summary h4 {
        font-size: 1.1rem;
      }
      .sidebar {
        width: 100vw;
        left: -100vw;
        padding: 0.7rem 0.2rem;
      }
      .sidebar.show {
        left: 0;
      }
      .main-content {
        padding: 0.3rem 0.1rem;
      }
      .sidebar .logo {
        font-size: 1rem;
      }
      .sidebar a, .sidebar button {
        font-size: 0.93rem;
        padding: 0.4rem 0.5rem;
      }
      .sidebar .nav-link ion-icon {
        font-size: 1rem;
      }
      .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.7rem 0.2rem;
      }
      .topbar .profile {
        margin-top: 0.5rem;
      }
    }
    @media (max-width: 500px) {
      .sidebar {
        width: 100vw;
        left: -100vw;
        padding: 0.3rem 0.01rem;
      }
      .sidebar.show {
        left: 0;
      }
      .main-content {
        padding: 0.1rem 0.01rem;
      }
      .card-summary {
        font-size: 0.85rem;
        padding: 0.5rem 0.1rem;
      }
      .card-summary h4 {
        font-size: 0.85rem;
      }
      .btn, .form-select {
        width: 100% !important;
        margin-bottom: 0.5rem;
      }
      .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.7rem 0.2rem;
      }
      .topbar .profile {
        margin-top: 0.5rem;
      }
    }
    @media (min-width: 1400px) {
      .sidebar {
        width: 260px;
        padding: 2rem 1rem 2rem 1rem;
      }
      .main-content {
        margin-left: 260px;
        padding: 2rem 2rem 2rem 2rem;
      }
      .topbar {
        padding: 1.2rem 2rem 1.2rem 2rem;
      }
    }
    .facility-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
  </style>
</head>

<body>
<div class="container-fluid p-0">
  <div class="row g-0">

    <!-- Sidebar Column -->
    <div class="sidenav col-auto p-0">
      <div class="sidebar d-flex flex-column justify-content-between shadow-sm border-end">

        <!-- Top Section -->
        <div class="">
          <div class="d-flex justify-content-center align-items-center mb-5 mt-3">
            <img src="\Administrative\asset\image.png" class="img-fluid me-2" style="height: 55px;" alt="Logo">
          </div>

          <div class="mb-4">
            <h6 class="text-uppercase mb-2">Main</h6>
            <nav class="nav flex-column">
              <a class="nav-link" href="/Administrative/Admin/index.php"><ion-icon name="home-outline"></ion-icon>Dashboard</a>
              <a class="nav-link" href="/Administrative/Admin/regulatory.php"><ion-icon name="newspaper-outline"></ion-icon>Regulatory</a>
              <a class="nav-link" href="/Administrative/Admin/legalCases.php"><ion-icon name="document-text-outline"></ion-icon>Legal Request</a>
              <a class="nav-link" href="/Administrative/Admin/reports.php"><ion-icon name="bar-chart-outline"></ion-icon>Reports</a>
              <a class="nav-link" href="/Administrative/Admin/accessControl.php"><ion-icon name="key-outline"></ion-icon>Access Control</a>
              <a class="nav-link" href="/Administrative/Admin/notifications.php"><ion-icon name="notifications-outline"></ion-icon>Notifications</a>
            </nav>
          </div>

          <!-- Facility Reservation -->
          <div class="mb-4">
            <h6 class="text-uppercase px-2 mb-2">Facility Reservation</h6>
            <nav class="nav flex-column">
              <a class="nav-link" href="index.php"><ion-icon name="business-outline"></ion-icon>Overview</a>
              <a class="nav-link active" href="#"><ion-icon name="build-outline"></ion-icon>Facilities</a>
              <a class="nav-link" href="request.php"><ion-icon name="clipboard-outline"></ion-icon>Requests</a>
              <a class="nav-link" href="history.php"><ion-icon name="time-outline"></ion-icon>History</a>
            </nav>
          </div>

          <!-- Document Management -->
          <div class="mb-4">
            <h6 class="text-uppercase px-2 mb-2">Document Management</h6>
            <nav class="nav flex-column">
              <a class="nav-link" href="/Administrative/Admin/documentManagement/index.php"><ion-icon name="folder-outline"></ion-icon>Documents</a>
              <a class="nav-link" href="/Administrative/Admin/documentManagement/review&approve.php"><ion-icon name="checkmark-done-outline"></ion-icon>Review & Approve</a>
              <a class="nav-link" href="/Administrative/Admin/documentManagement/countersign.php"><ion-icon name="pencil-outline"></ion-icon>Countersign</a>
              <a class="nav-link" href="/Administrative/Admin/documentManagement/release.php"><ion-icon name="cloud-upload-outline"></ion-icon>Release</a>
              <a class="nav-link" href="/Administrative/Admin/documentManagement/archive.php"><ion-icon name="archive-outline"></ion-icon>Archive</a>
              <a class="nav-link" href="/Administrative/Admin/documentManagement/trash.php"><ion-icon name="trash-outline"></ion-icon>Trash</a>
            </nav>
          </div>
        </div>

        <!-- Logout -->
        <div class="p-3 border-top mb-2">
          <a class="nav-link text-danger" href="/Administrative/login.php">
            <ion-icon name="log-out-outline"></ion-icon>Logout
          </a>
        </div>
      </div>
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
            <button class="btn" style="background: #ab83fcff;" data-bs-toggle="modal" data-bs-target="#addFacilityModal">
              <ion-icon name="add-circle-outline" class="me-1"></ion-icon> Add New Facility
            </button>
        </div>

    <!-- Facilities -->
    <div class="row g-4">
    <?php do { ?> 
    <div class="col-md-4">
        <div class="facility-card">
          <img src="/Administrative/ReservationManagement/uploads/<?php echo $row['image'];?>" class="facility-image" alt="<?php echo $row['facility_name']; ?>">
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
                <img src="/Administrative/ReservationManagement/uploads/<?php echo $row['image'];?>" id="modalImage" class="img-fluid rounded border shadow-sm" alt="Facility Image">
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

    modalImage.src = "/Administrative/ReservationManagement/uploads/" + image; 
    modalTitle.textContent = name;
    modalAddress.textContent = location;
    modalType.textContent = type;
    modalStatus.textContent = status;
    modalSize.textContent = capacity;

    editForm.facility_id.value = facility_id;
    editForm.facility_name.value = name;
    editForm.location.value = location;
    editForm.type.value = type;
    editForm.status.value = status;
    editForm.capacity.value = capacity;

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

    fetch("action/update_facility.php", {
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
        fetch('action/delete_facility.php', {
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
            alert("Facility removed successfully.");
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