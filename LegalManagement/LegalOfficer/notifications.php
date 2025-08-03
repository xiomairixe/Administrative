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
      color: #4311a5;
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
      font-family: 'QuickSand', 'Poppins';
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
      color: #4311a5;
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
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="cases.php"><i class="bi bi-building"></i> Legal Cases</a>
    <a href="documents.php"><i class="bi bi-calendar-check"></i> Documents</a>
    <a href="notifications.php"><i class="bi bi-bar-chart"></i> Notifications</a>
    <a href="#" class="active"><i class="bi bi-bell"></i> Notifications</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

  <!-- Main Content -->
<div class="main-content">
  <div class="topbar">
    <div>
      <div class="dashboard-title">Notification</div>
      <div class="breadcrumbs">Home / Notification</div>
    </div>
    <div class="profile">
      <div style="position:relative;">
        <i class="bi bi-envelope"></i>
        <span class="badge">2</span>
      </div>
      <img src="#" class="profile-img" alt="profile">
      <div class="profile-info">
        <strong>Steven</strong><br>
        <small>Admin</small>
      </div>
    </div>
  </div>

  <!-- Content Body -->
  <section class="content-body">
    <div class="bg-white rounded-3 shadow-sm p-0 mb-4" style="overflow:hidden;">
        <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom" style="background: #faf6ff;">
          <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;display:flex;align-items:center;gap:0.7rem;">
            <i class="bi bi-bell" style="color:#a78bfa;font-size:1.5rem;"></i>
            Your Notifications
            <span style="background:#e9d5ff;color:#a78bfa;font-size:0.93rem;font-weight:600;padding:2px 10px;border-radius:12px;margin-left:8px;">2 unread
            </span>
          </div>
          <div>
            <a href="#" style="color:#8b5cf6;font-weight:500;text-decoration:none;margin-right:18px;">Mark all as read</a>
            <a href="#" style="color:#bfc7d1;font-weight:500;text-decoration:none;">Clear all</a>
          </div>
        </div>
        <div>
          <!-- Notification 1 (unread) -->
          <div class="d-flex align-items-start px-4 py-3" style="background:#faf6ff;border-bottom:1px solid #f3f4f6;">
            <i class="bi bi-info-circle" style="color:#a78bfa;font-size:1.3rem;margin-right:1rem;margin-top:2px;"></i>
            <div class="flex-grow-1">
              <div style="font-weight:600;">New booking request</div>
              <div style="color:#444;">S.Lopez has requested to book Conference Room A for tomorrow at 10:00 AM
              </div>
            </div>
            <div style="color:#888;font-size:0.98rem;white-space:nowrap;margin-left:1.5rem;">5 minutes ago</div>
          </div>
          <!-- Notification 2 (unread) -->
          <div class="d-flex align-items-start px-4 py-3" style="background:#faf6ff;border-bottom:1px solid #f3f4f6;">
            <i class="bi bi-check-circle" style="color:#22c55e;font-size:1.3rem;margin-right:1rem;margin-top:2px;"></i>
            <div class="flex-grow-1">
              <div style="font-weight:600;">Booking confirmed</div>
              <div style="color:#444;">Your booking for Auditorium on April 15th has been confirmed</div>
            </div>
            <div style="color:#888;font-size:0.98rem;white-space:nowrap;margin-left:1.5rem;">1 hour ago</div>
          </div>
          <!-- Notification 3 -->
          <div class="d-flex align-items-start px-4 py-3" style="border-bottom:1px solid #f3f4f6;">
            <i class="bi bi-exclamation-circle" style="color:#f59e42;font-size:1.3rem;margin-right:1rem;margin-top:2px;"></i>
            <div class="flex-grow-1">
              <div style="font-weight:600;">Maintenance scheduled</div>
              <div style="color:#444;">Meeting Room C will be unavailable on April 20th due to scheduled maintenance
              </div>
            </div>
            <div style="color:#888;font-size:0.98rem;white-space:nowrap;margin-left:1.5rem;">3 hours ago</div>
          </div>
          <!-- Notification 4 -->
          <div class="d-flex align-items-start px-4 py-3" style="border-bottom:1px solid #f3f4f6;">
            <i class="bi bi-x-circle" style="color:#ef4444;font-size:1.3rem;margin-right:1rem;margin-top:2px;"></i>
            <div class="flex-grow-1">
              <div style="font-weight:600;">Booking cancelled</div>
              <div style="color:#444;">J.Williams has cancelled their booking for the Gym on April 10th</div>
            </div>
            <div style="color:#888;font-size:0.98rem;white-space:nowrap;margin-left:1.5rem;">5 hours ago</div>
          </div>
          <!-- Notification 5 -->
          <div class="d-flex align-items-start px-4 py-3">
            <i class="bi bi-info-circle" style="color:#a78bfa;font-size:1.3rem;margin-right:1rem;margin-top:2px;"></i>
            <div class="flex-grow-1">
              <div style="font-weight:600;">New facility added</div>
              <div style="color:#444;">A new facility "Executive Boardroom" has been added to the system</div>
            </div>
            <div style="color:#888;font-size:0.98rem;white-space:nowrap;margin-left:1.5rem;">1 day ago</div>
          </div>
          <div class="text-center py-3" style="background:#fff;">
            <a href="#" style="color:#8b5cf6;font-weight:500;text-decoration:none;">Load more notifications</a>
          </div>
        </div>
      </div>
  </section>
</div>
</div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>