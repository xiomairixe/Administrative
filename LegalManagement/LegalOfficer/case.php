<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Legal Officer - Case Page</title>
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

      .sidebar-toggle {
        display: block;
      }
    }

    table th, table td {
      vertical-align: middle;
      text-align: center;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold">Legal Cases</h3>
      <div class="d-flex align-items-center">
        <span class="me-3">🔔</span>
        <div>
          <strong>John Doe</strong><br>
          <small class="text-muted">Legal Officer</small>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
      <div class="row g-2 align-items-center mb-3">
        <div class="col-md-9">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Search by case, client, type...">
          </div>
        </div>
        <div class="col-md-3">
          <button class="btn btn-primary w-100">Export CSV</button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Case ID</th>
              <th>Department</th>
              <th>Type</th>
              <th>Status</th>
              <th>Start Date</th>
              <th>Last Updated</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Sample data array (Replace with your DB logic)
            $cases = [
              ['case_id' => 'C001', 'department' => 'Legal', 'type' => 'Civil', 'status' => 'Open', 'start_date' => '2023-06-01', 'last_updated' => '2023-07-20'],
              ['case_id' => 'C002', 'department' => 'Corporate', 'type' => 'M&A', 'status' => 'Closed', 'start_date' => '2023-04-10', 'last_updated' => '2023-06-15']
            ];

            foreach ($cases as $row) {
              echo "<tr>
                <td>{$row['case_id']}</td>
                <td>{$row['department']}</td>
                <td>{$row['type']}</td>
                <td>{$row['status']}</td>
                <td>{$row['start_date']}</td>
                <td>{$row['last_updated']}</td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
        
        <div class="tab-content">
            <div class="tab-pane fade show active" id="caseDetails">
              <h5>Case Details</h5>
              <p>Details about the selected case will be displayed here.</p>
        </div>
            <div class="tab-pane fade" id="caseHistory">
              <h5>Case History</h5>
              <p>History of actions taken on the case will be displayed here.</p>
            </div>
          </div>

        
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
