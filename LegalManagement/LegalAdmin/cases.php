<?php 
include('../connection.php');

// Fetch cases and legal requests
$sql = "SELECT lr.*, c.case_id, c.status AS case_status, c.assigned_to, c.start_date, u.fullname AS officer_name
        FROM legal_requests lr
        LEFT JOIN cases c ON lr.request_id = c.request_id
        LEFT JOIN users u ON c.assigned_to = u.user_id
        ORDER BY lr.created_at DESC";
$cases = $conn->query($sql) or die ($conn->error);

// Fetch legal officers
$sql1 = "SELECT * FROM users WHERE role = 'Legal Officer'";
$user = $conn->query($sql1) or die ($conn->error);
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
      <a class="nav-link active" href="#"><ion-icon name="newspaper-outline"></ion-icon>Casses</a>
      <a class="nav-link" href="documents.php"><ion-icon name="document-text-outline"></ion-icon>Documents </a>
      <a class="nav-link" href="reports.php"><ion-icon name="bar-chart-outline"></ion-icon>Reports</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

<!-- Main Content Column -->
    <div class="col main-content" style="background:#f8f9fb; min-height:100vh; margin-left:250px; padding:2rem 2rem 2rem 2rem;">
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
              <?php while ($row = $cases->fetch_assoc()): ?>
              <tr data-status="<?php echo $row['case_status'] ?? 'active'; ?>">
                <td>
                  <i class="bi bi-briefcase text-primary"></i>
                  <span class="fw-bold"><?php echo htmlspecialchars($row['description']);?></span><br>
                  <span class="text-muted small"><?php echo htmlspecialchars($row['request_id']);?></span>
                  <?php if ($row['case_id']): ?>
                    <br><span class="badge bg-info">Legal Case #<?php echo $row['case_id'];?></span>
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['request_type']);?></td>
                <td>
                  <span class="badge bg-primary"><?php echo htmlspecialchars($row['status']);?></span>
                  <?php if ($row['case_id']): ?>
                    <span class="badge bg-warning text-dark ms-1">Under Legal Officer</span>
                  <?php endif; ?>
                </td>
                <td><span class="badge bg-danger bg-opacity-10 text-danger"><?php echo htmlspecialchars($row['priority']);?></span></td>
                <td><i class="bi bi-calendar"></i> <?php echo htmlspecialchars($row['created_at']);?></td>
                <td>
                  <i class="bi bi-person"></i>
                  <?php echo htmlspecialchars($row['officer_name'] ?? $row['user_id']);?>
                </td>
                <td><i class="bi bi-file-earmark"></i> <?php echo htmlspecialchars($row['stakeholders']);?></td>
                <td>
                  <?php if ($row['case_id']): ?>
                    <button class="btn btn-sm btn-outline-secondary" disabled>Sent to Legal Officer</button>
                    <button class="btn btn-sm btn-outline-primary view-case-btn" data-caseid="<?php echo $row['case_id']; ?>">View Case</button>
                  <?php else: ?>
                    <button class="btn btn-sm btn-outline-primary send-legal-btn" data-requestid="<?php echo $row['request_id']; ?>">Send to Legal Officer</button>
                  <?php endif; ?>
                  <button class="btn btn-sm btn-outline-primary" onclick="openReviewModal('<?php echo $row['request_id'];?>', '<?php echo addslashes($row['description']); ?>', '<?php echo addslashes($row['request_type']); ?>', '<?php echo addslashes($row['stakeholders']); ?>', '<?php echo addslashes($row['status']); ?>', '<?php echo addslashes($row['priority']); ?>')">Review</button>
                </td>
              </tr>
              <?php endwhile; ?>
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

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">Review Legal Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="reviewRequestId">
        <div class="mb-3">
          <label class="form-label">Description</label>
          <div id="reviewDescription" class="form-control-plaintext"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Request Type</label>
          <div id="reviewRequestType" class="form-control-plaintext"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Stakeholders</label>
          <div id="reviewStakeholders" class="form-control-plaintext"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Status</label>
          <div id="reviewStatus" class="form-control-plaintext"></div>
        </div> 
        <div class="mb-3">
          <label class="form-label">Priority</label>
          <div id="reviewPriority" class="form-control-plaintext"></div>
        </div>
        <div class="mb-3">
          <label class="form-label">Review Note</label>
          <textarea class="form-control" id="reviewNote" rows="3" placeholder="Add your review note here..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit Review</button>
      </div>
    </form>
  </div>
</div>

<!-- View Case Modal -->
<div class="modal fade" id="viewCaseModal" tabindex="-1" aria-labelledby="viewCaseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-4">
      <div class="modal-header bg-white rounded-top-4">
        <h4 class="modal-title fw-bold" id="viewCaseModalLabel">Case Details</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="caseDetailContent">
          <!-- Populated by JS -->
        </div>
      </div>
    </div>
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

// Review action
function openReviewModal(requestId, description, requestType, stakeholders, status, priority) {
  document.getElementById('reviewRequestId').value = requestId;
  document.getElementById('reviewDescription').textContent = description;
  document.getElementById('reviewRequestType').textContent = requestType;
  document.getElementById('reviewStakeholders').textContent = stakeholders;
  document.getElementById('reviewStatus').textContent = status;
  document.getElementById('reviewPriority').textContent = priority;
  document.getElementById('reviewNote').value = '';
  var reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
  reviewModal.show();
}

// Review form submit
document.querySelector('#reviewModal form').addEventListener('submit', function(e) {
  e.preventDefault();
  alert('Review submitted for request ID: ' + document.getElementById('reviewRequestId').value);
  bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
});

// Send to Legal Officer (AJAX)
document.querySelectorAll('.send-legal-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    var requestId = this.getAttribute('data-requestid');
    if (confirm("Send this request to Legal Officer?")) {
      fetch('send_to_legal_officer.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'request_id=' + encodeURIComponent(requestId)
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('Legal Request is sent to Legal Officer.');
          location.reload();
        } else {
          alert('Failed to update status.' + (data.error ? "\n" + data.error : ""));
        }
      })
      .catch(() => {
        alert('Error sending to Legal Officer.');
      });
    }
  });
});

// View Case Modal functionality
document.querySelectorAll('.view-case-btn').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const caseId = btn.getAttribute('data-caseid');
    fetch('get_case_details.php?case_id=' + encodeURIComponent(caseId))
      .then(res => res.json())
      .then(data => {
        let html = `
          <h5 class="fw-bold mb-2">${data.name || 'No Title'}</h5>
          <div class="mb-2"><strong>Client:</strong> ${data.client || '-'}</div>
          <div class="mb-2"><strong>Status:</strong> ${data.status || '-'}</div>
          <div class="mb-2"><strong>Assigned To:</strong> ${data.assigned_to_name || '-'}</div>
          <div class="mb-2"><strong>Start Date:</strong> ${data.start_date ? new Date(data.start_date).toLocaleDateString() : '-'}</div>
          <hr>
          <h6 class="fw-bold mt-3 mb-2">Case Documents</h6>
          <ul class="list-group mb-3">
        `;
        if (data.documents && data.documents.length) {
          data.documents.forEach(doc => {
            html += `<li class="list-group-item d-flex justify-content-between align-items-center">
              <span>${doc.title} <span class="text-muted small">(${doc.type})</span></span>
              <a href="${doc.file_path}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
            </li>`;
          });
        } else {
          html += `<li class="list-group-item text-muted">No documents.</li>`;
        }
        html += `</ul>
          <h6 class="fw-bold mt-4 mb-2">Case Notes</h6>
          <div class="case-notes mb-3">`;
        if (data.notes && data.notes.length) {
          data.notes.forEach(note => {
            html += `<div class="mb-2">
              <strong>${note.author}</strong> <span class="text-muted small">(${note.date})</span><br>
              <span>${note.comment}</span>
            </div>`;
          });
        } else {
          html += `<span class="text-muted">No case notes yet.</span>`;
        }
        html += `</div>`;
        document.getElementById('caseDetailContent').innerHTML = html;
        var modal = new bootstrap.Modal(document.getElementById('viewCaseModal'));
        modal.show();
      })
      .catch(() => {
        document.getElementById('caseDetailContent').innerHTML = '<div class="text-danger">Error loading case details.</div>';
        var modal = new bootstrap.Modal(document.getElementById('viewCaseModal'));
        modal.show();
      });
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
