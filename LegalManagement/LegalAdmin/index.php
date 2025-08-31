<?php
// filepath: c:\xampp\htdocs\Administrative\LegalManagement\LegalAdmin\index.php
include_once("../../connection.php");

// --- Dashboard Stats ---
$total_documents = $conn->query("SELECT COUNT(*) FROM document WHERE status != 'trash'")->fetch_row()[0];
$active_cases = $conn->query("SELECT COUNT(*) FROM cases WHERE status = 'Active'")->fetch_row()[0];
$pending_approvals = $conn->query("SELECT COUNT(*) FROM document WHERE status = 'pending'")->fetch_row()[0];
$legal_requests = $conn->query("SELECT COUNT(*) FROM legal_requests")->fetch_row()[0];

// --- Recent Documents ---
$recent_documents = $conn->query("SELECT file_name, docu_type, uploaded_at, status FROM document WHERE status != 'trash' ORDER BY uploaded_at DESC LIMIT 5");

// --- Recent Cases ---
$recent_cases = $conn->query("SELECT name, assigned_to, status FROM cases ORDER BY created_at DESC LIMIT 3");
$users = [];
$user_res = $conn->query("SELECT user_id, fullname FROM users");
while($u = $user_res->fetch_assoc()) $users[$u['user_id']] = $u['fullname'];
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

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 2rem;
      gap: 1rem;
    }

    .topbar .breadcrumbs {
      color: #6c757d;
    }

    .topbar .profile {
      display: flex;
      align-items: center;
      gap: 1.2rem;
    }

    .topbar .profile .bi-envelope {
      font-size: 1.5rem;
      color: #4311a5;
      position: relative;
    }

    .topbar .badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #f43f5e;
      color: #fff;
      font-size: 0.7rem;
      border-radius: 50%;
      padding: 2px 6px;
    }

    .profile-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e0e7ff;
    }

    .dashboard-title {
      font-family: 'QuickSand', 'Poppins';
      font-size: 2rem;
      font-weight: 700;
    }

    .stats-cards {
      display: flex;
      gap: 1.5rem;
      flex-wrap: wrap;
      margin-bottom: 2rem;
    }

    .stats-card {
      flex: 1;
      min-width: 170px;
      padding: 1.5rem;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 8px rgba(140, 140, 200, 0.07);
      text-align: center;
    }

    .stats-card .icon {
      font-size: 2rem;
      background: #ede9fe;
      color: #4311a5;
      border-radius: 50%;
      width: 48px;
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 0.8rem auto;
    }

    .stats-card .label {
      color: #6c757d;
      margin-bottom: 0.2rem;
    }

    .stats-card .value {
      font-size: 1.6rem;
      font-weight: 700;
    }

    .dashboard-row {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .dashboard-col {
      flex: 1;
      min-width: 260px;
      background: #fff;
      border-radius: 18px;
      padding: 1.5rem;
      box-shadow: 0 2px 8px rgba(140, 140, 200, 0.07);
    }

    .dashboard-col h5 {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .table th {
      color: #6c757d;
    }

    .status-badge {
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .status-badge.confirmed {
      background: #d1fae5;
      color: #22c55e;
    }

    .status-badge.pending {
      background: #fef9c3;
      color: #eab308;
    }

    .sidebar-toggle {
      display: none;
      background: none;
      border: none;
      color: #fff;
      font-size: 2rem;
      position: absolute;
      top: 1rem;
      left: 1rem;
      z-index: 1050;
    }

    @media (max-width: 900px) {
      .sidebar {
        left: -260px;
      }

      .sidebar.show {
        left: 0;
      }

      .main-content {
        margin-left: 0;
        padding: 1rem;
      }

      .sidebar-toggle {
        display: block;
      }
    }

    @media (max-width: 700px) {
      .main-content {
        padding: 0.7rem 0.2rem 0.7rem 0.2rem;
      }

      .dashboard-title {
        font-size: 1.3rem;
      }

      .stats-card {
        min-width: 120px;
        padding: 1rem 0.7rem;
        font-size: 0.95rem;
      }

      .dashboard-col {
        padding: 1rem 0.7rem;
        min-width: 0;
      }

      .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
      }

      .topbar .profile {
        margin-top: 0.5rem;
      }

      .topbar .dashboard-title {
        font-size: 1.3rem;
      }

      .table-responsive {
        overflow-x: auto;
      }
    }

    @media (max-width: 500px) {
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

      .stats-cards,
      .dashboard-row {
        flex-direction: column;
        gap: 0.5rem;
      }

      .dashboard-col {
        min-width: 0;
        padding: 0.7rem 0.3rem;
      }

      .table-responsive {
        overflow-x: auto;
      }

      .btn,
      .form-control,
      .form-select {
        width: 100% !important;
        margin-bottom: 0.5rem;
      }
    }

    .facility-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .facility-image {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
</style>

<body>
  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="bi bi-list"></i>
  </button>

  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
      <a class="nav-link active" href="#"><ion-icon name="home-outline"></ion-icon>Dashboard</a>
      <a class="nav-link" href="cases.php"><ion-icon name="newspaper-outline"></ion-icon>Casses</a>
      <a class="nav-link" href="documents.php"><ion-icon name="document-text-outline"></ion-icon>Documents </a>
      <a class="nav-link" href="reports.php"><ion-icon name="bar-chart-outline"></ion-icon>Reports</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

<!-- Main Content Column -->
      <div class="col main-content" style="background:#f8f9fb;">
        <div class="container-fluid py-4">
          <h2 class="dashboard-title mb-1">Dashboard</h2>
          <p class="mb-4" style="color:#6c757d;">Welcome to your legal administration dashboard.</p>
          <div class="row mb-4 g-3">
            <div class="col-12 col-sm-6 col-lg-3">
              <div class="card shadow-sm p-3 text-center">
                <div class="mb-2" style="font-size:2rem;color:#6532c9;"><i class="bi bi-file-earmark-text"></i></div>
                <div style="font-size:1.3rem;font-weight:600;">Total Documents</div>
                <div style="font-size:2rem;font-weight:700;"><?= $total_documents ?></div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
              <div class="card shadow-sm p-3 text-center">
                <div class="mb-2" style="font-size:2rem;color:#6532c9;"><i class="bi bi-briefcase"></i></div>
                <div style="font-size:1.3rem;font-weight:600;">Active Cases</div>
                <div style="font-size:2rem;font-weight:700;"><?= $active_cases ?></div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
              <div class="card shadow-sm p-3 text-center">
                <div class="mb-2" style="font-size:2rem;color:#6532c9;"><i class="bi bi-clock-history"></i></div>
                <div style="font-size:1.3rem;font-weight:600;">Pending Approvals</div>
                <div style="font-size:2rem;font-weight:700;"><?= $pending_approvals ?></div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
              <div class="card shadow-sm p-3 text-center">
                <div class="mb-2" style="font-size:2rem;color:#6532c9;"><i class="bi bi-journal-text"></i></div>
                <div style="font-size:1.3rem;font-weight:600;">Legal Requests</div>
                <div style="font-size:2rem;font-weight:700;"><?= $legal_requests ?></div>
              </div>
            </div>
          </div>

          <div class="row g-4">
            <div class="col-lg-8">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Recent Documents</h5>
                    <a href="documents.php" class="text-decoration-none">View all</a>
                  </div>
                  <div class="table-responsive">
                    <table class="table align-middle mb-0">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Type</th>
                          <th>Date</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while($doc = $recent_documents->fetch_assoc()): ?>
                          <tr>
                            <td><a href="#"><?= htmlspecialchars($doc['file_name']) ?></a></td>
                            <td><?= htmlspecialchars($doc['docu_type']) ?></td>
                            <td><?= htmlspecialchars(date('Y-m-d', strtotime($doc['uploaded_at']))) ?></td>
                            <td>
                              <?php if(strtolower($doc['status']) == 'active' || strtolower($doc['status']) == 'approved'): ?>
                                <span class="text-success"><i class="bi bi-check-circle"></i> Approved</span>
                              <?php elseif(strtolower($doc['status']) == 'pending'): ?>
                                <span class="text-warning"><i class="bi bi-clock"></i> Pending</span>
                              <?php else: ?>
                                <span class="text-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card shadow-sm">
                <div class="card-body">
                  <h6 class="fw-bold mb-3">Document Activity</h6>
                  <canvas id="docChart" height="120"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card shadow-sm mb-4">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Recent Cases</h5>
                    <a href="cases.php" class="text-decoration-none">View all</a>
                  </div>
                  <ul class="list-group list-group-flush">
                    <?php while($case = $recent_cases->fetch_assoc()): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                        <a href="#" class="fw-bold"><?= htmlspecialchars($case['name']) ?></a>
                        <div class="small text-muted">Assignee: <?= htmlspecialchars($users[$case['assigned_to']] ?? 'N/A') ?></div>
                      </div>
                      <?php
                        $badge = 'bg-success';
                        if($case['status'] == 'Active') $badge = 'bg-danger';
                        elseif($case['status'] == 'Pending') $badge = 'bg-warning text-dark';
                      ?>
                      <span class="badge <?= $badge ?>"><?= htmlspecialchars($case['status']) ?></span>
                    </li>
                    <?php endwhile; ?>
                  </ul>
                </div>
              </div>
              <div class="card shadow-sm">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Upcoming Tasks</h6>
                    <a href="#" class="text-decoration-none">View all</a>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <span class="me-2"><i class="bi bi-clock-history text-warning"></i></span>
                      <span class="fw-bold">Review Contract Draft</span>
                      <div class="small text-muted">Due tomorrow</div>
                    </li>
                    <li class="list-group-item">
                      <span class="me-2"><i class="bi bi-briefcase text-primary"></i></span>
                      <span class="fw-bold">Approve NDA Documents</span>
                      <div class="small text-muted">Due in 3 days</div>
                    </li>
                    <li class="list-group-item">
                      <span class="me-2"><i class="bi bi-journal-text text-purple"></i></span>
                      <span class="fw-bold">Case Strategy Meeting</span>
                      <div class="small text-muted">Nov 20, 2023 at 10:00 AM</div>
                    </li>
                  </ul>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
  // Example: Document Activity Chart (dummy data)
  var ctx = document.getElementById('docChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
      datasets: [{
        label: 'Documents Uploaded',
        data: [2, 4, 3, 5, 2, 6, 4],
        borderColor: '#6532c9',
        backgroundColor: 'rgba(101,50,201,0.08)',
        fill: true,
        tension: 0.4,
        pointRadius: 4,
        pointBackgroundColor: '#6532c9'
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false } },
        y: { beginAtZero: true, grid: { color: '#f3f3f3' } }
      }
    }
  });
});
</script>
</body>
</html>
