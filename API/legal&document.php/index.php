<?php
  require '../../connection.php';

  // Get all folders with their names and counts
  $folders = [];
  $folderCounts = [];
  $folderNames = [];
  $res = $conn->query("SELECT f.folder_id, f.folder_name, COUNT(d.document_id) as cnt 
                       FROM folder f 
                       LEFT JOIN document d ON f.folder_id = d.folder_id 
                       GROUP BY f.folder_id, f.folder_name");
  $totalCount = 0;
  while ($f = $res->fetch_assoc()) {
    $folders[] = $f['folder_id'];
    $folderNames[$f['folder_id']] = $f['folder_name'];
    $folderCounts[$f['folder_id']] = $f['cnt'];
    $totalCount += $f['cnt'];
  }

  // Get all unique tags/categories from documents
  $tags = [];
  $tagRes = $conn->query("SELECT DISTINCT tag FROM document WHERE tag IS NOT NULL AND tag != ''");
  while ($t = $tagRes->fetch_assoc()) {
    foreach (explode(',', $t['tag']) as $tag) {
      $tag = trim($tag);
      if ($tag && !in_array($tag, $tags)) $tags[] = $tag;
    }
  }
  sort($tags);

  // Handle tag filter
  $tagFilter = isset($_GET['tag']) ? $_GET['tag'] : '';
  $tagWhere = '';
  if ($tagFilter) {
    $tagWhere = " AND (FIND_IN_SET('" . $conn->real_escape_string($tagFilter) . "', tag) > 0)";
  }

  // Legal document monitoring stats
  $legalTotal = $conn->query("SELECT COUNT(*) AS cnt FROM document WHERE docu_type = 'legal' AND status != 'trash'")->fetch_assoc()['cnt'];
  $legalPending = $conn->query("SELECT COUNT(*) AS cnt FROM document WHERE docu_type = 'legal' AND status = 'pending'")->fetch_assoc()['cnt'];
  $legalApproved = $conn->query("SELECT COUNT(*) AS cnt FROM document WHERE docu_type = 'legal' AND status = 'approved'")->fetch_assoc()['cnt'];
  $legalRejected = $conn->query("SELECT COUNT(*) AS cnt FROM document WHERE docu_type = 'legal' AND status = 'rejected'")->fetch_assoc()['cnt'];

  // Legal documents list
  $legalDocs = $conn->query("SELECT * FROM document WHERE docu_type = 'legal' AND status != 'trash' ORDER BY uploaded_at DESC");

  // --- Fetch Legal Requests for Cards (with modal fields and case info if exists) ---
$legalRequestCards = [];
$reqSql = "SELECT lr.*, u.fullname AS requested_by, u.department AS department
           FROM legal_requests lr
           LEFT JOIN users u ON lr.user_id = u.user_id
           ORDER BY lr.created_at DESC";
$reqRes = $conn->query($reqSql);
while ($req = $reqRes->fetch_assoc()) {
    // Get attachments
    $docs = [];
    $docRes = $conn->query("SELECT d.file_name, d.uploaded_at FROM legal_request_documents lrd
                            LEFT JOIN document d ON lrd.document_id = d.document_id
                            WHERE lrd.request_id = {$req['request_id']}");
    while ($d = $docRes->fetch_assoc()) {
        $docs[] = [
            "name" => $d['file_name'],
            "date" => $d['uploaded_at']
        ];
    }
    // Get notes/comments
    $notes = [];
    $noteRes = $conn->query("SELECT n.note, n.created_at, u.fullname, u.role 
                         FROM legal_request_notes n
                         LEFT JOIN users u ON n.user_id = u.user_id
                         WHERE n.request_id = {$req['request_id']}
                         ORDER BY n.created_at ASC");
    while ($n = $noteRes->fetch_assoc()) {
        $notes[] = [
            "author" => $n['fullname'],
            "role" => $n['role'],
            "comment" => $n['note'],
            "date" => date("F d, Y \\a\\t h:i A", strtotime($n['created_at']))
        ];
    }
    // Check if this request is now a legal case
    $case = null;
    $caseRes = $conn->query("SELECT c.*, u.fullname AS assigned_to_name
                             FROM cases c
                             LEFT JOIN users u ON c.assigned_to = u.user_id
                             WHERE c.name = '{$conn->real_escape_string($req['title'])}' LIMIT 1");
    if ($caseRes && $caseRes->num_rows > 0) {
        $case = $caseRes->fetch_assoc();
        // Get case documents
        $caseDocs = [];
        $caseDocRes = $conn->query("SELECT * FROM case_documents WHERE case_id = {$case['case_id']}");
        while ($cd = $caseDocRes->fetch_assoc()) {
            $caseDocs[] = $cd;
        }
        // Get case notes
        $caseNotes = [];
        $caseNoteRes = $conn->query("SELECT cn.*, u.fullname 
                             FROM case_notes cn 
                             LEFT JOIN users u ON cn.user_id = u.user_id 
                             WHERE cn.case_id = {$case['case_id']} 
                             ORDER BY cn.created_at ASC");
        while ($cn = $caseNoteRes->fetch_assoc()) {
            $caseNotes[] = [
                "author" => $cn['fullname'],
                "comment" => $cn['note'],
                "date" => date("F d, Y \\a\\t h:i A", strtotime($cn['created_at']))
            ];
        }
        $req['case'] = [
            "case_id" => $case['case_id'],
            "name" => $case['name'],
            "client" => $case['client'],
            "status" => $case['status'],
            "assigned_to" => $case['assigned_to_name'],
            "start_date" => $case['start_date'],
            "case_docs" => $caseDocs,
            "case_notes" => $caseNotes
        ];
    }
    $legalRequestCards[] = [
        "request_id" => $req['request_id'],
        "title" => $req['title'],
        "description" => $req['description'],
        "purpose" => $req['purpose'],
        "request_type" => $req['request_type'] === 'Others' ? ($req['other_request_type'] ?? 'Others') : ucfirst($req['request_type']),
        "priority" => $req['priority'],
        "stakeholders" => $req['stakeholders'],
        "status" => $req['status'],
        "requested_by" => $req['requested_by'],
        "department" => $req['department'],
        "submitted_on" => date("M d, Y", strtotime($req['created_at'])),
        "deadline" => $req['deadline'] ? date("M d, Y", strtotime($req['deadline'])) : '',
        "assigned_to" => $req['assigned_to'] ?? '',
        "employee_id" => $req['employee_id'],
        "complexity_level" => $req['complexity_level'],
        "urgency" => $req['urgency'],
        "attachments" => $docs,
        "comments" => $notes,
        "case" => $req['case'] ?? null
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Administrative</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
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

          <!-- Document Management -->
          <div class="mb-4">
            <h6 class="text-uppercase px-2 mb-2">Document & Legal</h6>
            <nav class="nav flex-column">
              <a class="nav-link active" href="#"><ion-icon name="folder-outline"></ion-icon>Documents</a>
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
    <main class="main-content" style="margin-left:220px; width:calc(100% - 220px); min-height:100vh;">
      <div class="container-fluid py-4 px-4">
        <div class="mb-4">
          <input type="text" class="form-control form-control-lg" placeholder="Search..." style="max-width:500px;" id="searchInput">
        </div>
        <div class="mb-4 d-flex justify-content-between align-items-center">
          <div class="mb-4">
            <div style="font-family:'Montserrat',sans-serif;font-size:2rem;font-weight:700;color:#22223b;">Document Management</div>
            <div style="color:#6c757d;font-size:1.08rem;">Organize and manage your documents</div>
          </div>
          <div class="col-md-5 d-flex align-items-center justify-content-end gap-2">
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#uploadModal">
              <i class="bi bi-file-earmark-text"></i> New Request/Document
            </button>
            <a href="export_legal_report.php" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Export Report</a>
            <a href="legal_analytics.php" class="btn btn-primary"><i class="bi bi-bar-chart"></i> Analytics</a>
          </div>
        </div>
        <!-- Legal Document Monitoring Section -->
        <div class="row mb-4">
          <div class="col-md-3">
            <div class="stats-card bg-white rounded-3 shadow-sm p-3 text-center">
              <div class="icon mb-2"><i class="bi bi-file-earmark-text" style="color:#4311a5;font-size:2rem;"></i></div>
              <div class="label mb-1">Total Legal Documents</div>
              <div class="value" style="font-size:1.6rem;font-weight:700;"><?= $legalTotal ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stats-card bg-white rounded-3 shadow-sm p-3 text-center">
              <div class="icon mb-2"><i class="bi bi-hourglass-split" style="color:#f59e42;font-size:2rem;"></i></div>
              <div class="label mb-1">Pending</div>
              <div class="value text-warning" style="font-size:1.6rem;font-weight:700;"><?= $legalPending ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stats-card bg-white rounded-3 shadow-sm p-3 text-center">
              <div class="icon mb-2"><i class="bi bi-check-circle" style="color:#22c55e;font-size:2rem;"></i></div>
              <div class="label mb-1">Approved</div>
              <div class="value text-success" style="font-size:1.6rem;font-weight:700;"><?= $legalApproved ?></div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stats-card bg-white rounded-3 shadow-sm p-3 text-center">
              <div class="icon mb-2"><i class="bi bi-x-circle" style="color:#ef4444;font-size:2rem;"></i></div>
              <div class="label mb-1">Legal Requestss</div>
              <div class="mt-0" style="font-size:0.98rem;">
                <span class="text-success">Completed: <?= $legalApproved ?></span> &nbsp;|&nbsp;
                <span class="text-danger">Rejected: <?= $legalRejected ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
          <form class="row g-2 align-items-center" method="get" id="searchFilterForm">
            <div class="col-md-4">
              <input type="text" class="form-control" name="search" placeholder="Search by title, ID, or requester..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-4">
              <select class="form-select" name="department">
                <option value="">All Departments</option>
                <!-- Populate with department options if available -->
              </select>
            </div>
            <div class="col-md-4">
              <select class="form-select" name="type">
                <option value="">All Types</option>
                <option value="contract">Contract</option>
                <option value="compliance">Compliance</option>
                <option value="litigation">Litigation</option>
              </select>
            </div>
            <div class="col-md-7 d-flex gap-2">
              <input type="date" class="form-control" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
              <span class="mx-1">to</span>
              <input type="date" class="form-control" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
              <button type="submit" class="btn btn-outline-secondary ms-2">Filter</button>
              <a href="?" class="btn btn-link">Clear Dates</a>
            </div>
          </form>
        </div>

        <!-- Archived Requests List -->
        <div class="bg-white rounded-3 shadow-sm p-4 mb-5">
          <h5 class="mb-3 fw-bold">Archived Requests (<?= $legalTotal ?>)</h5>
          <?php
            // Example filter logic (adjust as needed)
            $search = $_GET['search'] ?? '';
            $type = $_GET['type'] ?? '';
            $department = $_GET['department'] ?? '';
            $date_from = $_GET['date_from'] ?? '';
            $date_to = $_GET['date_to'] ?? '';
            $filteredDocs = [];
            $legalDocs->data_seek(0); // Reset pointer
            while($row = $legalDocs->fetch_assoc()) {
              $match = true;
              if ($search && stripos($row['title'] . $row['file_name'] . $row['stakeholders'], $search) === false) $match = false;
              if ($type && $row['request_type'] != $type) $match = false;
              if ($department && stripos($row['stakeholders'], $department) === false) $match = false;
              if ($date_from && strtotime($row['uploaded_at']) < strtotime($date_from)) $match = false;
              if ($date_to && strtotime($row['uploaded_at']) > strtotime($date_to)) $match = false;
              if ($match) $filteredDocs[] = $row;
            }
            if (count($filteredDocs) === 0): ?>
              <div class="text-muted text-center py-4">No archived requests found.</div>
            <?php else:
              foreach ($filteredDocs as $row):
                // Prepare attachment count and tags
                $attachments = !empty($row['file_name']) ? [ ["name"=>$row['file_name'], "date"=>$row['uploaded_at']] ] : [];
                $tagBadges = '';
                // Fix: Use isset() to avoid undefined array key warnings
                if (isset($row['request_type']) && !empty($row['request_type'])) {
                  $tagBadges .= '<span class="badge bg-light text-dark me-2"><i class="bi bi-tag"></i> ' . htmlspecialchars($row['request_type']) . '</span>';
                }
                if (isset($row['tag']) && !empty($row['tag'])) {
                  foreach (explode(',', $row['tag']) as $tag) {
                    $tagBadges .= '<span class="badge bg-light text-dark me-2"><i class="bi bi-tag"></i> ' . htmlspecialchars(trim($tag)) . '</span>';
                  }
                }
                $attachmentCount = count($attachments);
          ?>
            <div class="legal-request-card border rounded-4 p-4 mb-3 shadow-sm"
              style="cursor:pointer;transition:box-shadow .2s;"
              data-title="<?= htmlspecialchars($row['title'] ?? $row['file_name']) ?>"
              data-requestid="<?= htmlspecialchars($row['request_id'] ?? '') ?>"
              data-status="<?= htmlspecialchars($row['status'] ?? '') ?>"
              data-description="<?= htmlspecialchars($row['legal_description'] ?? $row['description'] ?? '-') ?>"
              data-purpose="<?= htmlspecialchars($row['purpose'] ?? '') ?>"
              data-requesttype="<?= htmlspecialchars($row['request_type'] ?? '') ?>"
              data-urgency="<?= htmlspecialchars($row['urgency'] ?? '') ?>"
              data-routedto="<?= htmlspecialchars($row['routed_to'] ?? '') ?>"
              data-routingreason="<?= htmlspecialchars($row['routing_reason'] ?? '') ?>"
              data-attachments='<?= json_encode($attachments) ?>'
              data-requestedby="<?= htmlspecialchars($row['uploaded_by'] ?? '') ?>"
              data-department="<?= htmlspecialchars($row['department'] ?? '') ?>"
              data-submittedon="<?= !empty($row['uploaded_at']) ? date("M d, Y", strtotime($row['uploaded_at'])) : '' ?>"
              data-duedate="<?= htmlspecialchars($row['deadline'] ?? '') ?>"
              data-assignedto="<?= htmlspecialchars($row['assigned_to'] ?? '') ?>"
              data-comments='<?= json_encode($notes) ?>'
              data-case='<?= isset($row['case']) ? htmlspecialchars(json_encode($row['case']), ENT_QUOTES, 'UTF-8') : '' ?>'
              onclick="openRequestModal(this)"
            >
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <div class="fw-bold fs-5 text-dark mb-1"><?= htmlspecialchars($row['title'] ?? $row['file_name']) ?></div>
                  <div class="text-muted mb-2"><?= htmlspecialchars($row['legal_description'] ?? $row['description'] ?? '-') ?></div>
                  <div class="d-flex align-items-center flex-wrap gap-3 mb-2">
                    <?php if (isset($row['deadline']) && $row['deadline']): ?>
                      <span class="text-success"><i class="bi bi-clock"></i> Due: <?= htmlspecialchars($row['deadline']) ?></span>
                    <?php endif; ?>
                    <?= $tagBadges ?>
                    <span class="text-muted"><i class="bi bi-paperclip"></i> <?= $attachmentCount ?> attachment(s)</span>
                  </div>
                </div>
                <div>
                  <?php if ($row['status'] == 'Completed' || $row['status'] == 'approved'): ?>
                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size:1rem;font-weight:600;">Completed</span>
                  <?php elseif ($row['status'] == 'Rejected' || $row['status'] == 'rejected'): ?>
                    <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size:1rem;font-weight:600;">Rejected</span>
                  <?php elseif ($row['status'] == 'Pending' || $row['status'] == 'pending'): ?>
                    <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size:1rem;font-weight:600;">Pending</span>
                  <?php else: ?>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:1rem;font-weight:600;"><?= htmlspecialchars($row['status']) ?></span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                <div class="text-muted">
                  From: <strong><?= htmlspecialchars($row['uploaded_by'] ?? 'Sarah Johnson') ?></strong>
                  <?php if (!empty($row['department'])): ?>
                    (<?= htmlspecialchars($row['department']) ?>)
                  <?php endif; ?>
                </div>
                <div class="text-muted small">
                  Submitted: <?= date("M d, Y", strtotime($row['uploaded_at'])); ?>
                </div>
              </div>
            </div>
          <?php endforeach; endif; ?>
        </div>
        
        <div class="row g-4">
          <!-- Folders Sidebar -->
          <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-3 mb-3">
              <div style="font-weight:600;font-size:1.15rem;margin-bottom:1rem;">Folders</div>
              <ul class="list-group" id="folderList">
                <li class="list-group-item folder-item active" data-folder="all">
                  <i class="bi bi-folder2-open me-2"></i> All Folders
                  <span class="badge bg-light text-dark ms-auto"><?php echo $totalCount; ?></span>
                </li>
                <?php foreach ($folders as $folder): ?>
                  <li class="list-group-item folder-item" data-folder="<?php echo htmlspecialchars($folder); ?>">
                    <i class="bi bi-folder me-2"></i> <?php echo htmlspecialchars(ucwords($folderNames[$folder])); ?>
                    <span class="badge bg-light text-dark ms-auto"><?php echo $folderCounts[$folder]; ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
              <!-- Tag/Category Filter -->
              <div class="mt-4">
                <div style="font-weight:600;font-size:1.08rem;margin-bottom:0.5rem;">Categories/Tags</div>
                <form method="get" id="tagFilterForm">
                  <select class="form-select" name="tag" id="tagFilterSelect" onchange="document.getElementById('tagFilterForm').submit();">
                    <option value="">All Categories</option>
                    <?php foreach ($tags as $tag): ?>
                      <option value="<?= htmlspecialchars($tag) ?>" <?= $tagFilter == $tag ? 'selected' : '' ?>><?= htmlspecialchars($tag) ?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </div>
            </div>
          </div>
          <!-- Documents Content -->
          <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <button class="btn btn-light me-2 active" id="tabGrid">Grid</button>
                <button class="btn btn-light me-2" id="tabList">List</button>
              </div> 
              <div class="d-flex align-items-center gap-2">
                <input type="text" class="form-control" placeholder="Search documents" style="max-width:220px;" id="tableSearchInput">
                <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i></button>
              </div>
            </div>
            <div class="bg-white rounded-3 shadow-sm p-3">
              <div id="gridView" class="row g-3">
                <?php
                $result = $conn->query("SELECT * FROM document WHERE status != 'trash' $tagWhere ORDER BY uploaded_at DESC");
                while ($row = $result->fetch_assoc()):
                  $filePath = "/Administrative/Admin/documentManagement/action/uploads/" . $row['file_name'];
                  $size = file_exists($filePath) ? round(filesize($filePath) / 1024 / 1024, 2) . " MB" : "N/A";
                ?>
                <div class="col-md-4 doc-card" data-folder="<?php echo htmlspecialchars($row['folder_id']); ?>" data-status="<?php echo htmlspecialchars($row['status'] ?? 'active'); ?>" data-tags="<?php echo htmlspecialchars($row['tag']); ?>">
                  <div class="card h-100">
                    <div class="card-body">
                      <div class="d-flex align-items-center mb-2">
                        <span style="background:#e0e7ff;border-radius:8px;padding:6px 10px;margin-right:8px;">
                          <i class="bi bi-file-earmark-text" style="color:#6366f1;font-size:1.2rem;"></i>
                        </span>
                        <div>
                          <strong class="text-dark"><?php echo htmlspecialchars($row['file_name']); ?></strong>
                          <div style="font-size:0.95rem;color:#6c757d;"><?php echo htmlspecialchars($row['folder_id']); ?></div>
                        </div>
                      </div>
                      <div class="mb-2"><small>Size:</small> <?php echo $size; ?></div>
                      <div class="mb-2"><small>Modified:</small> <?php echo date("M d, Y", strtotime($row['uploaded_at'])); ?></div>
                      <div class="mb-2"><small>By:</small> <?php echo htmlspecialchars($row['description']); ?></div>
                      <?php if (!empty($row['tag'])): ?>
                        <div class="mb-2">
                          <?php foreach (explode(',', $row['tag']) as $tag): ?>
                            <span class="badge bg-info text-dark"><?= htmlspecialchars(trim($tag)) ?></span>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2">
                      <a href="uploads/<?php echo urlencode($row['file_name']); ?>" class="btn btn-sm btn-outline-secondary" download title="Download"><i class="bi bi-download"></i></a>
                      <form method="POST" action="action/archive.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['document_id']; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Archive"><i class="bi bi-archive"></i></button>
                      </form>
                      <form method="POST" action="action/trash.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['document_id']; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Trash"><i class="bi bi-trash"></i></button>
                      </form>
                    </div>
                  </div>
                </div>
                <?php endwhile; ?>
              </div>
              <div id="listView" style="display:none;">
                <div class="table-responsive">
                  <table class="table align-middle mb-0">
                    <thead>
                      <tr>
                        <th>Document</th>
                        <th>Size</th>
                        <th>Modified</th>
                        <th>By</th>
                        <th>Tags</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="documentTable">
                      <?php
                      $result = $conn->query("SELECT * FROM document WHERE status != 'trash' $tagWhere ORDER BY uploaded_at DESC");
                      while ($row = $result->fetch_assoc()):
                        $filePath = "uploads/" . $row['file_name'];
                        $size = file_exists($filePath) ? round(filesize($filePath) / 1024 / 1024, 2) . " MB" : "N/A";
                      ?>
                        <tr data-folder="<?php echo htmlspecialchars($row['folder_id']); ?>" data-status="<?php echo htmlspecialchars($row['status'] ?? 'active'); ?>" data-tags="<?php echo htmlspecialchars($row['tag']); ?>">
                          <td>
                            <span class="d-inline-flex align-items-center">
                              <span style="background:#e0e7ff;border-radius:8px;padding:6px 10px;margin-right:8px;">
                                <i class="bi bi-file-earmark-text" style="color:#6366f1;font-size:1.2rem;"></i>
                              </span>
                              <div>
                                <strong class="text-dark"><?php echo htmlspecialchars($row['file_name']); ?></strong>
                                <div style="font-size:0.95rem;color:#6c757d;"><?php echo htmlspecialchars($row['folder_id']); ?></div>
                              </div>
                            </span>
                          </td>
                          <td><?php echo $size; ?></td>
                          <td><?php echo date("M d, Y", strtotime($row['uploaded_at'])); ?></td>
                          <td><?php echo htmlspecialchars($row['description']); ?></td>
                          <td>
                            <?php if (!empty($row['tag'])): ?>
                              <?php foreach (explode(',', $row['tag']) as $tag): ?>
                                <span class="badge bg-info text-dark"><?= htmlspecialchars(trim($tag)) ?></span>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          </td>
                          <td>
                            <a href="uploads/<?php echo urlencode($row['file_name']); ?>" class="btn btn-sm btn-outline-secondary" download title="Download"><i class="bi bi-download"></i></a>
                            <form method="POST" action="action/archive.php" style="display:inline;">
                              <input type="hidden" name="id" value="<?php echo $row['document_id']; ?>">
                              <button type="submit" class="btn btn-sm btn-outline-warning" title="Archive"><i class="bi bi-archive"></i></button>
                            </form>
                            <form method="POST" action="action/trash.php" style="display:inline;">
                              <input type="hidden" name="id" value="<?php echo $row['document_id']; ?>">
                              <button type="submit" class="btn btn-sm btn-outline-danger" title="Trash"><i class="bi bi-trash"></i></button>
                            </form>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- New Request/Document Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModal" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="action/upload.php" method="POST" enctype="multipart/form-data" id="uploadDocumentForm">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadModalLabel">Upload New Document / Legal Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="docu_type" class="form-label">Document Type</label>
          <select class="form-select" name="docu_type" id="docu_type" required>
            <option value="normal">Normal Document</option>
            <option value="legal">Legal Request</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="title" class="form-label">Document Title</label>
          <input type="text" class="form-control" name="title" id="title" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <input type="text" class="form-control" name="description" id="description">
        </div>
        <div class="mb-3">
          <label for="folder" class="form-label">Folder</label>
          <select class="form-select" name="folder" id="folder">
            <?php foreach ($folders as $folder): ?>
              <option value="<?php echo htmlspecialchars($folder); ?>"><?php echo htmlspecialchars(ucwords($folderNames[$folder])); ?></option>
            <?php endforeach; ?>
          </select>
          <input type="text" class="form-control mt-2" name="new_folder" placeholder="Or create new folder">
        </div>
        <div class="mb-3">
          <label for="document" class="form-label">File</label>
          <input type="file" class="form-control" name="document" id="document" required>
        </div>

        <!-- Legal Request Fields (hidden by default, shown if docu_type is 'legal') -->
        <div id="legalFields" style="display:none;">
          <hr>
          <h6 class="mb-3 mt-2 fw-bold text-primary">Legal Request Details</h6>
          <div class="mb-3">
            <label for="employee_id" class="form-label">Employee ID</label>
            <input type="text" class="form-control" name="employee_id" id="employee_id">
          </div>
          <div class="mb-3">
            <label for="request_type_select" class="form-label">Request Type</label>
            <select class="form-select" name="request_type" id="request_type_select">
              <option value="">Select request type</option>
              <option value="Contract Review">Contract Review</option>
              <option value="Documentation Validation">Documentation Validation</option>
              <option value="Legal Opinion">Legal Opinion</option>
              <option value="Template Request">Template Request</option>
              <option value="Signature Coordination">Signature Coordination</option>
              <option value="Policy Drafting">Policy Drafting</option>
              <option value="Compliance Check">Compliance Check</option>
              <option value="Risk Assessment">Risk Assessment</option>
              <option value="Others">Others</option>
            </select>
          </div>
          <div class="mb-3" id="otherRequestTypeDesc" style="display:none;">
            <label for="other_request_type_desc" class="form-label">Please describe your request</label>
            <input type="text" class="form-control" name="other_request_type" id="other_request_type_desc">
          </div>
          <div class="mb-3">
            <label for="urgency" class="form-label">Urgency</label>
            <select class="form-select" name="urgency" id="urgency">
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="complexity" class="form-label">Complexity Level</label>
            <select class="form-select" name="complexity" id="complexity">
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="stakeholders" class="form-label">Stakeholders</label>
            <input type="text" class="form-control" name="stakeholders" id="stakeholders">
          </div>
          <div class="mb-3">
            <label for="purpose" class="form-label">Purpose</label>
            <input type="text" class="form-control" name="purpose" id="purpose">
          </div>
          <div class="mb-3">
            <label for="due_date" class="form-label">Due Date (if applicable)</label>
            <input type="date" class="form-control" name="deadline" id="due_date">
          </div>
          <div class="alert alert-info mt-4 mb-0" style="font-size:0.98rem;">
            <strong>Note:</strong> Your request will be screened by a Legal Admin who will route it to either a Legal Admin (for routine matters) or a Legal Officer (for complex issues requiring legal analysis).
          </div>
        </div>

        <div class="mb-3">
          <label for="tag" class="form-label">Tags/Categories (comma separated)</label>
          <input type="text" class="form-control" name="tag" id="tag" placeholder="e.g. contract, finance, hr">
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Upload</button>
      </div>
    </form>
  </div>
</div>

<!-- Legal Request Modal -->
<div class="modal fade" id="legalRequestModal" tabindex="-1" aria-labelledby="legalRequestModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg">
      <div class="modal-header bg-gradient" style="background: linear-gradient(90deg,#9A66ff 0%,#4311a5 100%); color:#fff;">
        <h4 class="modal-title fw-bold" id="legalRequestModalLabel">
          <span id="modalRequestTitle"></span>
          <span id="modalStatusBadge" class="ms-3"></span>
        </h4>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-4">
          <!-- Left: Legal Request Details -->
          <div class="col-lg-5">
            <div class="mb-3">
              <div class="fw-semibold text-secondary mb-1">Request ID</div>
              <div id="modalRequestId" class="fs-6 fw-bold"></div>
            </div>
            <div class="mb-3">
              <div class="fw-semibold text-secondary mb-1">Type</div>
              <div id="modalRequestType" class="badge bg-light text-dark fs-6"></div>
            </div>
            <div class="mb-3">
              <div class="fw-semibold text-secondary mb-1">Purpose</div>
              <div id="modalPurpose"></div>
            </div>
            <div class="mb-3">
              <div class="fw-semibold text-secondary mb-1">Description</div>
              <div id="modalDescription"></div>
            </div>
            <div class="row mb-3">
              <div class="col-6">
                <div class="fw-semibold text-secondary mb-1">Urgency</div>
                <div id="modalUrgency" class="badge bg-warning text-dark"></div>
              </div>
              <div class="col-6">
                <div class="fw-semibold text-secondary mb-1">Complexity</div>
                <div id="modalComplexity" class="badge bg-info text-dark"></div>
              </div>
            </div>
            <div class="mb-3">
              <div class="fw-semibold text-secondary mb-1">Department</div>
              <div id="modalDepartment"></div>
            </div>
            <div class="mb-3">
              <div class="fw-semibold text-secondary mb-1">Requested By</div>
              <div id="modalRequestedBy"></div>
            </div>
            <div class="row mb-3">
              <div class="col-6">
                <div class="fw-semibold text-secondary mb-1">Submitted On</div>
                <div id="modalSubmittedOn"></div>
              </div>
              <div class="col-6">
                <div class="fw-semibold text-secondary mb-1">Due Date</div>
                <div id="modalDueDate"></div>
              </div>
            </div>
            <div class="mb-3">
              <div class="fw-semibold text-secondary mb-1">Attachments <span class="badge bg-light text-dark" id="modalAttachmentCount"></span></div>
              <div id="modalAttachments"></div>
            </div>
            <div class="mb-3">
              <div class="fw-semibold text-secondary mb-1">Comments</div>
              <div id="modalComments"></div>
            </div>
          </div>
          <!-- Right: Case Details if exists -->
          <div class="col-lg-7" id="caseDetailSection" style="display:none;">
            <div class="border rounded-3 p-3 mb-3 bg-light">
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
                  </tbody>
                </table>
              </div>
              <h6 class="fw-bold mt-4 mb-2">Case Notes</h6>
              <div class="case-notes mb-3" id="caseNotes"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
      // Grid view
      document.querySelectorAll('.doc-card').forEach(function(card) {
        if (folder === 'all' || card.getAttribute('data-folder') === folder) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
      // List view
      document.querySelectorAll('#documentTable tr').forEach(function(row) {
        if (folder === 'all' || row.getAttribute('data-folder') === folder) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });

  // Grid/List toggle
  document.getElementById('tabGrid').addEventListener('click', function() {
    document.getElementById('gridView').style.display = '';
    document.getElementById('listView').style.display = 'none';
    this.classList.add('active');
    document.getElementById('tabList').classList.remove('active');
  });
  document.getElementById('tabList').addEventListener('click', function() {
    document.getElementById('gridView').style.display = 'none';
    document.getElementById('listView').style.display = '';
    this.classList.add('active');
    document.getElementById('tabGrid').classList.remove('active');
  });

  // Search functionality (works for both grid and list)
  function searchDocs(val) {
    val = val.toLowerCase();
    document.querySelectorAll('.doc-card, #documentTable tr').forEach(function(row) {
      row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
  }
  document.getElementById('searchInput').addEventListener('input', function() {
    searchDocs(this.value);
  });
  document.getElementById('tableSearchInput').addEventListener('input', function() {
    searchDocs(this.value);
  });

  // This will make your "New Request/Document" button open the modal
  document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#uploadModal"]').forEach(function(btn) {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    var modal = new bootstrap.Modal(document.getElementById('uploadModal'));
    modal.show();
  });
});

  // Show/hide legal request fields based on document type
  document.getElementById('docu_type').addEventListener('change', function() {
    document.getElementById('legalFields').style.display = this.value === 'legal' ? '' : 'none';
  });

  // Show/hide "Other" description field for request type
  document.getElementById('request_type_select').addEventListener('change', function() {
    document.getElementById('otherRequestTypeDesc').style.display = this.value === 'Others' ? '' : 'none';
  });

  // Global modal open function
  function openRequestModal(card) {
  // Legal Request Details
  document.getElementById('modalRequestTitle').textContent = card.getAttribute('data-title');
  document.getElementById('modalRequestId').textContent = card.getAttribute('data-requestid');
  document.getElementById('modalRequestType').textContent = card.getAttribute('data-requesttype');
  document.getElementById('modalPurpose').textContent = card.getAttribute('data-purpose');
  document.getElementById('modalDescription').textContent = card.getAttribute('data-description');
  document.getElementById('modalUrgency').textContent = card.getAttribute('data-urgency');
  document.getElementById('modalComplexity').textContent = card.getAttribute('data-complexity_level') || '';
  document.getElementById('modalDepartment').textContent = card.getAttribute('data-department');
  document.getElementById('modalRequestedBy').textContent = card.getAttribute('data-requestedby');
  document.getElementById('modalSubmittedOn').textContent = card.getAttribute('data-submittedon');
  document.getElementById('modalDueDate').textContent = card.getAttribute('data-duedate');
  // Status badge
  let status = card.getAttribute('data-status');  
  let badge = '';
  if (status === 'Completed' || status === 'approved') badge = '<span class="badge bg-success">Completed</span>';
  else if (status === 'Rejected' || status === 'rejected') badge = '<span class="badge bg-danger">Rejected</span>';
  else badge = '<span class="badge bg-warning text-dark">Pending</span>';
  document.getElementById('modalStatusBadge').innerHTML = badge;

  // Attachments
  let attachments = JSON.parse(card.getAttribute('data-attachments') || '[]');
  document.getElementById('modalAttachmentCount').textContent = attachments.length;
  let attHtml = '';
  attachments.forEach(function(att) {
    attHtml += `<div class="d-flex align-items-center mb-2">
      <span class="me-2"><i class="bi bi-file-earmark"></i></span>
      <span>${att.name}</span>
      <span class="text-muted ms-2 small">Uploaded ${att.date}</span>
      <a href="uploads/${encodeURIComponent(att.name)}" class="btn btn-link btn-sm ms-auto" target="_blank">View</a>
    </div>`;
  });
  document.getElementById('modalAttachments').innerHTML = attHtml || '<span class="text-muted">No attachments.</span>';

  // Comments: Use case_notes if case exists, else use legal_request_notes
  let caseData = card.getAttribute('data-case');
  let commentsHtml = '';
  if (caseData && caseData !== 'null' && caseData !== '') {
    let caseObj = JSON.parse(caseData);
    let notes = caseObj.case_notes || [];
    notes.forEach(function(c) {
      commentsHtml += `<div class="mb-2">
        <strong>${c.author}</strong><br>
        <span>${c.comment}</span>
        <div class="text-muted small">${c.date}</div>
      </div>`;
    });
  } else {
    let comments = JSON.parse(card.getAttribute('data-comments') || '[]');
    comments.forEach(function(c) {
      commentsHtml += `<div class="mb-2">
        <strong>${c.author}</strong> <span class="text-muted small">(${c.role})</span><br>
        <span>${c.comment}</span>
        <div class="text-muted small">${c.date}</div>
      </div>`;
    });
  }
  document.getElementById('modalComments').innerHTML = commentsHtml || '<span class="text-muted">No comments yet.</span>';

  // CASE DETAILS
  caseData = card.getAttribute('data-case'); // Reuse variable, not redeclare
  if (caseData && caseData !== 'null' && caseData !== '') {
    let caseObj = JSON.parse(caseData);
    document.getElementById('caseDetailSection').style.display = '';
    document.getElementById('caseDetailHeader').innerHTML = `
      <h5 class="fw-bold mb-2 text-primary">Legal Case: ${caseObj.name}</h5>
      <div class="mb-2"><strong>Client:</strong> ${caseObj.client}</div>
      <div class="mb-2"><strong>Status:</strong> <span class="badge bg-info">${caseObj.status}</span></div>
      <div class="mb-2"><strong>Assigned To:</strong> ${caseObj.assigned_to || '-'}</div>
    `;
    document.getElementById('caseStartDate').textContent = caseObj.start_date ? new Date(caseObj.start_date).toLocaleDateString() : '-';
    document.getElementById('caseAssignedTo').textContent = caseObj.assigned_to || '-';
    document.getElementById('caseDocCount').textContent = caseObj.case_docs.length;
    // Render case documents
    let docRows = '';
    caseObj.case_docs.forEach(function(doc) {
      docRows += `<tr>
        <td>${doc.title}</td>
        <td>${doc.type}</td>
        <td>${doc.version || '-'}</td>
        <td><span class="badge bg-secondary">${doc.status || '-'}</span></td>
        <td><a href="${doc.file_path}" target="_blank" class="btn btn-sm btn-outline-primary">View</a></td>
      </tr>`;
    });
    document.getElementById('caseDocTable').innerHTML = docRows || '<tr><td colspan="5" class="text-muted">No documents.</td></tr>';
    // Render case notes
    let noteHtml = '';
    caseObj.case_notes.forEach(function(note) {
      noteHtml += `<div class="mb-2">
        <strong>${note.author}</strong><br>
        <span>${note.comment}</span>
        <div class="text-muted small">${note.date}</div>
      </div>`;
    });
    document.getElementById('caseNotes').innerHTML = noteHtml || '<span class="text-muted">No case notes yet.</span>';
  } else {
    document.getElementById('caseDetailSection').style.display = 'none';
    document.getElementById('caseDetailHeader').innerHTML = '';
    document.getElementById('caseStartDate').textContent = '';
    document.getElementById('caseAssignedTo').textContent = '';
    document.getElementById('caseDocCount').textContent = '';
    document.getElementById('caseDocTable').innerHTML = '';
    document.getElementById('caseNotes').innerHTML = '';
  }

  var modal = new bootstrap.Modal(document.getElementById('legalRequestModal'));
  modal.show();
}
</script>
</body>
</html>
