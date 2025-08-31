<?php
  include ('../connection.php');

  // Dashboard stats
  $totalVisitors = $conn->query("SELECT COUNT(*) AS cnt FROM visitors")->fetch_assoc()['cnt'];
  $activeVisitors = $conn->query("SELECT COUNT(*) AS cnt FROM visitors WHERE visit_status = 'Checked In'")->fetch_assoc()['cnt'];
  $completedVisits = $conn->query("SELECT COUNT(*) AS cnt FROM visitors WHERE visit_status = 'Checked Out'")->fetch_assoc()['cnt'];
  $pendingVisits = $conn->query("SELECT COUNT(*) AS cnt FROM visitors WHERE visit_status = 'Pre-registered'")->fetch_assoc()['cnt'];

  // Recent visitors
  $recentVisitors = $conn->query("SELECT * FROM visitors ORDER BY visit_datetime DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visitor Management</title>
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
    <a href="#" class="active"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="visitLog.php"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <a href="visitor.php"><i class="bi bi-person-lines-fill"></i> Visitors</a>
    <a href="blacklisted.php"><i class="bi bi-slash-circle"></i> Blacklist</a>
    <a href="security.php"><i class="bi bi-shield-lock"></i> Security</a>
    <hr>
    <a href="submenu/account.php"><i class="bi bi-person"></i> Account</a>
    <a href="submenu/setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="submenu/help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar mb-4">
      <div class="d-flex align-items-center gap-3">
            <div class=" justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div class="dashboard-title">Dashboard</div>
      <div class="breadcrumbs">Home &nbsp;/&nbsp; Dashboard</div>
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

  <!-- Stats Cards -->
  <div class="stats-cards mb-4">
    <div class="stats-card">
      <div class="icon"><i class="bi bi-person-walking"></i></div>
      <div class="label">Total Visitors</div>
      <div class="value"><?= $totalVisitors ?></div>
    </div>
    <div class="stats-card">
      <div class="icon"><i class="bi bi-people"></i></div>
      <div class="label">Active Visitors</div>
      <div class="value"><?= $activeVisitors ?></div>
    </div>
    <div class="stats-card">
      <div class="icon"><i class="bi bi-check2-circle"></i></div>
      <div class="label">Completed</div>
      <div class="value"><?= $completedVisits ?></div>
    </div>
    <div class="stats-card">
      <div class="icon"><i class="bi bi-geo-alt"></i></div>
      <div class="label">Pending Visits</div>
      <div class="value"><?= $pendingVisits ?></div>
    </div>
  </div>

    <!-- Recent Visitors Table -->
  <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
    <div class="mb-3 d-flex justify-content-end">
      <a href="action/export_visitors_report.php" class="btn btn-success">
        <i class="bi bi-file-earmark-excel"></i> Generate Visitors Report (Excel)
      </a>
    </div>
    <h6 class="mb-3" style="font-weight:700;">Recent Visitors</h6>
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Name</th>
          <th>Company</th>
          <th>Contact</th>
          <th>Date & Time</th>
          <th>Status</th>
          <th>Host</th>
        </tr>
      </thead>
      
      <tbody>
        <?php while ($row = $recentVisitors->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['full_name']) ?></td>
          <td><?= htmlspecialchars($row['company']) ?></td>
          <td><?= htmlspecialchars($row['contact_number']) ?></td>
          <td><?= htmlspecialchars(date('M d, Y H:i', strtotime($row['visit_datetime']))) ?></td>
          <td>
            <span class="badge <?= $row['visit_status'] == 'Checked In' ? 'bg-success' : ($row['visit_status'] == 'Checked Out' ? 'bg-secondary' : 'bg-warning text-dark') ?>">
              <?= htmlspecialchars($row['visit_status']) ?>
            </span>
          </td>
          <td><?= htmlspecialchars($row['host_name']) ?></td>
        </tr>
        <?php endwhile; ?>
        <?php if ($recentVisitors->num_rows === 0): ?>
          <tr><td colspan="6" class="text-center text-muted">No recent visitors.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Analytics Section Example -->
  <div class="row g-4 mb-4">
    <div class="col-md-6">
      <div class="bg-white rounded-3 shadow-sm p-4">
        <h6 class="mb-3" style="font-weight:700;">Visitor Status Distribution</h6>
        <?php
          $statusData = [
            'Checked In' => $activeVisitors,
            'Checked Out' => $completedVisits,
            'Pre-registered' => $pendingVisits
          ];
        ?>
        <ul class="list-group">
          <?php foreach ($statusData as $label => $count): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?= $label ?>
              <span class="badge bg-primary rounded-pill"><?= $count ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    
    <div class="col-md-6">
      <div class="bg-white rounded-3 shadow-sm p-4">
        <h6 class="mb-3" style="font-weight:700;">Top Hosts</h6>
        <?php
          $topHosts = $conn->query("SELECT host_name, COUNT(*) AS cnt FROM visitors GROUP BY host_name ORDER BY cnt DESC LIMIT 5");
        ?>
        <ul class="list-group">
          <?php while ($host = $topHosts->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?= htmlspecialchars($host['host_name']) ?>
              <span class="badge bg-info rounded-pill"><?= $host['cnt'] ?></span>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>