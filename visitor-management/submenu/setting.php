<?php
// filepath: c:\xampp\htdocs\Administrative\visitor-management\setting.php
include '../../connection.php';

// Handle form submission for General tab
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tab']) && $_POST['tab'] === 'general') {
    $org_name = trim($_POST['org_name']);
    $timezone = trim($_POST['timezone']);
    $date_format = trim($_POST['date_format']);
    $allow_weekend = isset($_POST['allow_weekend']) ? 1 : 0;
    $require_approval = isset($_POST['require_approval']) ? 1 : 0;
    $stmt = $conn->prepare("UPDATE settings SET org_name=?, timezone=?, date_format=?, allow_weekend=?, require_approval=? WHERE id=1");
    $stmt->bind_param("sssii", $org_name, $timezone, $date_format, $allow_weekend, $require_approval);
    $stmt->execute();
    $stmt->close();
    $success = true;
}

// Load settings from database
$res = $conn->query("SELECT * FROM settings WHERE id=1");
$settings = $res->fetch_assoc();

// Determine which tab to show
$tab = $_GET['tab'] ?? 'general';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Settings - Visitor Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../style.css" rel="stylesheet">
  <style>
    .list-group-item.active {
      background: #f4ebff;
      color: #8b5cf6;
      font-weight: 600;
      border: none;
    }

    .list-group-item {
      border: none;
    }

    .settings-nav a {
      cursor: pointer;
    }
  </style>
</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="../index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="../visitLog.php"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <a href="../visitor.php"><i class="bi bi-person-lines-fill"></i> Visitors</a>
    <a href="../blacklisted.php"><i class="bi bi-slash-circle"></i> Blacklist</a>
    <a href="../security.php"><i class="bi bi-slash-circle"></i> Security</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="#" class="active"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>  
  <div class="main-content">
    <div class="row g-4">
      <!-- Left Settings Nav -->
      <div class="col-lg-3">
        <div class="bg-white rounded-3 shadow-sm p-0">
          <div class="list-group list-group-flush settings-nav">
            <a href="#" class="list-group-item list-group-item-action <?= $tab == 'general' ? 'active' : '' ?>"
              onclick="showTab('general');return false;">General</a>
            <a href="#" class="list-group-item list-group-item-action <?= $tab == 'notifications' ? 'active' : '' ?>"
              onclick="showTab('notifications');return false;">Notifications</a>
            <a href="#" class="list-group-item list-group-item-action <?= $tab == 'appearance' ? 'active' : '' ?>"
              onclick="showTab('appearance');return false;">Appearance</a>
            <a href="#" class="list-group-item list-group-item-action <?= $tab == 'booking' ? 'active' : '' ?>"
              onclick="showTab('booking');return false;">Booking Rules</a>
            <a href="#" class="list-group-item list-group-item-action <?= $tab == 'users' ? 'active' : '' ?>"
              onclick="showTab('users');return false;">User Management</a>
            <a href="#" class="list-group-item list-group-item-action <?= $tab == 'integrations' ? 'active' : '' ?>"
              onclick="showTab('integrations');return false;">Integrations</a>
          </div>
        </div>
      </div>
      <!-- Settings Content -->
      <div class="col-lg-9">
        <div class="bg-white rounded-3 shadow-sm p-4 h-100" id="settingsContent">
          <?php if ($tab == 'general'): ?>
          <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">
            General Settings
          </div>
          <?php if ($success): ?>
          <div class="alert alert-success">Settings saved successfully!</div>
          <?php endif; ?>
          <div class="mb-4">
            <div style="font-weight:600;margin-bottom:0.5rem;">System Information</div>
            <div class="p-3 rounded-3" style="background:#faf6ff;">
              <div class="row">
                <div class="col-md-4 mb-2"><span style="color:#6c757d;">System Name</span><br><span
                    style="font-weight:600;">ViaHale Administrative</span></div>
                <div class="col-md-4 mb-2"><span style="color:#6c757d;">Version</span><br><span
                    style="font-weight:600;">1.1.1</span></div>
                <div class="col-md-4 mb-2"><span style="color:#6c757d;">Last Updated</span><br><span
                    style="font-weight:600;">April 1, 2023</span></div>
              </div>
            </div>
          </div>
          <form method="POST">
            <input type="hidden" name="tab" value="general">
            <div style="font-weight:600;margin-bottom:0.5rem;">Organization Settings</div>
            <div class="mb-3">
              <label class="form-label" style="font-weight:600;">Organization Name</label>
              <input type="text" class="form-control" name="org_name"
                value="<?= htmlspecialchars($settings['org_name'] ?? 'Acme Corporation') ?>">
            </div>
            <div class="mb-3">
              <label class="form-label" style="font-weight:600;">Timezone</label>
              <select class="form-select" name="timezone">
                <option value="Pacific" <?= ($settings['timezone'] ?? '') == 'Pacific' ? 'selected' : '' ?>>
                  (UTC-8:00) Pacific Time (US & Canada)
                </option>
                <option value="Eastern" <?= ($settings['timezone'] ?? '') == 'Eastern' ? 'selected' : '' ?>>
                  (UTC-5:00) Eastern Time (US & Canada)
                </option>
                <option value="London" <?= ($settings['timezone'] ?? '') == 'London' ? 'selected' : '' ?>>
                  (UTC+0:00) London
                </option>
                <option value="Beijing" <?= ($settings['timezone'] ?? '') == 'Beijing' ? 'selected' : '' ?>>
                  (UTC+8:00) Beijing
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label" style="font-weight:600;">Date Format</label>
              <select class="form-select" name="date_format">
                <option value="MM/DD/YYYY" <?= ($settings['date_format'] ?? '') == 'MM/DD/YYYY' ? 'selected' : '' ?>>
                  MM/DD/YYYY
                </option>
                <option value="DD/MM/YYYY" <?= ($settings['date_format'] ?? '') == 'DD/MM/YYYY' ? 'selected' : '' ?>>
                  DD/MM/YYYY
                </option>
                <option value="YYYY-MM-DD" <?= ($settings['date_format'] ?? '') == 'YYYY-MM-DD' ? 'selected' : '' ?>>
                  YYYY-MM-DD
                </option>
              </select>
            </div>
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="allow_weekend" id="allowWeekend"
                <?= !empty($settings['allow_weekend']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="allowWeekend" style="color:#8b5cf6;font-weight:500;">
                Allow weekend bookings
              </label>
            </div>
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" name="require_approval" id="requireApproval"
                <?= !empty($settings['require_approval']) ? 'checked' : '' ?>>
              <label class="form-check-label" for="requireApproval" style="color:#8b5cf6;font-weight:500;">
                Require approval for bookings
              </label>
            </div>
            <div class="text-end">
              <button type="submit" class="btn"
                style="background:#8b5cf6;color:#fff;font-weight:600;border-radius:8px;padding:0.7rem 1.2rem;">
                Save Changes
              </button>
            </div>
          </form>
          <?php elseif ($tab == 'notifications'): ?>
          <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">
            Notifications Settings
          </div>
          <div class="mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="notifEmail" checked>
              <label class="form-check-label" for="notifEmail">Email Notifications</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="notifSMS">
              <label class="form-check-label" for="notifSMS">SMS Notifications</label>
            </div>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="notifApp" checked>
              <label class="form-check-label" for="notifApp">App Push Notifications</label>
            </div>
          </div>
          <div class="alert alert-info">Notification settings are for demo only.</div>
          <?php elseif ($tab == 'appearance'): ?>
          <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">
            Appearance Settings
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;">Theme</label>
            <select class="form-select" name="theme">
              <option value="light" selected>Light</option>
              <option value="dark">Dark</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;">Accent Color</label>
            <input type="color" class="form-control form-control-color" value="#8b5cf6" title="Choose your color">
          </div>
          <div class="alert alert-info">Appearance settings are for demo only.</div>
          <?php elseif ($tab == 'booking'): ?>
          <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">
            Booking Rules
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;">Max Bookings Per Day</label>
            <input type="number" class="form-control" value="5">
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;">Advance Booking Limit (days)</label>
            <input type="number" class="form-control" value="30">
          </div>
          <div class="alert alert-info">Booking rules are for demo only.</div>
          <?php elseif ($tab == 'users'): ?>
          <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">
            User Management
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;">Add New User</label>
            <input type="text" class="form-control mb-2" placeholder="Full Name">
            <input type="email" class="form-control mb-2" placeholder="Email">
            <button class="btn btn-outline-primary">Add User</button>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;">Existing Users</label>
            <ul class="list-group">
              <li class="list-group-item">Lance Ramos <span class="badge bg-success">Admin</span></li>
              <li class="list-group-item">Glen Honrado <span class="badge bg-info">Staff</span></li>
              <li class="list-group-item">J.Amongo <span class="badge bg-secondary">Security</span></li>
            </ul>
          </div>
          <div class="alert alert-info">User management is for demo only.</div>
          <?php elseif ($tab == 'integrations'): ?>
          <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">
            Integrations
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;">API Key</label>
            <input type="text" class="form-control" value="API-1234567890" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label" style="font-weight:600;">Webhook URL</label>
            <input type="text" class="form-control" value="https://yourdomain.com/webhook" readonly>
          </div>
          <div class="alert alert-info">Integration settings are for demo only.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <script>
    function showTab(tab) {
      window.location.href = "setting.php?tab=" + tab;
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>