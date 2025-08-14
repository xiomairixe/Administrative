<?php
 include ('connection.php');
  $sql = "SELECT * FROM reservation_requests";
  $facility = $conn->query($sql) or die ($conn->error);
  $row = $facility->fetch_assoc();

  $request = $conn->query("SELECT * FROM reservation_requests ORDER BY request_id DESC");
  $pending = $conn->query("SELECT * FROM reservation_requests WHERE status = 'Pending' ORDER BY request_id DESC");
  $approved = $conn->query("SELECT * FROM reservation_requests WHERE status = 'Approved' ORDER BY request_id DESC");
  $rejected = $conn->query("SELECT * FROM reservation_requests WHERE status = 'Rejected' ORDER BY request_id DESC");
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
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="facilities.php"><i class="bi bi-building"></i> Facilities</a>
    <a href="#" class="active"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

  <!-- Main Content -->
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
          <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#all-tab" role="tab">All Reservations</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pending-tab" role="tab">Pending</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#approved-tab" role="tab">Approved</a></li>
          <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#rejected-tab" role="tab">Rejected</a></li>
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
          <div class="tab-pane fade show active" id="all-tab" role="tabpanel">
            <?php while ($row = $request->fetch_assoc()): ?>
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
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDetailsModal<?php echo $row['request_id']; ?>">
                  View Details
                </button>
                    
                <?php if ($status === 'Pending') : ?>
                <a href="action/update_status.php?request_id=<?php echo $row['request_id']; ?>&status=Approved" class="btn btn-success btn-sm">Approve</a>
                <a href="action/update_status.php?request_id=<?php echo $row['request_id']; ?>&status=Rejected" class="btn btn-danger btn-sm">Reject</a>
                <?php endif; ?>
              </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="viewDetailsModal<?php echo $row['request_id']; ?>" tabindex="-1" aria-labelledby="viewDetailsLabel<?php echo $row['id']; ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsLabel<?php echo $row['request_id']; ?>">Reservation Details</h5>
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
          <div class="tab-pane fade" id="pending-tab" role="tabpanel">
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
                  <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDetailsModal<?php echo $row['reservation_id']; ?>">
                    View Details
                  </button>
                    
                  <a href="action/update_status.php?request_id=<?php echo $row['request_id']; ?>&status=Approved" class="btn btn-success btn-sm">Approve</a>
                  <a href="action/update_status.php?request_id=<?php echo $row['request_id']; ?>&status=Rejected" class="btn btn-danger btn-sm">Reject</a>
                </div>
              </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="viewDetailsModal<?php echo $row['request_id']; ?>" tabindex="-1" aria-labelledby="viewDetailsLabel<?php echo $row['request_id']; ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsLabel<?php echo $row['request_id']; ?>">Reservation Details</h5>
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
          <div class="tab-pane fade" id="approved-tab" role="tabpanel">
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
                  <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#viewDetailsModal<?php echo $row['request_id']; ?>">
                    View Details
                  </button>
                  <!-- Register Visitors Button here -->
                  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerVisitorModal<?php echo $row['request_id']; ?>">
                    Register Visitors
                  </button>
                </div>
              </div>
            <?php endwhile; ?>
          </div>

          <!-- Rejected Tab -->
          <div class="tab-pane fade" id="rejected-tab" role="tabpanel">
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

  <!-- Scripts -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarNav = document.getElementById('sidebarNav');
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