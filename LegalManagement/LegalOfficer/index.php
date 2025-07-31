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
    <a href="facilities.php"><i class="bi bi-building"></i> Facilities</a>
    <a href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="#" class="active"><i class="bi bi-bell"></i> Notifications</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

  <div class="content">
    <!-- Top Bar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold">Dashboard</h3>
      <div class="d-flex align-items-center">
        <span class="me-3">🔔</span>
        <div>
          <strong>John Doe</strong><br>
          <small class="text-muted">Legal Officer</small>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="container-fluid">
      <div class="row g-4">
        <!-- Cards rendered as-is -->
      </div>
    </div>

    <!-- Tasks and Events -->
    <div class="row g-4 mb-4">
      <div class="col-md-6">
        <div class="card card-task p-3">
          <h5 class="fw-bold mb-3">High Priority Tasks</h5>
          <?php foreach ($highPriorityTasks as $task): ?>
          <div class="border-start border-3 border-danger ps-3 mb-3">
            <div class="fw-semibold"><?= $task['title'] ?></div>
            <small class="text-muted">Due: <?= $task['due'] ?> | Case: <?= $task['case'] ?></small>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-events p-3">
          <h5 class="fw-bold mb-3">Upcoming Events</h5>
          <p class="text-muted">No upcoming events</p>
        </div>
      </div>
    </div>

    <!-- Recent Cases Table -->
    <div class="card p-4">
      <h5 class="fw-bold mb-3">Recent Cases</h5>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr>
              <th>Case</th>
              <th>Client</th>
              <th>Type</th>
              <th>Status</th>
              <th>Last Updated</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Tech Corp Merger</td>
              <td>Tech Corp</td>
              <td>M&A</td>
              <td><span class="badge bg-success">Ongoing</span></td>
              <td>2023-08-28</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    document.getElementById("sidebarToggle").addEventListener("click", function () {
      document.getElementById("sidebarNav").classList.toggle("show");
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
