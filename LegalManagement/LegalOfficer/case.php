<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Legal Document Management - Cases</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <style>
    body {
      background: #fafbfc;
      color: #22223b;
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
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
      font-size: 1.6rem;
      color: #fff;
      margin-bottom: 2rem;
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

    .content {
      margin-left: 250px;
      padding: 2rem;
    }

    .case-list {
      background: #fff;
      border-radius: 14px;
      padding: 1.5rem 1rem;
      min-height: 600px;
    }

    .case-list .active-case {
      background: #ede9fe;
      border-radius: 8px;
    }

    .case-list .case-item {
      cursor: pointer;
      border-radius: 8px;
      padding: 0.7rem 1rem;
      margin-bottom: 0.5rem;
    }

    .case-list .case-item:hover {
      background: #f3f4f6;
    }

    .case-list .case-status {
      font-size: 0.95em;
    }

    .case-list .case-docs {
      font-size: 0.95em;
      color: #6c757d;
    }

    .case-details {
      background: #fff;
      border-radius: 14px;
      padding: 1.5rem 1.5rem 1rem 1.5rem;
      min-height: 600px;
    }

    .case-details .info-card {
      background: #f8f9fb;
      border-radius: 10px;
      padding: 1rem 1.2rem;
      margin-bottom: 1rem;
    }

    .case-doc-table th,
    .case-doc-table td {
      vertical-align: middle;
    }

    .case-doc-table th {
      color: #6c757d;
      font-weight: 600;
    }

    .case-doc-table td {
      color: #22223b;
    }

    .case-doc-table .bi {
      margin-right: 0.5rem;
    }

    .case-doc-table .table-actions a {
      color: #6532c9;
      font-weight: 500;
      margin-right: 0.7rem;
      text-decoration: none;
    }

    .case-doc-table .table-actions a:last-child {
      margin-right: 0;
    }

    .case-notes {
      background: #f8f9fb;
      border-radius: 10px;
      padding: 1rem 1.2rem;
      min-height: 70px;
    }

    .btn-purple {
      background: #6532c9;
      color: #fff;
    }

    .btn-purple:hover {
      background: #4311a5;
      color: #fff;
    }

    .header-bar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 1.2rem;
      margin-bottom: 1.5rem;
    }

    .header-bar .btn-primary {
      font-weight: 500;
    }

    .header-bar .bi-bell {
      font-size: 1.5rem;
      color: #6532c9;
      position: relative;
    }

    .header-bar .badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: #9a66ff;
      color: #fff;
      font-size: 0.7rem;
      border-radius: 50%;
      padding: 2px 6px;
    }

    .header-bar .profile-img {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      object-fit: cover;
    }

    .header-bar .profile-name {
      font-weight: 600;
    }

    @media (max-width: 900px) {
      .sidebar {
        left: -260px;
      }

      .sidebar.show {
        left: 0;
      }

      .content {
        margin-left: 0;
        padding: 1rem;
      }

      .header-bar {
        flex-direction: column;
        align-items: flex-end;
        gap: 0.7rem;
      }
    }
  </style>
</head>

<body>
  <button class="sidebar-toggle btn btn-outline-dark m-2" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>

  <div class="sidebar" id="sidebarNav">
    <div class="logo">Legal Officer</div>
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="case.php" class="active"><i class="bi bi-building"></i> Assigned Cases</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="notifications.php"><i class="bi bi-bell"></i> Notifications</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

  <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold mb-0">Legal Document Management</h2>
      <div class="header-bar">
        <form class="d-flex align-items-center" role="search">
          <input class="form-control me-2" type="search" placeholder="Search cases..." id="caseSearchInput"
            style="max-width:220px;">
        </form>
        <button class="btn btn-outline-secondary d-flex align-items-center"><i class="bi bi-funnel me-2"></i>Filter
        </button>
        <button class="btn btn-primary d-flex align-items-center"><i class="bi bi-upload me-2"></i>Upload</button>
        <span class="position-relative">
          <i class="bi bi-bell"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
        </span>
        <div class="d-flex align-items-center gap-2">
          <img src="https://ui-avatars.com/api/?name=John+Doe" alt="Profile" class="profile-img">
          <span class="profile-name">John Doe</span>
        </div>
      </div>
    </div>
    <div class="row g-4">
      <!-- Left: Legal Cases List -->
      <div class="col-lg-4">
        <div class="case-list p-0">
          <div class="d-flex align-items-center mb-3 px-3 pt-3">
            <input type="text" class="form-control me-2" placeholder="Search cases..." id="caseListSearch">
            <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filter</button>
          </div>
          <div id="caseList">
            <!-- Cases will be rendered here -->
          </div>
          <div class="p-3">
            <button class="btn btn-outline-primary w-100" onclick="alert('New case creation (demo)')"><i
                class="bi bi-plus-circle"></i> New Case</button>
          </div>
        </div>
      </div>
      <!-- Right: Case Details -->
      <div class="col-lg-8">
        <div class="case-details">
          <div id="caseDetailHeader">
            <!-- Case details header will be rendered here -->
          </div>
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
              </div>
            </div>
            <div class="col-md-4">
              <div class="info-card">
                <div class="small text-muted mb-1"><i class="bi bi-files"></i> Documents</div>
                <div id="caseDocCount" class="fw-semibold"></div>
              </div>
            </div>
          </div>
          <h6 class="fw-bold mt-3 mb-2">Case Documents</h6>
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
                <!-- Documents will be rendered here -->
              </tbody>
            </table>
          </div>
          <h6 class="fw-bold mt-4 mb-2">Case Notes <a href="#" class="small ms-2" onclick="addNote();return false;">Add
              Note</a></h6>
          <div class="case-notes mb-3" id="caseNotes">
            No case notes yet.
          </div>
          <div class="d-flex gap-2 justify-content-end mt-4">
            <button class="btn btn-outline-primary" onclick="alert('Upload Document (demo)')">Upload Document</button>
            <button class="btn btn-outline-secondary" onclick="alert('Edit Case (demo)')">Edit Case</button>
            <button class="btn btn-purple" onclick="alert('Create Document (demo)')">Create Document</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    const cases = [
      {
        id: 1,
        name: "ABC Corp Hiring",
        client: "ABC Corporation",
        status: "Active",
        documents: [
          { title: "Employment Contract - ABC Corp", type: "Contract", version: "v2.1", status: "In Review" },
          { title: "Offer Letter - ABC Corp", type: "Letter", version: "v1.0", status: "Completed" },
          { title: "Background Check Authorization", type: "Form", version: "v1.0", status: "Completed" }
        ],
        assignedTo: "John Doe",
        startDate: "2023-04-15",
        notes: []
      },
      {
        id: 2,
        name: "XYZ Partnership",
        client: "XYZ Technologies",
        status: "Active",
        documents: [
          { title: "NDA - XYZ Technologies", type: "Non-Disclosure", version: "v1.3", status: "Pending Clarification" },
          { title: "Collaboration Agreement", type: "Agreement", version: "v1.0", status: "Completed" }
        ],
        assignedTo: "John Doe",
        startDate: "2023-05-01",
        notes: []
      },
      {
        id: 3,
        name: "Office Relocation",
        client: "Internal",
        status: "Active",
        documents: [
          { title: "Lease Agreement - Office Space", type: "Real Estate", version: "v3.0", status: "Completed" },
          { title: "Move Checklist", type: "Checklist", version: "v1.0", status: "Completed" }
        ],
        assignedTo: "John Doe",
        startDate: "2023-03-20",
        notes: []
      },
      {
        id: 4,
        name: "Product Innovation",
        client: "Research Division",
        status: "Active",
        documents: [
          { title: "Patent Application - New Product", type: "Intellectual Property", version: "v1.0", status: "Draft" }
        ],
        assignedTo: "John Doe",
        startDate: "2023-06-10",
        notes: []
      },
      {
        id: 5,
        name: "DEF Acquisition",
        client: "DEF Inc",
        status: "Active",
        documents: [
          { title: "Merger Agreement - DEF Inc", type: "Corporate", version: "v2.4", status: "In Review" },
          { title: "Due Diligence Report", type: "Report", version: "v1.0", status: "Completed" }
        ],
        assignedTo: "John Doe",
        startDate: "2023-02-18",
        notes: []
      }
    ];

    let selectedCaseId = cases[0].id;

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
          const activeClass = c.id === selectedCaseId ? 'active-case' : '';
          list.innerHTML += `
          <div class="case-item ${activeClass}" onclick="selectCase(${c.id})">
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
      const c = cases.find(ca => ca.id === selectedCaseId);
      document.getElementById('caseDetailHeader').innerHTML = `
    <h4 class="fw-bold mb-1">${c.name}</h4>
    <div class="mb-2 text-muted">Client: ${c.client}</div>
  `;
      document.getElementById('caseStartDate').textContent = c.startDate;
      document.getElementById('caseAssignedTo').textContent = c.assignedTo;
      document.getElementById('caseDocCount').textContent = `${c.documents.length} total`;
      // Documents
      let docRows = '';
      c.documents.forEach(doc => {
        let statusClass = '';
        if (doc.status === 'In Review') statusClass = 'status-inreview';
        else if (doc.status === 'Pending Clarification') statusClass = 'status-pending';
        else if (doc.status === 'Completed') statusClass = 'status-completed';
        else if (doc.status === 'Draft') statusClass = 'status-draft';
        docRows += `
      <tr>
        <td><i class="bi bi-file-earmark-text"></i> ${doc.title}</td>
        <td>${doc.type}</td>
        <td>${doc.version}</td>
        <td class="${statusClass}">${doc.status}</td>
        <td class="table-actions">
          <a href="#" onclick="alert('View: ${doc.title}');return false;">View</a>
          <a href="#" onclick="alert('Edit: ${doc.title}');return false;">Edit</a>
        </td>
      </tr>
    `;
      });
      document.getElementById('caseDocTable').innerHTML = docRows;
      // Notes
      const notesDiv = document.getElementById('caseNotes');
      if (c.notes.length === 0) {
        notesDiv.innerHTML = 'No case notes yet.';
      } else {
        notesDiv.innerHTML = c.notes.map(n => `<div class="mb-2">${n}</div>`).join('');
      }
    }

    function selectCase(id) {
      selectedCaseId = id;
      renderCaseList();
      renderCaseDetails();
    }

    function addNote() {
      const note = prompt('Enter your note for this case:');
      if (note && note.trim()) {
        const c = cases.find(ca => ca.id === selectedCaseId);
        c.notes.push(note.trim());
        renderCaseDetails();
      }
    }

    document.getElementById("sidebarToggle").addEventListener("click", function () {
      document.getElementById("sidebarNav").classList.toggle("show");
    });
    document.getElementById('caseListSearch').addEventListener('input', renderCaseList);

    window.onload = function () {
      renderCaseList();
      renderCaseDetails();
    };
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
