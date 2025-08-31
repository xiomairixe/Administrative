<?php
// filepath: c:\xampp\htdocs\Administrative\visitor-management\account.php
include '../../connection.php';

// For demo, let's assume user_id=2 is logged in (Lance Ramos). Replace with session logic as needed.
$user_id = 2;

// Fetch user info from database
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Split fullname for first/last name (simple split, adjust as needed)
$fullname = $user['fullname'];
$names = explode(' ', $fullname, 2);
$first_name = $names[0] ?? '';
$last_name = $names[1] ?? '';

// Handle profile update
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['page']) && $_POST['page'] === 'profile') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']);
    $new_fullname = $first_name . ' ' . $last_name;
    $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, department=? WHERE user_id=?");
    $stmt->bind_param("sssi", $new_fullname, $email, $department, $user_id);
    $stmt->execute();
    $stmt->close();
    $user['fullname'] = $new_fullname;
    $user['email'] = $email;
    $user['department'] = $department;
    $success = true;
}

// Determine which page to show
$page = $_GET['page'] ?? 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account - Visitor Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <style>
    .account-nav a.active { background:#f4ebff;color:#8b5cf6;font-weight:600; }
    .account-nav a { color:#22223b;text-decoration:none; }
    .account-nav a:hover { background:#f4ebff;color:#8b5cf6; }
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
    <a href="../security.php"><i class="bi bi-shield-lock"></i> Security</a>
    <hr>
    <a href="account.php" class="active"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <div class="main-content">
    <div class="row g-4">
      <!-- Left Profile Card & Navigation -->
      <div class="col-lg-4">
        <div class="bg-white rounded-3 shadow-sm p-4 h-100 d-flex flex-column align-items-center">
          <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['fullname']) ?>&size=128" alt="Profile" class="mb-3"
            style="width:90px;height:90px;border-radius:50%;object-fit:cover;">
          <div style="font-family:'Montserrat',sans-serif;font-size:1.25rem;font-weight:700;">
            <?= htmlspecialchars($user['fullname']) ?>
          </div>
          <div style="color:#6c757d;font-size:1.05rem;margin-bottom:1.5rem;">
            <?= htmlspecialchars($user['role']) ?>
          </div>
          <div class="w-100 account-nav">
            <a href="#" class="d-flex align-items-center gap-2 mb-2 px-3 py-2 rounded-2 <?= $page=='profile'?'active':'' ?>" onclick="showPage('profile');return false;">
              <i class="bi bi-person"></i> Profile
            </a>
            <a href="#" class="d-flex align-items-center gap-2 mb-2 px-3 py-2 rounded-2 <?= $page=='password'?'active':'' ?>" onclick="showPage('password');return false;">
              <i class="bi bi-key"></i> Password
            </a>
            <a href="#" class="d-flex align-items-center gap-2 mb-2 px-3 py-2 rounded-2 <?= $page=='notifications'?'active':'' ?>" onclick="showPage('notifications');return false;">
              <i class="bi bi-bell"></i> Notifications
            </a>
            <a href="#" class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 <?= $page=='security'?'active':'' ?>" onclick="showPage('security');return false;">
              <i class="bi bi-shield-lock"></i> Security
            </a>
          </div>
        </div>
      </div>
      <!-- Dynamic Content Area -->
      <div class="col-lg-8">
        <div class="bg-white rounded-3 shadow-sm p-4 h-100" id="accountContent">
          <?php if ($page=='profile'): ?>
            <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">Profile Information</div>
            <?php if ($success): ?>
              <div class="alert alert-success">Profile updated successfully!</div>
            <?php endif; ?>
            <form method="POST" id="profileForm">
              <input type="hidden" name="page" value="profile">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label" style="font-weight:600;">First Name</label>
                  <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($first_name) ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label" style="font-weight:600;">Last Name</label>
                  <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($last_name) ?>">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label" style="font-weight:600;">Email</label>
                  <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                <div class="col-md-6">
                  <label class="form-label" style="font-weight:600;">Department</label>
                  <input type="text" class="form-control" name="department" value="<?= htmlspecialchars($user['department']) ?>">
                </div>
              </div>
              <div class="mb-4">
                <label class="form-label" style="font-weight:600;">Bio</label>
                <textarea class="form-control" name="bio" rows="2"></textarea>
              </div>
              <div class="text-end">
                <button type="submit" class="btn"
                  style="background:#8b5cf6;color:#fff;font-weight:600;padding:0.6rem 2.2rem;font-size:1.08rem;">Save Changes</button>
              </div>
            </form>
          <?php elseif ($page=='password'): ?>
            <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">Change Password</div>
            <form method="POST" id="passwordForm" autocomplete="off">
              <input type="hidden" name="page" value="password">
              <div class="mb-3">
                <label class="form-label" style="font-weight:600;">Current Password</label>
                <input type="password" class="form-control" name="current_password" required>
              </div>
              <div class="mb-3">
                <label class="form-label" style="font-weight:600;">New Password</label>
                <input type="password" class="form-control" name="new_password" required>
              </div>
              <div class="mb-3">
                <label class="form-label" style="font-weight:600;">Confirm New Password</label>
                <input type="password" class="form-control" name="confirm_password" required>
              </div>
              <div class="text-end">
                <button type="submit" class="btn"
                  style="background:#8b5cf6;color:#fff;font-weight:600;padding:0.6rem 2.2rem;font-size:1.08rem;">Update Password</button>
              </div>
            </form>
          <?php elseif ($page=='notifications'): ?>
            <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">Notifications</div>
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
          <?php elseif ($page=='security'): ?>
            <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">Security</div>
            <div class="mb-3">
              <label class="form-label" style="font-weight:600;">Two-Factor Authentication</label>
              <div>
                <span class="badge bg-success">Enabled</span>
                <button class="btn btn-outline-secondary btn-sm ms-2" disabled>Manage</button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label" style="font-weight:600;">Recent Login Activity</label>
              <ul class="list-group">
                <li class="list-group-item">Aug 28, 2025 10:12 AM - Chrome (Windows)</li>
                <li class="list-group-item">Aug 27, 2025 8:45 PM - Mobile App</li>
                <li class="list-group-item">Aug 27, 2025 3:20 PM - Chrome (Windows)</li>
              </ul>
            </div>
            <div class="alert alert-info">Security features are for demo only.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <script>
    function showPage(page) {
      window.location.href = "account.php?page=" + page;
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>