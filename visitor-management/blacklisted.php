<?php
// filepath: c:\xampp\htdocs\Administrative\visitor-management\blacklisted.php
include('../connection.php');

// Fetch blacklisted visitors
$blacklisted = $conn->query("SELECT * FROM visitors WHERE is_blacklisted = 1 ORDER BY full_name ASC");

// Handle blacklist/unblacklist actions
if (isset($_POST['action']) && isset($_POST['visitor_id'])) {
    $visitor_id = intval($_POST['visitor_id']);
    if ($_POST['action'] === 'unblacklist') {
        $conn->query("UPDATE visitors SET is_blacklisted = 0 WHERE visitor_id = $visitor_id");
    }
    if ($_POST['action'] === 'blacklist') {
        $conn->query("UPDATE visitors SET is_blacklisted = 1 WHERE visitor_id = $visitor_id");
    }
    header("Location: blacklisted.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visitor Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="visitLog.php"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <a href="visitor.php"><i class="bi bi-person-lines-fill"></i> Visitors</a>
    <a href="#" class="active"><i class="bi bi-slash-circle"></i> Blacklist</a>
    <a href="security.php"><i class="bi bi-slash-circle"></i> Security</a>
    <hr>
    <a href="submenu/account.php"><i class="bi bi-person"></i> Account</a>
    <a href="submenu/setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="submenu/help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <div class="main-content">
    <div class="topbar mb-4">
      <div class="dashboard-title">Blacklisted Visitors</div>
      <div class="breadcrumbs">Home / Blacklist</div>
    </div>
    <div class="container-fluid px-0">
      <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Visitor Name</th>
              <th scope="col">Email</th>
              <th scope="col">Contact</th>
              <th scope="col">Reason</th>
              <th scope="col">Date Blacklisted</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $blacklisted->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['full_name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
              <td><?php echo htmlspecialchars($row['blacklist_reason'] ?? ''); ?></td>
              <td><?php echo isset($row['blacklisted_at']) ? htmlspecialchars($row['blacklisted_at']) : 'N/A'; ?></td>
              <td>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="visitor_id" value="<?php echo $row['visitor_id']; ?>">
                  <input type="hidden" name="action" value="unblacklist">
                  <button type="submit" class="btn btn-success btn-sm">Remove</button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <!-- Blacklist a visitor manually -->
      <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
        <h5>Blacklist a Visitor</h5>
        <form method="post" class="row g-2">
          <div class="col-md-4">
            <select name="visitor_id" class="form-select" required>
              <option value="">Select Visitor</option>
              <?php
              $visitors = $conn->query("SELECT visitor_id, full_name FROM visitors WHERE is_blacklisted = 0 ORDER BY full_name ASC");
              while ($v = $visitors->fetch_assoc()) {
                echo '<option value="' . $v['visitor_id'] . '">' . htmlspecialchars($v['full_name']) . ' (ID: ' . $v['visitor_id'] . ')</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-5">
            <input type="text" name="reason" class="form-control" placeholder="Reason for blacklist" required>
          </div>
          <div class="col-md-3">
            <input type="hidden" name="action" value="blacklist">
            <button type="submit" class="btn btn-danger w-100">Blacklist</button>
          </div>
        </form>
        <?php
        // Handle manual blacklist with reason
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'blacklist' && isset($_POST['visitor_id'], $_POST['reason'])) {
            $visitor_id = intval($_POST['visitor_id']);
            $reason = $conn->real_escape_string($_POST['reason']);
            $conn->query("UPDATE visitors SET is_blacklisted = 1, blacklist_reason = '$reason', blacklisted_at = NOW() WHERE visitor_id = $visitor_id");
            echo "<script>window.location.href='blacklisted.php';</script>";
        }
        ?>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>