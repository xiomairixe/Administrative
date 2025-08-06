<?php 
 include('../connection.php');
 
 $sql = "SELECT * FROM legal_requests";
 $cases = $conn->query($sql) or die ($conn->error);
 $row = $cases->fetch_assoc();

 $sql1 = "SELECT * FROM users WHERE role = 'Legal Officer'";
 $user = $conn->query($sql1) or die ($conn->error);
 $row1 = $user->fetch_assoc();
?>

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
    <div class="col main-content" style="background:#f8f9fb;">
      <div class="container-fluid py-4">
        <h2 class="dashboard-title mb-1">Cases</h2>
        <p class="mb-4" style="color:#6c757d;">Manage and track legal cases and matters.</p>
        <div class="d-flex flex-wrap gap-2 mb-3">
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCaseModal"><i class="bi bi-plus"></i> New Case</button>
          <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="bi bi-funnel"></i> Filter</button>
          <div class="ms-auto" style="min-width:280px;">
            <input type="text" class="form-control" id="searchInput" placeholder="Search cases..." oninput="filterCases()">
          </div>
        </div>
        <ul class="nav nav-tabs mb-3" id="caseTabs">
          <li class="nav-item"><a class="nav-link active" href="#" onclick="showTab('all')">All Cases</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showTab('active')">Active</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showTab('pending')">Pending</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="showTab('closed')">Closed</a></li>
        </ul>
        <div class="table-responsive">
          <table class="table align-middle mb-0" id="casesTable">
            <thead>
              <tr>
                <th>Case</th>
                <th>Type</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Date</th>
                <th>Assignee</th>
                <th>Documents</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="casesTbody">
              <!-- Example rows, replace with PHP loop for dynamic data -->
               <?php do {?>
              <tr data-status="active">
                <td>
                  <i class="bi bi-briefcase text-primary"></i>
                  <span class="fw-bold"><?php echo $row['description'];?></span><br>
                  <span class="text-muted small"><?php echo $row['request_id'];?></span>
                </td>
                <td><?php echo $row['request_type'];?></td>
                <td><span class="badge bg-primary"><?php echo $row['status'];?></span></td>
                <td><span class="badge bg-danger bg-opacity-10 text-danger"><?php echo $row['priority'];?></span></td>
                <td><i class="bi bi-calendar"></i> <?php echo $row['created_at'];?></td>
                <td><i class="bi bi-person"></i><?php echo $row['user_id'];?></td>
                <td><i class="bi bi-file-earmark"></i> <?php echo $row['stakeholders'];?></td>
                <td>
                  <a href="#" class="text-decoration-none me-2">View</a>
                  <button class="btn btn-sm btn-outline-secondary" onclick="openAssignModal('Smith vs. Johnson Corporation')">Assign</button>
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"></button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Edit</a></li>
                    <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
                  </ul>
                </td>
              </tr>
              <?php } while ($row = $cases->fetch_assoc()); ?>
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-end align-items-center mt-3">
          <span class="me-3">Showing 1 to 6 of 20 results</span>
          <nav>
            <ul class="pagination mb-0">
              <li class="page-item disabled"><a class="page-link" href="#">&lt;</a></li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">&gt;</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" onsubmit="assignCase(event)">
      <div class="modal-header">
        <h5 class="modal-title" id="assignModalLabel">Assign Legal Case</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Case</label>
          <input type="text" class="form-control" id="assignCaseName" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Assign to</label>
          <select class="form-select" id="assignTo" required>
            <option value="">Select user</option>
            <?php do {?>
            <option><?php echo $row1['fullname'];?></option>
            <?php } while ($row1 = $user->fetch_assoc()); ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Note (optional)</label>
          <textarea class="form-control" id="assignNote" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Assign</button>
      </div>
    </form>
  </div>
</div>

<!-- New Case Modal (for demonstration, not functional) -->
<div class="modal fade" id="newCaseModal" tabindex="-1" aria-labelledby="newCaseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newCaseModalLabel">New Case</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Case Name</label>
          <input type="text" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Type</label>
          <input type="text" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Priority</label>
          <select class="form-select" required>
            <option>High</option>
            <option>Medium</option>
            <option>Low</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Assignee</label>
          <select class="form-select" required>
            <option>Jane Doe</option>
            <option>John Smith</option>
            <option>Emily Chen</option>
            <option>Robert Johnson</option>
            <option>Sarah Miller</option>
            <option>Michael Wong</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Create Case</button>
      </div>
    </form>
  </div>
</div>

<!-- Filter Modal (for demonstration, not functional) -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterModalLabel">Filter Cases</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Status</label>
          <select class="form-select">
            <option value="">All</option>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="closed">Closed</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Priority</label>
          <select class="form-select">
            <option value="">All</option>
            <option>High</option>
            <option>Medium</option>
            <option>Low</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Apply Filter</button>
      </div>
    </form>
  </div>
</div>

<script>
function showTab(tab) {
  const rows = document.querySelectorAll('#casesTbody tr');
  rows.forEach(row => {
    if (tab === 'all') {
      row.style.display = '';
    } else {
      row.style.display = row.getAttribute('data-status') === tab ? '' : 'none';
    }
  });
  document.querySelectorAll('#caseTabs .nav-link').forEach(link => link.classList.remove('active'));
  document.querySelector(`#caseTabs .nav-link[onclick*="${tab}"]`).classList.add('active');
}

function filterCases() {
  const val = document.getElementById('searchInput').value.toLowerCase();
  document.querySelectorAll('#casesTbody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
  });
}

function openAssignModal(caseName) {
  document.getElementById('assignCaseName').value = caseName;
  var assignModal = new bootstrap.Modal(document.getElementById('assignModal'));
  assignModal.show();
}

function assignCase(e) {
  e.preventDefault();
  // Here you would send the assignment to the server via AJAX
  alert('Case assigned to ' + document.getElementById('assignTo').value + '!');
  bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
