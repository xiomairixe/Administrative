<?php
  include  ('../connection.php');
  // Show only legal documents
  $sql = "SELECT * FROM document WHERE docu_type = 'legal'";
  $documents = $conn->query($sql) or die ($conn->error);
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
      <a class="nav-link" href="index.php"><ion-icon name="home-outline"></ion-icon>Dashboard</a>
      <a class="nav-link" href="cases.php"><ion-icon name="newspaper-outline"></ion-icon>Casses</a>
      <a class="nav-link active" href="#"><ion-icon name="document-text-outline"></ion-icon>Documents </a>
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
              <?php while ($row = $documents->fetch_assoc()) { ?>
                <tr data-status="<?php echo htmlspecialchars($row['status']); ?>">
                  <td><i class="bi bi-file-earmark"></i> <a href="#"><?php echo htmlspecialchars($row['file_name']); ?></a></td>
                  <td><?php echo htmlspecialchars($row['docu_type']); ?></td>
                  <td><span class="text-success"><i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($row['status']); ?></span></td>
                  <td><?php echo htmlspecialchars($row['uploaded_at']); ?></td>
                  <td><?php echo htmlspecialchars($row['uploaded_by']); ?></td>
                  <td><?php echo htmlspecialchars($row['document_id']); ?></td>
                  <td>
                    <button class="btn btn-link p-0 me-2" title="View" onclick="viewDocument('<?php echo addslashes($row['file_name']); ?>')"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-link p-0 me-2" title="Edit" onclick="requestRevision('<?php echo addslashes($row['file_name']); ?>')"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-link p-0 me-2" title="Download" onclick="downloadDocument('<?php echo addslashes($row['file_name']); ?>')"><i class="bi bi-download"></i></button>
                    <button class="btn btn-link p-0 me-2" title="Manage Access" onclick="manageAccess('<?php echo addslashes($row['file_name']); ?>')"><i class="bi bi-share"></i></button>
                    <button class="btn btn-link p-0" title="More" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                  </td>
                </tr>
              <?php } ?>
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
