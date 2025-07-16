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
              <a class="nav-link" href="index.com"><ion-icon name="business-outline"></ion-icon>Overview</a>
              <a class="nav-link" href="facilities.php"><ion-icon name="build-outline"></ion-icon>Facilities</a>
              <a class="nav-link active" href="request.php"><ion-icon name="clipboard-outline"></ion-icon>Requests</a>
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

    <main class="col-md-10 main-content">
      <div class="topbar mb-4">
        <div class="d-flex align-items-center gap-3">
          <button class="sidebar-toggle d-lg-none" id="sidebarToggle2" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
          </button>
          <nav class="nav">
            <a class="nav-link" href="#">Home</a>
            <a class="nav-link" href="#">Contact</a>
          </nav>
        </div>

        <div class="profile">
          <div style="position:relative;">
            <i class="bi bi-bell"></i>
            <span class="badge">2</span>
          </div>
          <img src="#" class="profile-img" alt="profile">
          <div class="profile-info">
            <strong>R. Lance</strong><br>
            <small>Admin</small>
          </div>
        </div>
      </div>
      <div class="content-body">
          <!-- Actions -->
          <div class="mb-3 text-end">
            <button class="btn btn-outline-secondary me-2">Batch Reservation</button>
            <button class="btn btn-primary">New Reservation</button>
          </div>

          <!-- Tabs -->
          <ul class="nav nav-tabs mb-3" id="reservationTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#all-tab">All Reservations</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pending-tab">Pending</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#approved-tab">Approved</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#rejected-tab">Rejected</a></li>
          </ul>

          <!-- Filters -->
          <div class="d-flex flex-wrap gap-2 mb-4">
            <input class="form-control" type="search" placeholder="Search reservations..." style="max-width: 250px;" />
            <select class="form-select" style="max-width: 200px;">
              <option>All Spaces</option>
              <option>Conference Room A</option>
              <option>Auditorium</option>
              <option>Training Room</option>
            </select>
            <input type="date" class="form-control" style="max-width: 200px;" />
            <button class="btn btn-outline-secondary">More Filters</button>
          </div>

          <!-- Tab Content -->
          <div class="tab-content">
          <!-- All Tab -->
            <div class="tab-pane fade show active" id="all-tab">
              <?php while ($row = $all->fetch_assoc()): ?>
                <?php
                  $status = $row['status'];
                  $badgeClass = 'bg-secondary';
                  if ($status === 'Pending') {
                    $badgeClass = 'bg-warning text-dark';
                  } elseif ($status === 'Approved') {
                    $badgeClass = 'bg-success';
                  } elseif ($status === 'Rejected') {
                    $badgeClass = 'bg-danger';
                  }
                ?>
                <div class="reservation-card">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h5><?php echo $row['facility_name']; ?></h5>
                      <p class="mb-1"><?php echo $row['slot']; ?></p>
                      <p class="mb-1"><?php echo $row['purpose']; ?></p>
                    </div>
                    <div class="text-end">
                      <span class="badge <?php echo $badgeClass; ?> status-badge"><?php echo $status; ?></span><br>
                      <small>Jenna - IT</small>
                    </div>
                  </div>
                  <div class="mt-2">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDetailsModal<?php echo $row['id']; ?>">
                      View Details
                    </button>
                    
                    <?php if ($status === 'Pending') : ?>
                      <a href="update_status.php?id=<?php echo $row['id']; ?>&status=Approved" class="btn btn-success btn-sm">Approve</a>
                      <a href="update_status.php?id=<?php echo $row['id']; ?>&status=Rejected" class="btn btn-danger btn-sm">Reject</a>
                    <?php endif; ?>
                  </div>
                </div>
                <!-- Modal -->
                    <div class="modal fade" id="viewDetailsModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="viewDetailsLabel<?php echo $row['id']; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="viewDetailsLabel<?php echo $row['id']; ?>">Reservation Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <dl class="row">
                              <dt class="col-sm-4">Facility Name:</dt>
                              <dd class="col-sm-8"><?php echo $row['facility_name']; ?></dd>

                              <dt class="col-sm-4">Slot:</dt>
                              <dd class="col-sm-8"><?php echo $row['slot']; ?></dd>

                              <dt class="col-sm-4">Purpose:</dt>
                              <dd class="col-sm-8"><?php echo $row['purpose']; ?></dd>

                              <dt class="col-sm-4">Status:</dt>
                              <dd class="col-sm-8"><?php echo $row['status']; ?></dd>

                              <dt class="col-sm-4">Requested By:</dt>
                              <dd class="col-sm-8"><?php echo $row['requested_by'] ?? 'N/A'; ?></dd>

                              <dt class="col-sm-4">Date Requested:</dt>
                              <dd class="col-sm-8"><?php echo $row['date_requested'] ?? 'N/A'; ?></dd>
                            </dl>
                          </div>
                        </div>
                      </div>
                    </div>
              <?php endwhile; ?>
            </div>
            <!-- Pending Tab -->
            <div class="tab-pane fade" id="pending-tab">
              <?php while ($row = $pending->fetch_assoc()): ?>
                <div class="reservation-card">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h5><?php echo $row['facility_name']; ?></h5>
                      <p class="mb-1"><?php echo $row['slot']; ?></p>
                      <p class="mb-1"><?php echo $row['purpose']; ?></p>
                    </div>
                    <div class="text-end">
                      <span class="badge bg-warning text-dark status-badge">Pending</span><br>
                      <small>Leo - Admin</small>
                    </div>
                  </div>
                  <div class="mt-2">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDetailsModal<?php echo $row['id']; ?>">
                      View Details
                    </button>
                    
                    <a href="update_status.php?id=<?php echo $row['id']; ?>&status=Approved" class="btn btn-success btn-sm">Approve</a>
                    <a href="update_status.php?id=<?php echo $row['id']; ?>&status=Rejected" class="btn btn-danger btn-sm">Reject</a>
                  </div>
                </div>
                <!-- Modal -->
                    <div class="modal fade" id="viewDetailsModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="viewDetailsLabel<?php echo $row['id']; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="viewDetailsLabel<?php echo $row['id']; ?>">Reservation Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <dl class="row">
                              <dt class="col-sm-4">Facility Name:</dt>
                              <dd class="col-sm-8"><?php echo $row['facility_name']; ?></dd>

                              <dt class="col-sm-4">Slot:</dt>
                              <dd class="col-sm-8"><?php echo $row['slot']; ?></dd>

                              <dt class="col-sm-4">Purpose:</dt>
                              <dd class="col-sm-8"><?php echo $row['purpose']; ?></dd>

                              <dt class="col-sm-4">Status:</dt>
                              <dd class="col-sm-8"><?php echo $row['status']; ?></dd>

                              <dt class="col-sm-4">Requested By:</dt>
                              <dd class="col-sm-8"><?php echo $row['requested_by'] ?? 'N/A'; ?></dd>

                              <dt class="col-sm-4">Date Requested:</dt>
                              <dd class="col-sm-8"><?php echo $row['date_requested'] ?? 'N/A'; ?></dd>
                            </dl>
                          </div>
                        </div>
                      </div>
                    </div>
              <?php endwhile; ?>
            </div>

            <!-- Approved Tab -->
            <div class="tab-pane fade" id="approved-tab">
            <?php while ($row = $approved->fetch_assoc()): ?>
              <div class="reservation-card">
                <div class="d-flex justify-content-between">
                  <div>
                    <h5><?php echo $row['facility_name']; ?></h5>
                    <p class="mb-1"><?php echo $row['slot']; ?></p>
                    <p class="mb-1"><?php echo $row['purpose']; ?></p>
                  </div>
                  <div class="text-end">
                    <span class="badge bg-success status-badge">Approved</span><br>
                    <small>Jenna - IT</small>
                  </div>
                </div>
                <div class="mt-2">
                  <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDetailsModal<?php echo $row['id']; ?>">
                    View Details
                  </button>
                  <!-- Register Visitors Button here -->
                  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerVisitorModal<?php echo $row['id']; ?>">
                    Register Visitors
                  </button>
                </div>
              </div>
            <?php endwhile; ?>
            </div>

            <!-- Rejected Tab -->
            <div class="tab-pane fade" id="rejected-tab">
            <?php while ($row = $rejected->fetch_assoc()): ?>
              <div class="reservation-card">
                <div class="d-flex justify-content-between">
                  <div>
                    <h5><h5><?php echo $row['facility_name']; ?></h5></h5>
                    <p class="mb-1"><?php echo $row['slot']; ?></p>
                    <p class="mb-1"><?php echo $row['purpose']; ?></p>
                  </div>
                  <div class="text-end">
                    <span class="badge bg-danger status-badge">Rejected</span><br>
                    <small>Jenna - IT</small>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
            </div>
  </main>
</div>

<!-- Chart.js Scripts -->
<script>
  // Sidebar toggle for mobile
  const sidebarToggle = document.getElementById('sidebarToggle2');
  const sidebarNav = document.querySelector('.sidebar');
  sidebarToggle?.addEventListener('click', function () {
    sidebarNav.classList.toggle('show');
  });
  document.addEventListener('click', function (e) {
    if (window.innerWidth <= 900 && sidebarNav.classList.contains('show')) {
      if (!sidebarNav.contains(e.target) && !sidebarToggle.contains(e.target)) {
        sidebarNav.classList.remove('show');
      }
    }
  });
</script>
</body>
</html>
