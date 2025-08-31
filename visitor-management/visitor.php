<?php
  include('../connection.php');

  // Get search and status filter from GET
  $search = isset($_GET['search']) ? trim($_GET['search']) : '';
  $status = isset($_GET['status']) ? trim($_GET['status']) : '';

  $where = [];
  $params = [];
  $types = '';

  if ($search !== '') {
    $where[] = "(full_name LIKE ? OR email LIKE ? OR company LIKE ? OR host_name LIKE ?)";
    $searchVal = "%$search%";
    $params = array_merge($params, [$searchVal, $searchVal, $searchVal, $searchVal]);
    $types .= 'ssss';
  }
  if ($status !== '' && $status !== 'All Statuses') {
    $where[] = "visit_status = ?";
    $params[] = $status;
    $types .= 's';
  }

  $sql = "SELECT * FROM visitors";
  if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
  }
  $sql .= " ORDER BY visit_datetime DESC";

  $stmt = $conn->prepare($sql);
  if ($params) {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $visitors = $stmt->get_result();
  } else {
    $visitors = $conn->query($sql) or die($conn->error);
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visitors List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    .table-avatar { border-radius: 50%; object-fit: cover; border: 2px solid #e0e7ff; }
    .badge-yes { background: #22c55e; }
    .badge-no { background: #f87171; }
    .visitors-table th, .visitors-table td { vertical-align: middle; text-align: center; font-size: 1rem; }
    .visitors-table th { background: #f8f9fa; font-weight: 600; }
    .visitors-table td { background: #fff; }
    .visitors-table .badge { font-size: 0.95rem; padding: 0.5em 0.7em; }
    .visitors-table .btn-sm { font-size: 0.95rem; padding: 0.25em 0.7em; }
    .visitors-table tr { border-bottom: 1px solid #e5e7eb; }
    .visitors-table tbody tr:last-child { border-bottom: none; }
    .table-responsive { border-radius: 12px; overflow: hidden; }
  </style>
</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="visitLog.php"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <a href="#" class="active"><i class="bi bi-person-lines-fill"></i> Visitors</a>
    <a href="blacklisted.php"><i class="bi bi-slash-circle"></i> Blacklist</a>
    <a href="security.php"><i class="bi bi-shield-lock"></i> Security</a>
    <hr>
    <a href="submenu/account.php"><i class="bi bi-person"></i> Account</a>
    <a href="submenu/setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="submenu/help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <div class="main-content">
    <div class="topbar mb-4">
      <div class="d-flex align-items-center gap-3">
            <div class=" justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div class="dashboard-title">Visitors</div>
      <div class="breadcrumbs">View and search all registered visitors</div>
    </div>  
    <button class="sidebar-toggle d-lg-none" id="sidebarToggle2" aria-label="Toggle sidebar">
      <i class="bi bi-list"></i>
    </button>
  </div>
  
  <div class="profile">
    <div style="position:relative;">
      <i class="bi bi-bell"></i>
      <span class="badge">2</span>
    </div>
    <img src="#" class="profile-img" alt="profile">
    <div class="profile-info">
      <strong>R.Lance</strong><br>
      <small>Admin</small>
    </div>
  </div>
  </div>

    <div class="container-fluid px-0">
      <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
        <form class="row g-2 align-items-center mb-4" method="get" id="searchFilterForm">
          <div class="col-md-9 mb-2 mb-md-0">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
              <input type="text" class="form-control border-start-0" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name, email, company...">
            </div>
          </div>
          <div class="col-md-3">
            <select class="form-select" name="status" onchange="document.getElementById('searchFilterForm').submit();">
              <option value="">All Statuses</option>
              <option value="Pre-registered" <?= $status == 'Pre-registered' ? 'selected' : '' ?>>Pre-registered</option>
              <option value="Checked In" <?= $status == 'Checked In' ? 'selected' : '' ?>>Checked In</option>
              <option value="Checked Out" <?= $status == 'Checked Out' ? 'selected' : '' ?>>Checked Out</option>
            </select>
          </div>
        </form>
      </div>
      <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
        <div class="table-responsive">
          <table class="table visitors-table align-middle mb-0">
            <thead>
              <tr>
                <th style="width:60px;">Face</th>
                <th style="width:140px;">Name</th>
                <th style="width:120px;">Company</th>
                <th style="width:120px;">Contact</th>
                <th style="width:120px;">Purpose</th>
                <th style="width:140px;">Date & Time</th>
                <th style="width:120px;">Host</th>
                <th style="width:100px;">Type</th>
                <th style="width:90px;">Details</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $visitors->fetch_assoc()): ?>
              <tr>
                <td>
                  <?php if (!empty($row['face_data'])): ?>
                    <img src="<?= $row['face_data'] ?>" alt="Face" width="44" height="44" class="table-avatar">
                  <?php else: ?>
                    <span class="text-muted"><i class="bi bi-person-circle fs-3"></i></span>
                  <?php endif; ?>
                </td>
                <td><strong><?= htmlspecialchars($row['full_name']) ?></strong></td>
                <td><?= htmlspecialchars($row['company']) ?></td>
                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                <td><?= htmlspecialchars($row['purpose']) ?></td>
                <td>
                  <span class="badge bg-light text-dark"><?= htmlspecialchars(date('M d, Y H:i', strtotime($row['visit_datetime']))) ?></span>
                </td>
                <td><?= htmlspecialchars($row['host_name']) ?></td>
                <td>
                  <span class="badge bg-info text-dark"><?= htmlspecialchars($row['visitor_type']) ?></span>
                </td>
                <td>
                  <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal<?= $row['visitor_id'] ?>">
                    <i class="bi bi-eye"></i> View
                  </button>
                </td>
              </tr>
              <!-- Modal for full details -->
              <div class="modal fade" id="detailsModal<?= $row['visitor_id'] ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?= $row['visitor_id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="detailsModalLabel<?= $row['visitor_id'] ?>">Visitor Full Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row mb-3">
                        <div class="col-md-3 text-center">
                          <?php if (!empty($row['face_data'])): ?>
                            <img src="<?= $row['face_data'] ?>" alt="Face" width="90" height="90" class="table-avatar mb-2">
                          <?php else: ?>
                            <span class="text-muted"><i class="bi bi-person-circle fs-1"></i></span>
                          <?php endif; ?>
                          <div class="mt-2">
                            <span class="badge bg-info text-dark"><?= htmlspecialchars($row['visitor_type']) ?></span>
                          </div>
                        </div>
                        <div class="col-md-9">
                          <dl class="row mb-0">
                            <dt class="col-sm-4">Full Name:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['full_name']) ?></dd>
                            <dt class="col-sm-4">Company:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['company']) ?></dd>
                            <dt class="col-sm-4">Contact Number:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['contact_number']) ?></dd>
                            <dt class="col-sm-4">Email:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['email']) ?></dd>
                            <dt class="col-sm-4">Purpose of Visit:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['purpose']) ?></dd>
                            <dt class="col-sm-4">Host Name:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['host_name']) ?></dd>
                            <dt class="col-sm-4">Date & Time:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['visit_datetime']) ?></dd>
                            <dt class="col-sm-4">Visit Duration:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['visit_duration']) ?></dd>
                            <dt class="col-sm-4">Notes:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['notes']) ?></dd>
                            <dt class="col-sm-4">Valid ID Type:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['id_type']) ?></dd>
                            <dt class="col-sm-4">Valid ID Number:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['id_number']) ?></dd>
                            <dt class="col-sm-4">Vehicle Plate:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['vehicle_plate']) ?></dd>
                            <dt class="col-sm-4">Repeat Visitor:</dt>
                            <dd class="col-sm-8"><?= !empty($row['repeat_flag']) ? 'Yes' : 'No' ?></dd>
                            <dt class="col-sm-4">Consent Biometric:</dt>
                            <dd class="col-sm-8"><?= !empty($row['consent_biometric']) ? 'Yes' : 'No' ?></dd>
                            <dt class="col-sm-4">Consent Privacy:</dt>
                            <dd class="col-sm-8"><?= !empty($row['consent_privacy']) ? 'Yes' : 'No' ?></dd>
                            <dt class="col-sm-4">Access Level:</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($row['access_level']) ?></dd>
                            <dt class="col-sm-4">Signature:</dt>
                            <dd class="col-sm-8">
                              <?php if (!empty($row['signature'])): ?>
                                <img src="<?= $row['signature'] ?>" alt="Signature" width="120" height="40" style="object-fit:contain;background:#fff;border-radius:6px;border:1px solid #e0e7ff;">
                              <?php endif; ?>
                            </dd>
                          </dl>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endwhile; ?>
              <?php if ($visitors->num_rows === 0): ?>
                <tr><td colspan="9" class="text-center text-muted">No visitor records found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>