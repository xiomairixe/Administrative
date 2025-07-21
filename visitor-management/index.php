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
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
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
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
      font-size: 1.6rem;
      color: #fff;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sidebar .logo i {
      font-size: 2rem;
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

    .topbar .search-bar {
      background: #f4f6fa;
      border-radius: 1.5rem;
      padding: 0.3rem 1.2rem;
      border: none;
      width: 260px;
      font-size: 1rem;
      color: #6c757d;
      outline: none;
    }
    .topbar .search-bar {
        width: 100%;
        margin-top: 0.5rem;
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
      color: #9a66ff;
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
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
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
      color: #9a66ff;
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
</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="#" class="active"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="facilities.php"><i class="bi bi-person-plus"></i> Facilities</a>
    <a href="visitLog.php"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <div class="main-content">
    <div class="topbar mb-4">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle2" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </button>
        <nav class="nav">
          <a class="nav-link active" href="#">Home</a>
          <a class="nav-link" href="#">Contact</a>
        </nav>
      </div>
      <form class="d-none d-md-block">
        <input type="text" class="search-bar" placeholder="Search here">
      </form>
      <div class="profile">
        <div style="position:relative;">
          <i class="bi bi-bell"></i>
          <span class="badge">2</span>
        </div>
        <img src="# " class="profile-img" alt="profile">
        <div class="profile-info">
          <strong>R.Lance</strong><br>
          <small>Admin</small>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div class="dashboard-title">Dashboard</div>
      <div class="breadcrumbs">Home &nbsp;/&nbsp; Dashboard</div>
    </div>
    <div class="stats-cards">
      <div class="stats-card">
        <div class="icon"><i class="bi bi-person-walking"></i></div>
        <div class="label">Total Visitors</div>
        <div class="value" id="totalVisitors">0</div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-people"></i></div>
        <div class="label">Active Visitors</div>
        <div class="value" id="activeVisitors">0</div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-check2-circle"></i></div>
        <div class="label">Completed</div>
        <div class="value" id="completedVisits">0</div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-geo-alt"></i></div>
        <div class="label">Pending Visits</div>
        <div class="value" id="pendingVisits">0</div>
      </div>
    </div>
    <div>
        <div class="dashboard-row">
            <div class="dashboard-col">
            <div class="p-3 bg-light rounded-3">
              <h6 class="mb-3" style="font-weight:700;">Check In</h6>
              <form id="checkInForm">
                <div class="mb-2">
                  <input type="text" class="form-control" id="checkInRFID" placeholder="Scan RFID or enter code" autocomplete="off">
                </div>
                <div class="mb-2 text-center">
                  <span style="font-size:0.93rem;color:#888;">or</span>
                </div>
                <div class="mb-2">
                  <input type="text" class="form-control" id="checkInQRCode" placeholder="Scan QR Code or enter code" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary w-100" style="background:#8b5cf6;border:none;">Check In</button>
              </form>
              <div id="checkInResult" class="mt-2 text-success" style="display:none;"></div>
            </div>
            </div>
            <div class="dashboard-col">
            <div class="p-3 bg-light rounded-3">
              <h6 class="mb-3" style="font-weight:700;">Check Out</h6>
              <form id="checkOutForm">
                <div class="mb-2">
                  <input type="text" class="form-control" id="checkOutRFID" placeholder="Scan RFID or enter code" autocomplete="off">
                </div>
                <div class="mb-2 text-center">
                  <span style="font-size:0.93rem;color:#888;">or</span>
                </div>
                <div class="mb-2">
                  <input type="text" class="form-control" id="checkOutQRCode" placeholder="Scan QR Code or enter code" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-success w-100" style="background:#22c55e;border:none;">Check Out</button>
              </form>
              <div id="checkOutResult" class="mt-2 text-success" style="display:none;"></div>
            </div>
            </div>
        </div>
    </div>
    <!-- Check In/Out Section -->
    <div class="dashboard-row">
      <div class="dashboard-col" style="max-width: 700px;">
        <div style="font-family:'Montserrat',sans-serif;font-size:1.13rem;font-weight:600;margin-bottom:1.1rem;">Visitor Location</div>
        <img src="https://i.imgur.com/6QKQ7Qp.png" alt="Visitor Location" class="visitor-location-img mb-3" style="background:#fff;">
        <!-- You can replace the above src with a real map or chart -->
        
      </div>
      <div class="dashboard-col">
        <div style="font-family:'Montserrat',sans-serif;font-size:1.13rem;font-weight:600;margin-bottom:1.1rem;">Recent Visitors</div>
        <div class="recent-visitors" id="recentVisitors">No active visitors</div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarToggle2 = document.getElementById('sidebarToggle2');
    const sidebarNav = document.getElementById('sidebarNav');
    sidebarToggle?.addEventListener('click', function () {
      sidebarNav.classList.toggle('show');
    });
    sidebarToggle2?.addEventListener('click', function () {
      sidebarNav.classList.toggle('show');
    });
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 900 && sidebarNav.classList.contains('show')) {
        if (!sidebarNav.contains(e.target) && !sidebarToggle.contains(e.target)) {
          sidebarNav.classList.remove('show');
        }
      }
    });

    // --- Check In/Out Logic (Demo) ---
    function verifyRFID(code) {
      // Simulate RFID verification (replace with real backend call)
      return code.trim() !== '';
    }
    function verifyQRCode(code) {
      // Simulate QR code verification (replace with real backend call)
      return code.trim() !== '';
    }

    document.getElementById('checkInForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const rfid = document.getElementById('checkInRFID').value;
      const qr = document.getElementById('checkInQRCode').value;
      let result = false;
      if (rfid) result = verifyRFID(rfid);
      else if (qr) result = verifyQRCode(qr);

      const msg = document.getElementById('checkInResult');
      if (result) {
        msg.textContent = "Check-in successful!";
        msg.style.display = "block";
        // Update stats (demo)
        document.getElementById('activeVisitors').textContent = parseInt(document.getElementById('activeVisitors').textContent) + 1;
        document.getElementById('totalVisitors').textContent = parseInt(document.getElementById('totalVisitors').textContent) + 1;
        document.getElementById('recentVisitors').textContent = "Visitor checked in";
      } else {
        msg.textContent = "Invalid RFID or QR Code.";
        msg.style.display = "block";
        msg.classList.remove('text-success');
        msg.classList.add('text-danger');
      }
      setTimeout(() => { msg.style.display = "none"; }, 2500);
      this.reset();
    });

    document.getElementById('checkOutForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const rfid = document.getElementById('checkOutRFID').value;
      const qr = document.getElementById('checkOutQRCode').value;
      let result = false;
      if (rfid) result = verifyRFID(rfid);
      else if (qr) result = verifyQRCode(qr);

      const msg = document.getElementById('checkOutResult');
      if (result) {
        msg.textContent = "Check-out successful!";
        msg.style.display = "block";
        // Update stats (demo)
        let active = parseInt(document.getElementById('activeVisitors').textContent);
        if (active > 0) document.getElementById('activeVisitors').textContent = active - 1;
        document.getElementById('completedVisits').textContent = parseInt(document.getElementById('completedVisits').textContent) + 1;
        document.getElementById('recentVisitors').textContent = "Visitor checked out";
      } else {
        msg.textContent = "Invalid RFID or QR Code.";
        msg.style.display = "block";
        msg.classList.remove('text-success');
        msg.classList.add('text-danger');
      }
      setTimeout(() => { msg.style.display = "none"; }, 2500);
      this.reset();
    });
  </script>
</body>
</html>