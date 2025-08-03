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

          <!-- Main Navigation -->
          <div class="mb-4">
            <h6 class="text-uppercase mb-2">Main</h6>
            <nav class="nav flex-column">
                <a class="nav-link active" href="#"><ion-icon name="home-outline"></ion-icon>Dashboard</a>
                <a class="nav-link" href="cases.php"><ion-icon name="newspaper-outline"></ion-icon>Casses</a>
                <a class="nav-link" href="documents.php"><ion-icon name="document-text-outline"></ion-icon>Documents </a>
                <a class="nav-link" href="reports.php"><ion-icon name="bar-chart-outline"></ion-icon>Reports</a>
                <a class="nav-link" href="notifications.php"><ion-icon name="notifications-outline"></ion-icon>Notifications</a>
                <hr>
                <a href="account.php"><i class="bi bi-person"></i> Account</a>
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
    <div class="col main-content" style="background:#f8f9fb;">
      <div class="container-fluid py-4">
        <h2 class="dashboard-title mb-1">Dashboard</h2>
        <p class="mb-4" style="color:#6c757d;">Welcome to your legal administration dashboard.</p>
        <div class="row mb-4 g-3">
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm p-3 text-center">
              <div class="mb-2" style="font-size:2rem;color:#6532c9;"><i class="bi bi-file-earmark-text"></i></div>
              <div style="font-size:1.3rem;font-weight:600;">Total Documents</div>
              <div style="font-size:2rem;font-weight:700;">1,284</div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm p-3 text-center">
              <div class="mb-2" style="font-size:2rem;color:#6532c9;"><i class="bi bi-briefcase"></i></div>
              <div style="font-size:1.3rem;font-weight:600;">Active Cases</div>
              <div style="font-size:2rem;font-weight:700;">42</div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm p-3 text-center">
              <div class="mb-2" style="font-size:2rem;color:#6532c9;"><i class="bi bi-clock-history"></i></div>
              <div style="font-size:1.3rem;font-weight:600;">Pending Approvals</div>
              <div style="font-size:2rem;font-weight:700;">8</div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="card shadow-sm p-3 text-center">
              <div class="mb-2" style="font-size:2rem;color:#6532c9;"><i class="bi bi-journal-text"></i></div>
              <div style="font-size:1.3rem;font-weight:600;">Legal Requests</div>
              <div style="font-size:2rem;font-weight:700;">16</div>
            </div>
          </div>
        </div>

        <div class="row g-4">
          <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="fw-bold mb-0">Recent Documents</h5>
                  <a href="#" class="text-decoration-none">View all</a>
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
                      <tr>
                        <td><a href="#">Contract_Johnson_2023.pdf</a></td>
                        <td>Contract</td>
                        <td>2023-11-15</td>
                        <td><span class="text-success"><i class="bi bi-check-circle"></i> Approved</span></td>
                      </tr>
                      <tr>
                        <td><a href="#">NDA_TechCorp_2023.docx</a></td>
                        <td>NDA</td>
                        <td>2023-11-14</td>
                        <td><span class="text-warning"><i class="bi bi-clock"></i> Pending</span></td>
                      </tr>
                      <tr>
                        <td><a href="#">LegalBrief_Smith_Case.pdf</a></td>
                        <td>Brief</td>
                        <td>2023-11-13</td>
                        <td><span class="text-danger"><i class="bi bi-x-circle"></i> Rejected</span></td>
                      </tr>
                      <tr>
                        <td><a href="#">Property_Agreement_2023.pdf</a></td>
                        <td>Agreement</td>
                        <td>2023-11-12</td>
                        <td><span class="text-success"><i class="bi bi-check-circle"></i> Approved</span></td>
                      </tr>
                      <tr>
                        <td><a href="#">Compliance_Report_Q3.xlsx</a></td>
                        <td>Report</td>
                        <td>2023-11-10</td>
                        <td><span class="text-warning"><i class="bi bi-clock"></i> Pending</span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card shadow-sm">
              <div class="card-body">
                <h6 class="fw-bold mb-3">Document Activity</h6>
                <div class="text-muted" style="height:120px;display:flex;align-items:center;justify-content:center;">
                  Document activity chart will appear here
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="fw-bold mb-0">Recent Cases</h5>
                  <a href="#" class="text-decoration-none">View all</a>
                </div>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <a href="#" class="fw-bold">Smith vs. Johnson Corp</a>
                      <div class="small text-muted">Assignee: Jane Doe</div>
                    </div>
                    <span class="badge bg-danger">High</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <a href="#" class="fw-bold">Intellectual Property Claim #45</a>
                      <div class="small text-muted">Assignee: John Smith</div>
                    </div>
                    <span class="badge bg-warning text-dark">Medium</span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <a href="#" class="fw-bold">Contract Dispute - TechServe</a>
                      <div class="small text-muted">Assignee: Emily Chen</div>
                    </div>
                    <span class="badge bg-success">Low</span>
                  </li>
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
</body>
</html>
