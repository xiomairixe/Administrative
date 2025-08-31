<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Department Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f7f8fa;
      font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
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

    .dashboard-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.2rem;
      margin-top: 1.5rem;
    }

    .dashboard-desc {
      color: #6c757d;
      margin-bottom: 2rem;
      font-size: 1.08rem;
    }

    .card-stat {
      border-radius: 14px;
      box-shadow: 0 2px 8px rgba(140, 140, 200, 0.07);
      padding: 1.2rem 1.5rem;
      background: #fff;
      display: flex;
      align-items: center;
      gap: 1rem;
      min-width: 220px;
    }

    .card-stat .icon {
      font-size: 2.2rem;
      margin-right: 0.7rem;
    }

    .card-stat .stat-label {
      font-size: 1.08rem;
      font-weight: 500;
      color: #6c757d;
    }

    .card-stat .stat-value {
      font-size: 1.5rem;
      font-weight: 700;
      margin-top: 0.2rem;
    }

    .card-stat.pending {
      border-left: 6px solid #facc15;
    }

    .card-stat.approved {
      border-left: 6px solid #10b981;
    }

    .card-stat.rejected {
      border-left: 6px solid #ef4444;
    }

    .recent-card,
    .quick-card {
      border-radius: 14px;
      box-shadow: 0 2px 8px rgba(140, 140, 200, 0.07);
      background: #fff;
      padding: 1.5rem 1.5rem 1.2rem 1.5rem;
      margin-bottom: 1.5rem;
    }

    .recent-table th,
    .recent-table td {
      vertical-align: middle;
      font-size: 1.05rem;
    }

    .status-badge {
      font-size: 0.98rem;
      font-weight: 500;
      border-radius: 8px;
      padding: 0.3em 0.9em;
    }

    .status-approved {
      background: #e6f9f2;
      color: #10b981;
    }

    .status-rejected {
      background: #fde7ea;
      color: #ef4444;
    }

    .quick-card .btn {
      font-size: 1.08rem;
      font-weight: 500;
      border-radius: 8px;
      margin-bottom: 0.7rem;
      box-shadow: 0 2px 8px rgba(140, 140, 200, 0.07);
    }

    .quick-card .btn-primary {
      background: #2563eb;
      border: none;
    }

    .quick-card .btn-primary:hover {
      background: #1d4ed8;
    }

    @media (max-width: 900px) {
      .dashboard-title {
        font-size: 1.4rem;
      }

      .card-stat {
        min-width: 150px;
        padding: 1rem;
      }

      .recent-card,
      .quick-card {
        padding: 1rem;
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
            <a href="index.php" class="active"><i class="bi bi-grid"></i> Facility Reservation</a>
        </div>

        <div class="content">
            <div class="container-fluid px-3 px-md-5">
                <div class="dashboard-title mt-4">Department Dashboard</div>
                <div class="dashboard-desc mb-4">Manage your facility reservations</div>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card-stat pending">
                                <span class="icon text-warning"><i class="bi bi-clock-history"></i></span>
                            <div>
                            <div class="stat-label">Pending Requests</div>
                            <div class="stat-value">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                <div class="card-stat approved">
                    <span class="icon text-success"><i class="bi bi-check-circle"></i></span>
                <div>
                <div class="stat-label">Approved Reservations</div>
                <div class="stat-value">1</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-stat rejected">
          <span class="icon text-danger"><i class="bi bi-x-circle"></i></span>
        <div>
        <div class="stat-label">Rejected Requests</div>
        <div class="stat-value">1</div>
    </div>
</div>
</div>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="recent-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-bold mb-0">Recent Reservations</h5>
                <a href="#" class="btn btn-outline-primary btn-sm">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table recent-table mb-0">
                <thead>
                    <tr>
                        <th>Facility</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">Main Conference Room</td>
                        <td>10/20/2023</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                        <td><a href="#" class="text-decoration-none text-primary fw-semibold">View</a></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Auditorium</td>
                        <td>10/25/2023</td>
                        <td><span class="status-badge status-rejected">Rejected</span></td>
                        <td><a href="#" class="text-decoration-none text-primary fw-semibold">View</a></td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="quick-card">
          <div class="fw-bold mb-3">Quick Actions</div>
          <a href="reservation.php">
                      <button class="btn btn-primary w-100 mb-2"><i class="bi bi-plus-lg me-2"></i> New Reservation Request</button>
          </a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
