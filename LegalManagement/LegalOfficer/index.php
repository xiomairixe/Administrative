<?php
// filepath: c:\xampp\htdocs\Administrative\LegalManagement\LegalOfficer\index.php
include_once("../../connection.php");

// --- Dashboard Stats ---
$total_documents = $conn->query("SELECT COUNT(*) FROM document WHERE status != 'trash'")->fetch_row()[0];
$pending_documents = $conn->query("SELECT COUNT(*) FROM document WHERE status = 'pending'")->fetch_row()[0];
$active_cases = $conn->query("SELECT COUNT(*) FROM cases WHERE status = 'Active'")->fetch_row()[0];
$pending_requests = $conn->query("SELECT COUNT(*) FROM legal_requests WHERE status IN ('Pending','In Review','Submitted','Draft')")->fetch_row()[0];

// --- Recent Documents ---
$recent_documents = $conn->query("SELECT file_name, status, uploaded_at, docu_type, description FROM document WHERE status != 'trash' ORDER BY uploaded_at DESC LIMIT 4");

// --- Recent Cases ---
$recent_cases = $conn->query("SELECT name, status, client, start_date FROM cases ORDER BY created_at DESC LIMIT 3");

// --- Pending Requests ---
$pending_legal_requests = $conn->query("SELECT title, status, priority, created_at FROM legal_requests WHERE status IN ('Pending','In Review','Submitted','Draft') ORDER BY created_at DESC LIMIT 3");
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
  <style>
    body {
      background: #fafbfc;
      font-family: 'QuickSand', 'Poppins';
      color: #22223b;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 250px;
      background: #181818ff;
      padding: 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      z-index: 1040;
      transition: left 0.3s ease;
    }

    .sidebar .logo {
      font-family: 'QuickSand', 'Poppins';
      font-size: 1.6rem;
      color: #fff;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sidebar a {
      color: #bfc7d1;
      text-decoration: none;
      font-size: 1.08rem;
      padding: 0.7rem 1rem;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 0.9rem;
      transition: 0.2s;
    }

    .sidebar a.active,
    .sidebar a:hover {
      background: linear-gradient(90deg, #9A66ff 0%, #4311a5 100%);
      color: #fff;
    }

    .sidebar hr {
      border-top: 1px solid #2d3250;
      margin: 1.2rem 0;
    }

    .main-content {
      margin-left: 250px;
      padding: 2.5rem;
      min-height: 100vh;
      background: #fafbfc;
      transition: margin 0.3s;
    }

    .content {
      margin-left: 250px;
      padding: 2rem;
    }

    @media (max-width: 900px) {
      .sidebar {
        left: -260px;
      }

      .sidebar.show {
        left: 0;
      }

      .main-content,
      .content {
        margin-left: 0;
        padding: 1rem;
      }

      .sidebar-toggle {
        display: block;
      }
    }
  </style>
</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="/Administrative/asset/image.png" alt="Logo" style="height: 60px;"></div>
    <a href="#" class="active"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="case.php"><i class="bi bi-building"></i> Assigned Cases</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

  <div class="content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Dashboard</h2>
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-primary px-4"><i class="bi bi-upload"></i> Upload</button>
        <span class="position-relative">
          <i class="bi bi-bell fs-4"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
        </span>
        <div class="d-flex align-items-center gap-2">
          <img src="https://ui-avatars.com/api/?name=John+Doe" alt="Profile" class="rounded-circle" width="36" height="36">
          <span class="fw-semibold">John Doe</span>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <div class="fs-4 fw-bold text-primary">Documents</div>
          <div class="display-6 fw-bold text-primary"><?= $total_documents ?></div>
          <div class="small text-muted"><?= $pending_documents ?> need action</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <div class="fs-4 fw-bold text-primary">Active Cases</div>
          <div class="display-6 fw-bold text-primary"><?= $active_cases ?></div>
          <div class="small text-muted">Active</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <div class="fs-4 fw-bold text-primary">Pending Requests</div>
          <div class="display-6 fw-bold text-primary"><?= $pending_requests ?></div>
          <div class="small text-muted">Pending/Review</div>
        </div>
      </div>
    </div>

    <!-- Recent Documents Table -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Recent Documents</h5>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <?php while($doc = $recent_documents->fetch_assoc()): ?>
                <tr>
                  <td class="fw-semibold"><?= htmlspecialchars($doc['file_name']) ?></td>
                  <td>
                    <?php
                      $status = strtolower($doc['status']);
                      if($status == 'active' || $status == 'approved')
                        echo '<span class="text-success"><i class="bi bi-check-circle"></i> Approved</span>';
                      elseif($status == 'pending')
                        echo '<span class="text-warning"><i class="bi bi-clock"></i> Pending</span>';
                      else
                        echo '<span class="text-danger"><i class="bi bi-x-circle"></i> Rejected</span>';
                    ?>
                  </td>
                  <td><?= htmlspecialchars(date('Y-m-d', strtotime($doc['uploaded_at']))) ?></td>
                  <td><?= htmlspecialchars($doc['docu_type']) ?></td>
                  <td><?= htmlspecialchars($doc['description']) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Recent Cases Table -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Recent Cases</h5>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Case Name</th>
                <th>Status</th>
                <th>Client</th>
                <th>Start Date</th>
              </tr>
            </thead>
            <tbody>
              <?php while($case = $recent_cases->fetch_assoc()): ?>
                <tr>
                  <td class="fw-semibold"><?= htmlspecialchars($case['name']) ?></td>
                  <td>
                    <?php
                      $status = strtolower($case['status']);
                      if($status == 'active')
                        echo '<span class="badge bg-success">Active</span>';
                      elseif($status == 'pending')
                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                      else
                        echo '<span class="badge bg-secondary">Closed</span>';
                    ?>
                  </td>
                  <td><?= htmlspecialchars($case['client']) ?></td>
                  <td><?= htmlspecialchars($case['start_date']) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Pending Requests -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Pending Requests</h5>
        <?php while($req = $pending_legal_requests->fetch_assoc()): ?>
        <div class="mb-3 p-3 border rounded bg-light">
          <div class="fw-semibold mb-1"><?= htmlspecialchars($req['title']) ?></div>
          <div class="mb-1 text-muted small">
            Status: <?= htmlspecialchars($req['status']) ?><br>
            Priority: <span class="badge 
              <?= $req['priority'] == 'High' ? 'bg-danger' : ($req['priority'] == 'Medium' ? 'bg-warning text-dark' : 'bg-success') ?>">
              <?= htmlspecialchars($req['priority']) ?>
            </span>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button class="btn btn-primary btn-sm me-2">Respond</button>
              <button class="btn btn-outline-secondary btn-sm">View Request</button>
            </div>
            <div class="text-muted small"><?= htmlspecialchars(date('Y-m-d', strtotime($req['created_at']))) ?></div>
          </div>
        </div>
        <?php endwhile; ?>
        <?php if($pending_legal_requests->num_rows == 0): ?>
          <div class="text-muted text-center">No pending requests.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <!-- Sidebar toggle script and Bootstrap JS -->
  <script>
    document.getElementById("sidebarToggle").addEventListener("click", function () {
      document.getElementById("sidebarNav").classList.toggle("show");
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
