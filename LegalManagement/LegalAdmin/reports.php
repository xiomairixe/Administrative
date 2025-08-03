
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Legal Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { font-family: 'QuickSand', 'Poppins', Arial, sans-serif; background: #fafbfc; color: #22223b; font-size: 16px; }

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
    .stats-card .trend {
      font-size: 0.93rem;
      color: #22c55e;
    }
    .stats-card .trend.negative {
      color: #ef4444;
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

    /* Report Page Specific Styles */
    .report-actions a { color: #4311a5; text-decoration: none; font-weight: 500; }
    .report-actions a:hover { text-decoration: underline; }
    .available-report-card { background: #fff; border: 1px solid #f0f0f0; border-radius: 12px; padding: 1.2rem 1rem; margin-bottom: 1rem; }
    .available-report-card .btn { float: right; }

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

          <!-- Main Navigation -->
          <div class="mb-4">
            <h6 class="text-uppercase mb-2">Main</h6>
            <nav class="nav flex-column">
                <a class="nav-link" href="index.php"><ion-icon name="home-outline"></ion-icon>Dashboard</a>
                <a class="nav-link active" href="#"><ion-icon name="newspaper-outline"></ion-icon>Casses</a>
                <a class="nav-link" href="documents.php"><ion-icon name="document-text-outline"></ion-icon>Documents </a>
                <a class="nav-link" href="reports.php"><ion-icon name="bar-chart-outline"></ion-icon>Reports</a>
                <a class="nav-link" href="notifications.php"><ion-icon name="notifications-outline"></ion-icon>Notifications</a>
                  <hr>
                <a href="account.php"><i class="bi bi-person"></i> Account</a>
                <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
                <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
                <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
                <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
            </nav>
          </div>

        <!-- Logout -->
        <div class="p-3 border-top mb-2">
          <a class="nav-link text-danger" href="/Administrative/login.php">
            <ion-icon name="log-out-outline"></ion-icon>Logout
          </a>
        </div>
      </div>
    </div>

    <!-- Main Content Column -->

<div class="container-fluid py-4">
  <h2 class="dashboard-title mb-1">Reports</h2>
  <p class="mb-4" style="color:#6c757d;">Generate and view legal reports and analytics.</p>
  <div class="d-flex flex-wrap gap-2 mb-3">
    <button class="btn btn-primary"><i class="bi bi-bar-chart"></i> Generate Report</button>
    <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filter</button>
    <div class="ms-auto">
      <select class="form-select" style="min-width:160px;">
        <option>Last 30 days</option>
        <option>Last 90 days</option>
        <option>Last 12 months</option>
      </select>
    </div>
  </div>
  <ul class="nav nav-tabs mb-4" id="reportTabs">
    <li class="nav-item"><a class="nav-link active" href="#">Overview</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Documents</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Cases</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Performance</a></li>
    <li class="nav-item"><a class="nav-link" href="#">Custom Reports</a></li>
  </ul>
  <div class="row g-3 mb-4">
    <div class="col-lg-2 col-md-4 col-6">
      <div class="stats-card">
        <div class="icon"><i class="bi bi-file-earmark-text"></i></div>
        <div class="label">Total Documents</div>
        <div class="value">1,284</div>
        <div class="trend">+25% from last month</div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
      <div class="stats-card">
        <div class="icon"><i class="bi bi-briefcase"></i></div>
        <div class="label">Active Cases</div>
        <div class="value">42</div>
        <div class="trend">+13% from last month</div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
      <div class="stats-card">
        <div class="icon"><i class="bi bi-people"></i></div>
        <div class="label">Legal Staff</div>
        <div class="value">18</div>
        <div class="trend text-muted">No change from last month</div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
      <div class="stats-card">
        <div class="icon"><i class="bi bi-folder-check"></i></div>
        <div class="label">Closed Cases</div>
        <div class="value">64</div>
        <div class="trend negative">-24% from last month</div>
      </div>
    </div>
  </div>
  <div class="row g-4 mb-4">
    <div class="col-lg-6">
      <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Document Activity</span>
          <span class="small text-muted"><i class="bi bi-calendar"></i> Last 30 days</span>
        </div>
        <div class="card-body">
          <span>Document activity chart will appear here</span>
        </div>
        <div class="card-footer text-end">
          <a href="#" class="small text-decoration-none"><i class="bi bi-download"></i> Export Data</a>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Case Resolution Time</span>
          <span class="small text-muted"><i class="bi bi-calendar"></i> Last 12 months</span>
        </div>
        <div class="card-body">
          <span>Case resolution chart will appear here</span>
        </div>
        <div class="card-footer text-end">
          <a href="#" class="small text-decoration-none"><i class="bi bi-download"></i> Export Data</a>
        </div>
      </div>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Recent Reports</span>
      <a href="#" class="small text-decoration-none">View all</a>
    </div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead>
          <tr>
            <th>Report Name</th>
            <th>Type</th>
            <th>Generated Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><a href="#">Monthly Document Activity</a></td>
            <td>Document Report</td>
            <td>2023-11-01</td>
            <td><span class="text-success"><i class="bi bi-check-circle"></i> Generated</span></td>
            <td class="report-actions"><a href="#">View</a></td>
          </tr>
          <tr>
            <td><a href="#">Case Resolution Time Q3</a></td>
            <td>Case Report</td>
            <td>2023-10-15</td>
            <td><span class="text-success"><i class="bi bi-check-circle"></i> Generated</span></td>
            <td class="report-actions"><a href="#">View</a></td>
          </tr>
          <tr>
            <td><a href="#">Legal Team Performance</a></td>
            <td>Performance Report</td>
            <td>2023-11-10</td>
            <td><span class="text-warning"><i class="bi bi-clock"></i> Pending</span></td>
            <td class="report-actions"><a href="#">View</a></td>
          </tr>
          <tr>
            <td><a href="#">Document Type Analysis</a></td>
            <td>Document Report</td>
            <td>2023-10-28</td>
            <td><span class="text-success"><i class="bi bi-check-circle"></i> Generated</span></td>
            <td class="report-actions"><a href="#">View</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="mb-4">
    <h5 class="mb-3">Available Reports</h5>
    <div class="row g-3">
      <div class="col-lg-3 col-md-6">
        <div class="available-report-card">
          <div><i class="bi bi-bar-chart"></i> Document Activity</div>
          <div class="small text-muted mb-2">Track document upload, approval, and rejections</div>
          <button class="btn btn-outline-primary btn-sm">Generate</button>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="available-report-card">
          <div><i class="bi bi-pie-chart"></i> Document Type Analysis</div>
          <div class="small text-muted mb-2">Breakdown of document types and volumes</div>
          <button class="btn btn-outline-primary btn-sm">Generate</button>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="available-report-card">
          <div><i class="bi bi-clock-history"></i> Case Resolution Times</div>
          <div class="small text-muted mb-2">Analyze how quickly cases are being resolved</div>
          <button class="btn btn-outline-primary btn-sm">Generate</button>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="available-report-card">
          <div><i class="bi bi-diagram-3"></i> Case Type Analysis</div>
          <div class="small text-muted mb-2">Breakdown of case types and volumes</div>
          <button class="btn btn-outline-primary btn-sm">Generate</button>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="available-report-card">
          <div><i class="bi bi-people"></i> Team Performance</div>
          <div class="small text-muted mb-2">Evaluate legal team workload and efficiency</div>
          <button class="btn btn-outline-primary btn-sm">Generate</button>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="available-report-card">
          <div><i class="bi bi-clock"></i> Document Approval Times</div>
          <div class="small text-muted mb-2">Average time for document approvals</div>
          <button class="btn btn-outline-primary btn-sm">Generate</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
