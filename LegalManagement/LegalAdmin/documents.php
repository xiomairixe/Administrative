<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Legal Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
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
                <a class="nav-link" href="index.php"><ion-icon name="home-outline"></ion-icon>Dashboard</a>
                <a class="nav-link" href="cases.php"><ion-icon name="newspaper-outline"></ion-icon>Casses</a>
                <a class="nav-link active" href="#"><ion-icon name="document-text-outline"></ion-icon>Documents </a>
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
    <div class="col main-content" style="background:#f8f9fb;">
      <div class="container-fluid py-4">
        <h2 class="dashboard-title mb-1">Documents</h2>
        <p class="mb-4" style="color:#6c757d;">Manage, review, and approve legal documents.</p>
        <div class="d-flex flex-wrap gap-2 mb-3">
          <button class="btn btn-primary"><i class="bi bi-plus"></i> Upload Document</button>
          <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filter</button>
          <div class="ms-auto" style="min-width:280px;">
            <input type="text" class="form-control" id="searchInput" placeholder="Search documents..." oninput="filterDocuments()">
          </div>
        </div>
        <ul class="nav nav-tabs mb-3" id="docTabs">
          <li class="nav-item"><a class="nav-link active" href="#" onclick="showDocTab('all')">All Documents</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showDocTab('pending')">Pending Approval</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showDocTab('approved')">Approved</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showDocTab('rejected')">Rejected</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showDocTab('archived')">Archived</a></li>
        </ul>
        <div class="table-responsive">
          <table class="table align-middle mb-0" id="documentsTable">
            <thead>
              <tr>
                <th>Document</th>
                <th>Type</th>
                <th>Status</th>
                <th>Date</th>
                <th>Owner</th>
                <th>Version</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="documentsTbody">
              <!-- Group: Smith vs. Johnson Corp -->
              <tr class="table-group">
                <td colspan="7" class="fw-bold bg-light">Smith vs. Johnson Corporation</td>
              </tr>
              <tr data-status="approved" data-group="Smith vs. Johnson Corporation">
                <td><i class="bi bi-file-earmark"></i> <a href="#">Employment Contract - John Smith.pdf</a></td>
                <td>Contract</td>
                <td><span class="text-success"><i class="bi bi-check-circle"></i> Approved</span></td>
                <td>2023-11-15</td>
                <td>Jane Doe</td>
                <td>2.0</td>
                <td>
                  <button class="btn btn-link p-0 me-2" title="View" onclick="viewDocument('Employment Contract - John Smith.pdf')"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Edit" onclick="requestRevision('Employment Contract - John Smith.pdf')"><i class="bi bi-pencil-square"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Download" onclick="downloadDocument('Employment Contract - John Smith.pdf')"><i class="bi bi-download"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Manage Access" onclick="manageAccess('Employment Contract - John Smith.pdf')"><i class="bi bi-share"></i></button>
                  <button class="btn btn-link p-0" title="More" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                </td>
              </tr>
              <tr data-status="rejected" data-group="Smith vs. Johnson Corporation">
                <td><i class="bi bi-file-earmark"></i> <a href="#">Legal Brief - Smith vs. Johnson.pdf</a></td>
                <td>Brief</td>
                <td><span class="text-danger"><i class="bi bi-x-circle"></i> Rejected</span></td>
                <td>2023-11-13</td>
                <td>Emily Chen</td>
                <td>3.1</td>
                <td>
                  <button class="btn btn-link p-0 me-2" title="View" onclick="viewDocument('Legal Brief - Smith vs. Johnson.pdf')"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Edit" onclick="requestRevision('Legal Brief - Smith vs. Johnson.pdf')"><i class="bi bi-pencil-square"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Download" onclick="downloadDocument('Legal Brief - Smith vs. Johnson.pdf')"><i class="bi bi-download"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Manage Access" onclick="manageAccess('Legal Brief - Smith vs. Johnson.pdf')"><i class="bi bi-share"></i></button>
                  <button class="btn btn-link p-0" title="More" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                </td>
              </tr>
              <!-- Group: TechCorp Partnership -->
              <tr class="table-group">
                <td colspan="7" class="fw-bold bg-light">TechCorp Partnership</td>
              </tr>
              <tr data-status="pending" data-group="TechCorp Partnership">
                <td><i class="bi bi-file-earmark"></i> <a href="#">NDA - TechCorp Partnership.docx</a></td>
                <td>NDA</td>
                <td><span class="text-warning"><i class="bi bi-clock"></i> Pending</span></td>
                <td>2023-11-14</td>
                <td>Robert Johnson</td>
                <td>1.0</td>
                <td>
                  <button class="btn btn-link p-0 me-2" title="View" onclick="viewDocument('NDA - TechCorp Partnership.docx')"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Edit" onclick="requestRevision('NDA - TechCorp Partnership.docx')"><i class="bi bi-pencil-square"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Download" onclick="downloadDocument('NDA - TechCorp Partnership.docx')"><i class="bi bi-download"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Manage Access" onclick="manageAccess('NDA - TechCorp Partnership.docx')"><i class="bi bi-share"></i></button>
                  <button class="btn btn-link p-0" title="More" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                </td>
              </tr>
              <!-- Group: Property Purchase -->
              <tr class="table-group">
                <td colspan="7" class="fw-bold bg-light">Property Purchase</td>
              </tr>
              <tr data-status="approved" data-group="Property Purchase">
                <td><i class="bi bi-file-earmark"></i> <a href="#">Property Purchase Agreement.pdf</a></td>
                <td>Agreement</td>
                <td><span class="text-success"><i class="bi bi-check-circle"></i> Approved</span></td>
                <td>2023-11-12</td>
                <td>Michael Wong</td>
                <td>1.2</td>
                <td>
                  <button class="btn btn-link p-0 me-2" title="View" onclick="viewDocument('Property Purchase Agreement.pdf')"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Edit" onclick="requestRevision('Property Purchase Agreement.pdf')"><i class="bi bi-pencil-square"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Download" onclick="downloadDocument('Property Purchase Agreement.pdf')"><i class="bi bi-download"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Manage Access" onclick="manageAccess('Property Purchase Agreement.pdf')"><i class="bi bi-share"></i></button>
                  <button class="btn btn-link p-0" title="More" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                </td>
              </tr>
              <!-- Group: Compliance -->
              <tr class="table-group">
                <td colspan="7" class="fw-bold bg-light">Compliance</td>
              </tr>
              <tr data-status="pending" data-group="Compliance">
                <td><i class="bi bi-file-earmark"></i> <a href="#">Compliance Report Q3 2023.xlsx</a></td>
                <td>Report</td>
                <td><span class="text-warning"><i class="bi bi-clock"></i> Pending</span></td>
                <td>2023-11-10</td>
                <td>Sarah Miller</td>
                <td>2.3</td>
                <td>
                  <button class="btn btn-link p-0 me-2" title="View" onclick="viewDocument('Compliance Report Q3 2023.xlsx')"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Edit" onclick="requestRevision('Compliance Report Q3 2023.xlsx')"><i class="bi bi-pencil-square"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Download" onclick="downloadDocument('Compliance Report Q3 2023.xlsx')"><i class="bi bi-download"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Manage Access" onclick="manageAccess('Compliance Report Q3 2023.xlsx')"><i class="bi bi-share"></i></button>
                  <button class="btn btn-link p-0" title="More" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                </td>
              </tr>
              <!-- Group: IP -->
              <tr class="table-group">
                <td colspan="7" class="fw-bold bg-light">Intellectual Property</td>
              </tr>
              <tr data-status="archived" data-group="Intellectual Property">
                <td><i class="bi bi-file-earmark"></i> <a href="#">Intellectual Property Rights.pdf</a></td>
                <td>Policy</td>
                <td><span class="text-secondary"><i class="bi bi-archive"></i> Archived</span></td>
                <td>2023-10-28</td>
                <td>David Clark</td>
                <td>4.0</td>
                <td>
                  <button class="btn btn-link p-0 me-2" title="View" onclick="viewDocument('Intellectual Property Rights.pdf')"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Edit" onclick="requestRevision('Intellectual Property Rights.pdf')"><i class="bi bi-pencil-square"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Download" onclick="downloadDocument('Intellectual Property Rights.pdf')"><i class="bi bi-download"></i></button>
                  <button class="btn btn-link p-0 me-2" title="Manage Access" onclick="manageAccess('Intellectual Property Rights.pdf')"><i class="bi bi-share"></i></button>
                  <button class="btn btn-link p-0" title="More" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end align-items-center mt-3">
          <span class="me-3">Showing 1 to 6 of 24 results</span>
          <nav>
            <ul class="pagination mb-0">
              <li class="page-item disabled"><a class="page-link" href="#">&lt;</a></li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">4</a></li>
              <li class="page-item"><a class="page-link" href="#">&gt;</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modals for actions -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">View Document</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="viewModalBody">
        <!-- Document preview goes here -->
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="revisionModal" tabindex="-1" aria-labelledby="revisionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" onsubmit="submitRevision(event)">
      <div class="modal-header">
        <h5 class="modal-title" id="revisionModalLabel">Request Revision</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="revisionDocName">
        <div class="mb-3">
          <label class="form-label">Revision Note</label>
          <textarea class="form-control" id="revisionNote" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Send Request</button>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="accessModal" tabindex="-1" aria-labelledby="accessModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" onsubmit="submitAccess(event)">
      <div class="modal-header">
        <h5 class="modal-title" id="accessModalLabel">Manage Access</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="accessDocName">
        <div class="mb-3">
          <label class="form-label">Share with (email)</label>
          <input type="email" class="form-control" id="accessEmail" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Permission</label>
          <select class="form-select" id="accessPermission" required>
            <option value="view">View</option>
            <option value="edit">Edit</option>
            <option value="download">Download</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Share</button>
      </div>
    </form>
  </div>
</div>

<script>
function showDocTab(tab) {
  const rows = document.querySelectorAll('#documentsTbody tr:not(.table-group)');
  rows.forEach(row => {
    if (tab === 'all') {
      row.style.display = '';
    } else {
      row.style.display = row.getAttribute('data-status') === tab ? '' : 'none';
    }
  });
  document.querySelectorAll('#docTabs .nav-link').forEach(link => link.classList.remove('active'));
  document.querySelector(`#docTabs .nav-link[onclick*="${tab}"]`).classList.add('active');
}

function filterDocuments() {
  const val = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#documentsTbody tr:not(.table-group)').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
  });
}

function viewDocument(docName) {
  document.getElementById('viewModalBody').innerHTML = '<p>Preview for <b>' + docName + '</b> (demo only)</p>';
  var viewModal = new bootstrap.Modal(document.getElementById('viewModal'));
  viewModal.show();
}

function requestRevision(docName) {
  document.getElementById('revisionDocName').value = docName;
  document.getElementById('revisionNote').value = '';
  var revisionModal = new bootstrap.Modal(document.getElementById('revisionModal'));
  revisionModal.show();
}

function submitRevision(e) {
  e.preventDefault();
  alert('Revision requested for ' + document.getElementById('revisionDocName').value + '!');
  bootstrap.Modal.getInstance(document.getElementById('revisionModal')).hide();
}

function downloadDocument(docName) {
  alert('Downloading ' + docName + ' (demo only)');
  // In real app, trigger download here
}

function manageAccess(docName) {
  document.getElementById('accessDocName').value = docName;
  document.getElementById('accessEmail').value = '';
  document.getElementById('accessPermission').value = 'view';
  var accessModal = new bootstrap.Modal(document.getElementById('accessModal'));
  accessModal.show();
}

function submitAccess(e) {
  e.preventDefault();
  alert('Access granted to ' + document.getElementById('accessEmail').value + ' for ' + document.getElementById('accessDocName').value);
  bootstrap.Modal.getInstance(document.getElementById('accessModal')).hide();
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
