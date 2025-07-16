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
              <a class="nav-link" href="/Administrative/Admin/facilitiesReservation/index.php"><ion-icon name="business-outline"></ion-icon>Overview</a>
              <a class="nav-link" href="/Administrative/Admin/facilitiesReservation/facilities.php"><ion-icon name="build-outline"></ion-icon>Facilities</a>
              <a class="nav-link" href="/Administrative/Admin/facilitiesReservation/request.php"><ion-icon name="clipboard-outline"></ion-icon>Requests</a>
              <a class="nav-link" href="/Administrative/Admin/facilitiesReservation/history.php"><ion-icon name="time-outline"></ion-icon>History</a>
            </nav>
          </div>

          <!-- Document Management -->
          <div class="mb-4">
            <h6 class="text-uppercase px-2 mb-2">Document Management</h6>
            <nav class="nav flex-column">
              <a class="nav-link active" href="#"><ion-icon name="folder-outline"></ion-icon>Documents</a>
              <a class="nav-link" href="review&approve.php"><ion-icon name="checkmark-done-outline"></ion-icon>Review & Approve</a>
              <a class="nav-link" href="countersign.php"><ion-icon name="pencil-outline"></ion-icon>Countersign</a>
              <a class="nav-link" href="release.php"><ion-icon name="cloud-upload-outline"></ion-icon>Release</a>
              <a class="nav-link" href="archive.php"><ion-icon name="archive-outline"></ion-icon>Archive</a>
              <a class="nav-link" href="trash.php"><ion-icon name="trash-outline"></ion-icon>Trash</a>
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

    <!-- Main Content Column -->
    <main class="col-md-10 main-content">
      <div class="container py-4 px-0">
        <div class="mb-4">
          <input type="text" class="form-control form-control-lg" placeholder="Search..." style="max-width:500px;">
        </div>
        <div class="mb-4">
          <div style="font-family:'Montserrat',sans-serif;font-size:2rem;font-weight:700;color:#22223b;">Document Management</div>
          <div style="color:#6c757d;font-size:1.08rem;">Organize and manage your documents</div>
        </div>
        <div class="row g-4">
          <!-- Folders Sidebar -->
          <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-3 mb-3">
              <div style="font-weight:600;font-size:1.15rem;margin-bottom:1rem;">Folders</div>
              <ul class="list-group" id="folderList">
                <li class="list-group-item folder-item active" data-folder="all">
                  <i class="bi bi-folder2-open me-2"></i> All Folders
                  <span class="badge bg-light text-dark ms-auto">24</span>
                </li>
                <li class="list-group-item folder-item" data-folder="contracts">
                  <i class="bi bi-folder me-2"></i> Contracts
                  <span class="badge bg-light text-dark ms-auto">24</span>
                </li>
                <li class="list-group-item folder-item" data-folder="hr">
                  <i class="bi bi-folder me-2"></i> HR Documents
                  <span class="badge bg-light text-dark ms-auto">18</span>
                </li>
                <li class="list-group-item folder-item" data-folder="legal">
                  <i class="bi bi-folder me-2"></i> Legal
                  <span class="badge bg-light text-dark ms-auto">12</span>
                </li>
                <li class="list-group-item folder-item" data-folder="finance">
                  <i class="bi bi-folder me-2"></i> Finance
                  <span class="badge bg-light text-dark ms-auto">31</span>
                </li>
                <li class="list-group-item folder-item" data-folder="projects">
                  <i class="bi bi-folder me-2"></i> Projects
                  <span class="badge bg-light text-dark ms-auto">15</span>
                </li>
              </ul>
            </div>
          </div>
          <!-- Documents Content -->
          <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <button class="btn btn-light me-2 active" id="tabAll">All Documents</button>
                <button class="btn btn-light me-2" id="tabActive">Active</button>
                <button class="btn btn-light" id="tabArchived">Archived</button>
              </div>
              <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control" placeholder="Search documents" style="max-width:220px;">
                <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i></button>
                <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Upload Document</button>
              </div>
            </div>
            <div class="bg-white rounded-3 shadow-sm p-3">
              <div class="table-responsive">
                <table class="table align-middle mb-0">
                  <thead>
                    <tr>
                      <th>Document</th>
                      <th>Size</th>
                      <th>Modified</th>
                      <th>By</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="documentTable">
                    <tr data-folder="hr">
                      <td>
                        <span class="d-inline-flex align-items-center">
                          <span style="background:#fee2e2;border-radius:8px;padding:6px 10px;margin-right:8px;">
                            <i class="bi bi-file-earmark-pdf" style="color:#ef4444;font-size:1.2rem;"></i>
                          </span>
                          <div>
                            <strong class="text-dark">Employer Handbook 2023.pdf</strong>
                            <div style="font-size:0.95rem;color:#6c757d;">HR Documents</div>
                          </div>
                        </span>
                      </td>
                      <td>3.2 MB</td>
                      <td>Jun 2, 2023</td>
                      <td>Sarah Johnson</td>
                      <td>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-download"></i></a>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-upload"></i></a>
                        <a href="#" class="text-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <tr data-folder="contracts">
                      <td>
                        <span class="d-inline-flex align-items-center">
                          <span style="background:#e0e7ff;border-radius:8px;padding:6px 10px;margin-right:8px;">
                            <i class="bi bi-file-earmark-word" style="color:#6366f1;font-size:1.2rem;"></i>
                          </span>
                          <div>
                            <strong class="text-dark">Vendor Agreement - XYZ Corp.docx</strong>
                            <div style="font-size:0.95rem;color:#6c757d;">Contracts</div>
                          </div>
                        </span>
                      </td>
                      <td>1.8 MB</td>
                      <td>Jun 5, 2023</td>
                      <td>Michael Brown</td>
                      <td>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-download"></i></a>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-upload"></i></a>
                        <a href="#" class="text-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <tr data-folder="finance">
                      <td>
                        <span class="d-inline-flex align-items-center">
                          <span style="background:#d1fae5;border-radius:8px;padding:6px 10px;margin-right:8px;">
                            <i class="bi bi-file-earmark-excel" style="color:#22c55e;font-size:1.2rem;"></i>
                          </span>
                          <div>
                            <strong class="text-dark">Q2 Financial Report.xlsx</strong>
                            <div style="font-size:0.95rem;color:#6c757d;">Finance</div>
                          </div>
                        </span>
                      </td>
                      <td>4.5 MB</td>
                      <td>May 28, 2023</td>
                      <td>David Wang</td>
                      <td>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-download"></i></a>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-upload"></i></a>
                        <a href="#" class="text-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <tr data-folder="legal">
                      <td>
                        <span class="d-inline-flex align-items-center">
                          <span style="background:#e0e7ff;border-radius:8px;padding:6px 10px;margin-right:8px;">
                            <i class="bi bi-file-earmark-text" style="color:#6366f1;font-size:1.2rem;"></i>
                          </span>
                          <div>
                            <strong class="text-dark">Privacy Policy Update.docx</strong>
                            <div style="font-size:0.95rem;color:#6c757d;">Legal</div>
                          </div>
                        </span>
                      </td>
                      <td>0.9 MB</td>
                      <td>Jun 1, 2023</td>
                      <td>Jennifer Lee</td>
                      <td>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-download"></i></a>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-upload"></i></a>
                        <a href="#" class="text-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <tr data-folder="projects">
                      <td>
                        <span class="d-inline-flex align-items-center">
                          <span style="background:#fef9c3;border-radius:8px;padding:6px 10px;margin-right:8px;">
                            <i class="bi bi-file-earmark-ppt" style="color:#eab308;font-size:1.2rem;"></i>
                          </span>
                          <div>
                            <strong class="text-dark">Project Alpha Proposal.pptx</strong>
                            <div style="font-size:0.95rem;color:#6c757d;">Projects</div>
                          </div>
                        </span>
                      </td>
                      <td>6.2 MB</td>
                      <td>May 20, 2023</td>
                      <td>Robert Wilson</td>
                      <td>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-download"></i></a>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-upload"></i></a>
                        <a href="#" class="text-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                    <tr data-folder="legal">
                      <td>
                        <span class="d-inline-flex align-items-center">
                          <span style="background:#e0e7ff;border-radius:8px;padding:6px 10px;margin-right:8px;">
                            <i class="bi bi-file-earmark-text" style="color:#6366f1;font-size:1.2rem;"></i>
                          </span>
                          <div>
                            <strong class="text-dark">NDA Template.docx</strong>
                            <div style="font-size:0.95rem;color:#6c757d;">Legal</div>
                          </div>
                        </span>
                      </td>
                      <td>0.5 MB</td>
                      <td>Apr 15, 2023</td>
                      <td>Sarah Johnson</td>
                      <td>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-download"></i></a>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-upload"></i></a>
                        <a href="#" class="text-danger"><i class="bi bi-trash"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
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

   // Folder filter functionality
        document.querySelectorAll('.folder-item').forEach(function(item) {
          item.addEventListener('click', function() {
            document.querySelectorAll('.folder-item').forEach(function(i) {
              i.classList.remove('active');
            });
            item.classList.add('active');
            const folder = item.getAttribute('data-folder');
            document.querySelectorAll('#documentTable tr').forEach(function(row) {
              if (folder === 'all' || row.getAttribute('data-folder') === folder) {
                row.style.display = '';
              } else {
                row.style.display = 'none';
              }
            });
          });
        });
        // Tabs functionality (demo only, does not filter actual data)
        document.getElementById('tabAll').addEventListener('click', function() {
          this.classList.add('active');
          document.getElementById('tabActive').classList.remove('active');
          document.getElementById('tabArchived').classList.remove('active');
        });
        document.getElementById('tabActive').addEventListener('click', function() {
          this.classList.add('active');
          document.getElementById('tabAll').classList.remove('active');
          document.getElementById('tabArchived').classList.remove('active');
        });
        document.getElementById('tabArchived').addEventListener('click', function() {
          this.classList.add('active');
          document.getElementById('tabAll').classList.remove('active');
          document.getElementById('tabActive').classList.remove('active');
        });
</script>
</body>
</html>
