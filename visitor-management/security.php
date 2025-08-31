<?php
  include ('../connection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visitor Management</title>
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
    <a href="#"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="visitLog.php"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <a href="visitor.php"><i class="bi bi-person-lines-fill"></i> Visitors</a>
    <a href="blacklisted.php"><i class="bi bi-slash-circle"></i> Blacklist</a>
    <a href="#" class="active"><i class="bi bi-slash-circle"></i> Security</a>
    <hr>
    <a href="submenu/account.php"><i class="bi bi-person"></i> Account</a>
    <a href="submenu/setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="submenu/help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <div class="main-content px-2 px-md-4 py-3" style="background:#f7f8fa;">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div>
        <h2 class="fw-bold mb-1" style="font-family:Montserrat;">Security Monitoring</h2>
      </div>
      <button class="btn btn-outline-secondary" style="font-weight:500;">
        <i class="bi bi-bell-slash"></i> Mute Alerts
      </button>
    </div>

    <!-- Facial Recognition System -->
    <div class="card mb-4" style="border-radius:16px;">
      <div class="card-body">
        <div class="row g-4">
          <div class="col-lg-8">
            <div class="mb-2 fw-semibold">Facial Recognition System</div>
            <div class="bg-dark d-flex align-items-center justify-content-center" style="height:320px; border-radius:12px; position:relative;">
              <div class="text-center w-100">
                <i class="bi bi-play-circle" style="font-size:3.5rem; color:#fff;"></i>
                <div class="mt-2 text-white fs-5">Click Start to activate facial recognition</div>
              </div>
              <div style="position:absolute;bottom:10px;left:20px;color:#bbb;font-size:0.98rem;">
                <span class="dot" style="height:10px;width:10px;background:#bbb;border-radius:50%;display:inline-block;margin-right:6px;"></span>
                System Inactive
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="mb-2 fw-semibold">Recognition Results</div>
            <div class="bg-light rounded-3 p-3 mb-3" style="min-height:110px;">
              <span class="text-muted">No recognition results yet. The system will display visitor information when a registered face is detected.</span>
            </div>
            <div class="fw-semibold mb-1">System Log</div>
            <div class="bg-light rounded-3 p-2" style="font-size:0.98rem;">
              <span class="text-muted">[20:09:54] System initialized</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Camera Feeds -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="bg-white rounded-3 shadow-sm p-2" style="position:relative;">
          <div class="fw-semibold mb-1">Main Entrance</div>
          <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=600&q=80" alt="Main Entrance" class="w-100 rounded-3" style="height:160px;object-fit:cover;">
          <div style="position:absolute;top:10px;left:10px;color:#fff;font-size:0.98rem;background:rgba(0,0,0,0.45);padding:2px 8px;border-radius:6px;">20:10:07</div>
          <div style="position:absolute;top:10px;right:14px;">
            <span class="dot" style="height:10px;width:10px;background:#22c55e;border-radius:50%;display:inline-block;margin-right:4px;"></span>
            <span class="text-success fw-semibold" style="font-size:0.97rem;">Active</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bg-white rounded-3 shadow-sm p-2" style="position:relative;">
          <div class="fw-semibold mb-1">Reception Area</div>
          <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=600&q=80" alt="Reception Area" class="w-100 rounded-3" style="height:160px;object-fit:cover;">
          <div style="position:absolute;top:10px;left:10px;color:#fff;font-size:0.98rem;background:rgba(0,0,0,0.45);padding:2px 8px;border-radius:6px;">20:10:09</div>
          <div style="position:absolute;top:10px;right:14px;">
            <span class="dot" style="height:10px;width:10px;background:#22c55e;border-radius:50%;display:inline-block;margin-right:4px;"></span>
            <span class="text-success fw-semibold" style="font-size:0.97rem;">Active</span>
          </div>
          <!-- Example visitor overlay -->
          <div style="position:absolute;bottom:12px;left:12px;background:rgba(255,255,255,0.92);border-radius:8px;padding:6px 14px;display:flex;align-items:center;box-shadow:0 2px 8px rgba(140,140,200,0.07);">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Maria Garcia" style="width:32px;height:32px;border-radius:50%;margin-right:8px;">
            <div>
              <div class="fw-semibold" style="font-size:0.98rem;">Maria Garcia</div>
              <div class="text-muted" style="font-size:0.93rem;">Job Interview</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bg-white rounded-3 shadow-sm p-2" style="position:relative;">
          <div class="fw-semibold mb-1">Conference Room Hallway</div>
          <img src="https://images.unsplash.com/photo-1465101178521-c1a9136a3c8b?auto=format&fit=crop&w=600&q=80" alt="Conference Room Hallway" class="w-100 rounded-3" style="height:160px;object-fit:cover;">
          <div style="position:absolute;top:10px;left:10px;color:#fff;font-size:0.98rem;background:rgba(0,0,0,0.45);padding:2px 8px;border-radius:6px;">20:09:57</div>
          <div style="position:absolute;top:10px;right:14px;">
            <span class="dot" style="height:10px;width:10px;background:#22c55e;border-radius:50%;display:inline-block;margin-right:4px;"></span>
            <span class="text-success fw-semibold" style="font-size:0.97rem;">Active</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Security Alerts -->
    <div class="card mb-4" style="border-radius:16px;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <div class="fw-semibold fs-5">Security Alerts <span class="badge bg-danger ms-2">2 Active</span></div>
          <a href="#" class="text-decoration-none text-muted" style="font-size:0.98rem;">Clear Resolved</a>
        </div>
        <div class="alert alert-danger d-flex align-items-center mb-2" style="border-radius:10px;">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div>
            <strong>Unauthorized Access Attempt</strong>
            <div class="small text-muted">20:09:54</div>
          </div>
        </div>
        <div class="alert alert-danger d-flex align-items-center mb-2" style="border-radius:10px;">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div>
            <strong>Door Forced Open</strong>
            <div class="small text-muted">20:10:02</div>
          </div>
        </div>
      </div>
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

    function handleCheckInOut(formId, inputId, resultId, type) {
      document.getElementById(formId).addEventListener('submit', function(e) {
        e.preventDefault();
        const code = document.getElementById(inputId).value.trim();
        const msg = document.getElementById(resultId);

        if (!code) {
          msg.textContent = "Please enter or scan a code.";
          msg.classList.add('text-danger');
          msg.style.display = "block";
          setTimeout(() => { msg.style.display = "none"; }, 2000);
          return;
        }

        fetch('action/check_' + (type === 'Check-in' ? 'in' : 'out') + '.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'code=' + encodeURIComponent(code)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            msg.textContent = type + " successful!";
            msg.classList.remove('text-danger');
            msg.classList.add('text-success');
            msg.style.display = "block";
            setTimeout(() => { location.reload(); }, 1200);
          } else {
            msg.textContent = data.message || "Invalid code.";
            msg.classList.remove('text-success');
            msg.classList.add('text-danger');
            msg.style.display = "block";
            setTimeout(() => { msg.style.display = "none"; }, 2500);
          }
        });
        this.reset();
      });
    }

    handleCheckInOut('checkInForm', 'checkInQRCode', 'checkInResult', 'Check-in');
    handleCheckInOut('checkOutForm', 'checkOutQRCode', 'checkOutResult', 'Check-out');
  </script>
</body>
</html>