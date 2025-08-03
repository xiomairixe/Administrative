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
    <a href="index.php" class="active"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="case.php"><i class="bi bi-building"></i> Assigned Cases</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="notifications.php"><i class="bi bi-bell"></i> Notifications</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

  <div class="content">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Legal Document Management</h2>
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
          <div class="display-6 fw-bold text-primary">24</div>
          <div class="small text-muted">5 need action</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <div class="fs-4 fw-bold text-primary">Active Cases</div>
          <div class="display-6 fw-bold text-primary">8</div>
          <div class="small text-muted">2 high priority</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3 text-center">
          <div class="fs-4 fw-bold text-primary">Pending Requests</div>
          <div class="display-6 fw-bold text-primary">3</div>
          <div class="small text-muted">1 overdue</div>
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
                <th>Priority</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="fw-semibold">Employment Contract - ABC Corp</td>
                <td><span class="text-primary"><i class="bi bi-arrow-repeat"></i> In Review</span></td>
                <td>2023-05-15</td>
                <td><span class="badge bg-danger bg-opacity-10 text-danger">High</span></td>
                <td><a href="#" class="text-decoration-none text-primary">View</a></td>
              </tr>
              <tr>
                <td class="fw-semibold">NDA - XYZ Technologies</td>
                <td><span class="text-warning"><i class="bi bi-exclamation-circle"></i> Pending Clarification</span></td>
                <td>2023-05-14</td>
                <td><span class="badge bg-warning bg-opacity-10 text-warning">Medium</span></td>
                <td><a href="#" class="text-decoration-none text-primary">View</a></td>
              </tr>
              <tr>
                <td class="fw-semibold">Lease Agreement - Office Space</td>
                <td><span class="text-success"><i class="bi bi-check-circle"></i> Completed</span></td>
                <td>2023-05-10</td>
                <td><span class="badge bg-success bg-opacity-10 text-success">Low</span></td>
                <td><a href="#" class="text-decoration-none text-primary">View</a></td>
              </tr>
              <tr>
                <td class="fw-semibold">Patent Application - New Product</td>
                <td><span class="text-secondary"><i class="bi bi-clock"></i> Draft</span></td>
                <td>2023-05-08</td>
                <td><span class="badge bg-danger bg-opacity-10 text-danger">High</span></td>
                <td><a href="#" class="text-decoration-none text-primary">View</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Pending Requests -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Pending Requests</h5>
        <div class="mb-3 p-3 border rounded bg-light">
          <div class="fw-semibold mb-1">Clarification on Section 3.2</div>
          <div class="mb-1 text-muted small">
            Document: NDA - XYZ Technologies<br>
            From: Sarah Johnson
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button class="btn btn-primary btn-sm me-2">Respond</button>
              <button class="btn btn-outline-secondary btn-sm">View Document</button>
            </div>
            <div class="text-muted small">2023-05-14</div>
          </div>
        </div>
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
