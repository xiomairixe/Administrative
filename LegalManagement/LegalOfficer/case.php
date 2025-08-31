<?php
// filepath: c:\xampp\htdocs\Administrative\LegalManagement\LegalOfficer\case.php
include_once("../../connection.php");

// Handle file upload for case documents
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_document']) && isset($_FILES['file'])) {
    $case_id = intval($_POST['case_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $type = $conn->real_escape_string($_POST['type']);
    $version = $conn->real_escape_string($_POST['version']);
    $status = $conn->real_escape_string($_POST['status']);

    // File upload handling
    $upload_dir = __DIR__ . "/../../Admin/documentManagement/action/uploads/" . $case_id . "/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

    $file_name = basename($_FILES['file']['name']);
    $file_path = $upload_dir . $file_name;
    $db_file_path = "uploads/" . $case_id . "/" . $file_name;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
        // Insert into document table
        $uploaded_by = 1; // Replace with session user_id in real app
        $description = $title;
        $conn->query("INSERT INTO document (file_name, file_path, uploaded_by, docu_type, status, description) 
            VALUES ('$file_name', '$db_file_path', $uploaded_by, '$type', '$status', '$description')");
        $document_id = $conn->insert_id;

        // Insert into case_documents
        $conn->query("INSERT INTO case_documents (case_id, title, type, version, status, file_path) 
            VALUES ($case_id, '$title', '$type', '$version', '$status', '$db_file_path')");
        $case_doc_id = $conn->insert_id;

        $doc = $conn->query("SELECT * FROM case_documents WHERE id=$case_doc_id")->fetch_assoc();
        header('Content-Type: application/json');
        echo json_encode(['success'=>true, 'document'=>$doc]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success'=>false, 'error'=>'File upload failed.']);
    }
    exit;
}

// Fetch all cases with assigned user info
$cases_res = $conn->query("
    SELECT c.*, u.fullname AS assigned_to_name
    FROM cases c
    LEFT JOIN users u ON c.assigned_to = u.user_id
    ORDER BY c.created_at DESC
");
$cases = [];
while ($c = $cases_res->fetch_assoc()) {
    $case_id = $c['case_id'];
    // Fetch documents for this case
    $docs = [];
    $doc_res = $conn->query("SELECT * FROM case_documents WHERE case_id = $case_id");
    while ($d = $doc_res->fetch_assoc()) $docs[] = $d;
    // Fetch notes for this case
    $notes = [];
    $note_res = $conn->query("SELECT cn.*, u.fullname FROM case_notes cn LEFT JOIN users u ON cn.user_id = u.user_id WHERE cn.case_id = $case_id ORDER BY cn.created_at DESC");
    while ($n = $note_res->fetch_assoc()) $notes[] = $n;
    $c['documents'] = $docs;
    $c['notes'] = $notes;
    $cases[] = $c;
}

// Fetch all legal officers for assignment
$officers = [];
$officer_res = $conn->query("SELECT user_id, fullname FROM users WHERE role='Compliance Officer'");
while ($o = $officer_res->fetch_assoc()) $officers[] = $o;

// Handle add note AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
    $case_id = intval($_POST['case_id']);
    $user_id = 8; // Example: use session user_id in real app
    $note = $conn->real_escape_string($_POST['note']);
    $conn->query("INSERT INTO case_notes (case_id, user_id, note) VALUES ($case_id, $user_id, '$note')");
    $note_id = $conn->insert_id;
    $note_row = $conn->query("SELECT cn.*, u.fullname FROM case_notes cn LEFT JOIN users u ON cn.user_id = u.user_id WHERE cn.id = $note_id")->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode(['success'=>true, 'note'=>$note_row]);
    exit;
}

// Handle assign officer AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_officer'])) {
    $case_id = intval($_POST['case_id']);
    $assigned_to = intval($_POST['assigned_to']);
    $conn->query("UPDATE cases SET assigned_to=$assigned_to WHERE case_id=$case_id");
    $officer = $conn->query("SELECT fullname FROM users WHERE user_id=$assigned_to")->fetch_assoc();
    header('Content-Type: application/json');
    echo json_encode(['success'=>true, 'assigned_to'=>$officer['fullname']]);
    exit;
}
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
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="#" class="active"><i class="bi bi-building"></i> Assigned Cases</a>
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
      <h2 class="fw-bold">Legal Document Management</h2>
      <div class="d-flex align-items-center gap-3">
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

    <div class="row g-4">
        <!-- Case List -->
        <div class="col-lg-4">
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Search cases..." id="caseListSearch">
          </div>
          <div id="caseList">
            <!-- Rendered by JS -->
          </div>
          <div class="p-3">
            <button class="btn btn-outline-primary w-100" onclick="alert('New case creation (demo)')"><i class="bi bi-plus-circle"></i> New Case</button>
          </div>
        </div>
        <!-- Case Details -->
        <div class="col-lg-8">
          <div id="caseDetailHeader"></div>
          <div class="row mb-3">
            <div class="col-md-4">
              <div class="info-card">
                <div class="small text-muted mb-1"><i class="bi bi-calendar"></i> Start Date</div>
                <div id="caseStartDate" class="fw-semibold"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="info-card">
                <div class="small text-muted mb-1"><i class="bi bi-person"></i> Assigned To</div>
                <div id="caseAssignedTo" class="fw-semibold"></div>
                <select id="assignOfficerSelect" class="form-select form-select-sm mt-2" style="display:none;"></select>
                <button class="btn btn-sm btn-outline-secondary mt-2" id="assignOfficerBtn" style="display:none;">Assign</button>
                <button class="btn btn-sm btn-link mt-2" id="showAssignOfficerBtn">Change</button>
              </div>
            </div>
            <div class="col-md-4">
              <div class="info-card">
                <div class="small text-muted mb-1"><i class="bi bi-files"></i> Documents</div>
                <div id="caseDocCount" class="fw-semibold"></div>
              </div>
            </div>
          </div>
          <h6 class="fw-bold mt-3 mb-2">Case Documents <a href="#" class="small ms-2" onclick="showAddDocModal();return false;">Add Document</a></h6>
          <div class="table-responsive">
            <table class="table case-doc-table align-middle mb-3">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Type</th>
                  <th>Version</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="caseDocTable">
                <!-- Rendered by JS -->
              </tbody>
            </table>
          </div>
          <h6 class="fw-bold mt-4 mb-2">Case Notes <a href="#" class="small ms-2" onclick="addNote();return false;">Add Note</a></h6>
          <div class="case-notes mb-3" id="caseNotes">
            No case notes yet.
          </div>
        </div>
      </div>
<!-- Add Document Modal -->
  <div class="modal fade" id="addDocModal" tabindex="-1" aria-labelledby="addDocModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" id="addDocForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="addDocModalLabel">Add Case Document</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="case_id" id="addDocCaseId">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Version</label>
            <input type="text" name="version" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option>Draft</option>
              <option>In Review</option>
              <option>Pending Clarification</option>
              <option>Completed</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">File</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.txt" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Document</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    // PHP data to JS
    const cases = <?php echo json_encode($cases); ?>;
    const officers = <?php echo json_encode($officers); ?>;
    let selectedCaseId = cases.length > 0 ? cases[0].case_id : null;

    function renderCaseList() {
      const list = document.getElementById('caseList');
      const searchVal = (document.getElementById('caseListSearch').value || '').toLowerCase();
      list.innerHTML = '';
      cases.forEach(c => {
        if (
          !searchVal ||
          c.name.toLowerCase().includes(searchVal) ||
          c.client.toLowerCase().includes(searchVal)
        ) {
          const activeClass = parseInt(c.case_id) === parseInt(selectedCaseId) ? 'active-case' : '';
          list.innerHTML += `
          <div class="case-item ${activeClass}" onclick="selectCase(${c.case_id})">
            <div class="fw-semibold"><i class="bi bi-folder${activeClass ? '-fill' : ''} me-2"></i>${c.name}</div>
            <div class="small text-muted">Client: ${c.client}</div>
            <div class="case-status text-success small">${c.status}</div>
            <div class="case-docs small">${c.documents.length} document${c.documents.length !== 1 ? 's' : ''}</div>
          </div>
        `;
        }
      });
    }

    function renderCaseDetails() {
      const c = cases.find(ca => parseInt(ca.case_id) === parseInt(selectedCaseId));
      if (!c) return;
      document.getElementById('caseDetailHeader').innerHTML = `
        <h4 class="fw-bold mb-1">${c.name}</h4>
        <div class="mb-2 text-muted">Client: ${c.client}</div>
      `;
      document.getElementById('caseStartDate').textContent = c.start_date;
      document.getElementById('caseAssignedTo').textContent = c.assigned_to_name || 'Unassigned';
      document.getElementById('caseDocCount').textContent = `${c.documents.length} total`;

      // Assign Officer UI
      document.getElementById('showAssignOfficerBtn').onclick = function() {
        document.getElementById('assignOfficerSelect').style.display = '';
        document.getElementById('assignOfficerBtn').style.display = '';
        this.style.display = 'none';
        // Populate select
        let sel = document.getElementById('assignOfficerSelect');
        sel.innerHTML = officers.map(o => `<option value="${o.user_id}" ${c.assigned_to == o.user_id ? 'selected' : ''}>${o.fullname}</option>`).join('');
      };
      document.getElementById('assignOfficerBtn').onclick = function() {
        let officerId = document.getElementById('assignOfficerSelect').value;
        fetch('', {
          method: 'POST',
          headers: {'Content-Type':'application/x-www-form-urlencoded'},
          body: `assign_officer=1&case_id=${c.case_id}&assigned_to=${officerId}`
        }).then(r=>r.json()).then(data=>{
          if(data.success) {
            c.assigned_to = officerId;
            c.assigned_to_name = officers.find(o=>o.user_id==officerId).fullname;
            renderCaseDetails();
          }
        });
      };
      document.getElementById('assignOfficerSelect').style.display = 'none';
      document.getElementById('assignOfficerBtn').style.display = 'none';
      document.getElementById('showAssignOfficerBtn').style.display = '';

      // Documents
      let docRows = '';
      c.documents.forEach(doc => {
        let statusClass = '';
        if (doc.status === 'In Review') statusClass = 'badge bg-warning text-dark';
        else if (doc.status === 'Pending Clarification') statusClass = 'badge bg-danger';
        else if (doc.status === 'Completed') statusClass = 'badge bg-success';
        else if (doc.status === 'Draft') statusClass = 'badge bg-secondary';
        docRows += `
          <tr>
            <td><i class="bi bi-file-earmark-text"></i> ${doc.title}</td>
            <td>${doc.type}</td>
            <td>${doc.version || ''}</td>
            <td><span class="${statusClass}">${doc.status}</span></td>
            <td>
              <a href="../../Admin/documentManagement/${doc.file_path}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>
            </td>
          </tr>
        `;
      });
      document.getElementById('caseDocTable').innerHTML = docRows;

      // Notes
      const notesDiv = document.getElementById('caseNotes');
      if (!c.notes || c.notes.length === 0) {
        notesDiv.innerHTML = 'No case notes yet.';
      } else {
        notesDiv.innerHTML = c.notes.map(n => `
          <div class="mb-2">
            <span class="note-user">${n.fullname}</span>
            <span class="note-date">(${n.created_at})</span>
            <div class="note-content">${n.note}</div>
          </div>
        `).join('');
      }
    }

    function selectCase(id) {
      selectedCaseId = parseInt(id);
      renderCaseList();
      renderCaseDetails();
    }

    function addNote() {
      const note = prompt('Enter your note for this case:');
      if (note && note.trim()) {
        fetch('', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `add_note=1&case_id=${selectedCaseId}&note=${encodeURIComponent(note)}`
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            const c = cases.find(ca => parseInt(ca.case_id) === parseInt(selectedCaseId));
            c.notes.unshift(data.note);
            renderCaseDetails();
          } else {
            alert('Failed to add note.');
          }
        });
      }
    }

    function showAddDocModal() {
      const c = cases.find(ca => parseInt(ca.case_id) === parseInt(selectedCaseId));
      document.getElementById('addDocCaseId').value = c.case_id;
      var modal = new bootstrap.Modal(document.getElementById('addDocModal'));
      modal.show();
    }

    document.getElementById('addDocForm').onsubmit = function(e) {
      e.preventDefault();
      const form = e.target;
      const fd = new FormData(form);
      fd.append('add_document', 1);
      fetch('', {
        method: 'POST',
        body: fd
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const c = cases.find(ca => parseInt(ca.case_id) === parseInt(selectedCaseId));
          c.documents.push(data.document);
          renderCaseDetails();
          bootstrap.Modal.getInstance(document.getElementById('addDocModal')).hide();
          form.reset();
        } else {
          alert(data.error || 'Failed to add document.');
        }
      });
    };

    document.getElementById('caseListSearch').addEventListener('input', renderCaseList);

    window.onload = function () {
  if (cases.length > 0) selectedCaseId = parseInt(cases[0].case_id);
  renderCaseList();
  renderCaseDetails();
};
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>