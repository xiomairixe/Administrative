<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ViaHale Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/html5-qrcode"></script>
  <link rel="stylesheet" href="style.css">
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
     <!-- Check In/Out -->
    <div class="dashboard-row">
      <div class="dashboard-col">
        <div class="p-3 bg-light rounded-3">
          <h6 class="mb-3" style="font-weight:700;">Check In</h6>
          <form id="checkInForm">
            <div class="mb-2 input-group">
              <input type="text" class="form-control" id="checkInQRCode" placeholder="Scan QR Code or enter code">
              <button type="button" class="btn btn-gradient" id="scanQrBtn"><i class="bi bi-qr-code-scan"></i></button>
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
            <div class="mb-2 input-group">
              <input type="text" class="form-control" id="checkOutQRCode" placeholder="Scan QR Code or enter code">
              <button type="button" class="btn btn-gradient" id="scanQrOutBtn"><i class="bi bi-qr-code-scan"></i></button>
            </div>
            <button type="submit" class="btn btn-success w-100" style="background:#22c55e;border:none;">Check Out</button>
          </form>
          <div id="checkOutResult" class="mt-2 text-success" style="display:none;"></div>
        </div>
      </div>
    </div>

    <!-- Recent Visitors -->
    <div class="mt-4">
      <h6>Recent Visitors</h6>
      <div class="recent-visitors" id="recentVisitors">No active visitors</div>
    </div>
  </div>

  <!-- QR Scanner Modal -->
  <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius: 10px;">
        <div class="modal-header">
          <h5 class="modal-title" id="qrScannerLabel">Scan QR Code</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeScannerBtn"></button>
        </div>
        <div class="modal-body text-center">
          <div id="qrReader" style="width:100%;max-width:400px;margin:auto;"></div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let html5QrScanner = null;
    let activeQrInput = null;

    function verifyCode(code) {
      return code.trim() !== '';
    }

    function handleFormSubmit(formId, rfidId, qrId, resultId, type) {
      document.getElementById(formId).addEventListener('submit', function(e) {
        e.preventDefault();
        const rfid = document.getElementById(rfidId).value;
        const qr = document.getElementById(qrId).value;
        const msg = document.getElementById(resultId);
        let result = false;

        if (rfid) result = verifyCode(rfid);
        else if (qr) result = verifyCode(qr);

        if (result) {
          msg.textContent = `${type} successful!`;
          msg.classList.remove('text-danger');
          msg.classList.add('text-success');
          msg.style.display = "block";

          if (type === "Check-in") {
            document.getElementById('activeVisitors').textContent++;
            document.getElementById('totalVisitors').textContent++;
            document.getElementById('recentVisitors').textContent = "Visitor checked in";
          } else {
            let active = parseInt(document.getElementById('activeVisitors').textContent);
            if (active > 0) document.getElementById('activeVisitors').textContent = active - 1;
            document.getElementById('completedVisits').textContent++;
            document.getElementById('recentVisitors').textContent = "Visitor checked out";
          }
        } else {
          msg.textContent = "Invalid RFID or QR Code.";
          msg.classList.remove('text-success');
          msg.classList.add('text-danger');
          msg.style.display = "block";
        }

        setTimeout(() => { msg.style.display = "none"; }, 2500);
        this.reset();
      });
    }

    handleFormSubmit('checkInForm', 'checkInRFID', 'checkInQRCode', 'checkInResult', 'Check-in');
    handleFormSubmit('checkOutForm', 'checkOutRFID', 'checkOutQRCode', 'checkOutResult', 'Check-out');

    function openQrScanner(targetInputId) {
      activeQrInput = document.getElementById(targetInputId);
      const qrModal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
      qrModal.show();
      setTimeout(() => { startQrCamera(); }, 400);
    }

    function startQrCamera() {
      html5QrScanner = new Html5Qrcode("qrReader");
      html5QrScanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        qrCodeMessage => {
          if (activeQrInput) activeQrInput.value = qrCodeMessage;
          stopQrCamera();
          bootstrap.Modal.getInstance(document.getElementById('qrScannerModal')).hide();
        },
        () => {}
      ).catch(err => {
        alert("Camera access error: " + err);
        stopQrCamera();
      });
    }

    function stopQrCamera() {
      if (html5QrScanner) {
        html5QrScanner.stop().then(() => {
          html5QrScanner.clear();
        }).catch(err => console.error("Stop failed", err));
      }
    }

    document.getElementById('closeScannerBtn').addEventListener('click', stopQrCamera);

    document.getElementById('scanQrBtn').addEventListener('click', () => openQrScanner('checkInQRCode'));
    document.getElementById('scanQrOutBtn').addEventListener('click', () => openQrScanner('checkOutQRCode'));
  </script>
</body>
</html>