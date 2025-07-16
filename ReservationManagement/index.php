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
      font-family: 'QuickSand', 'Poppins', Arial;
      color: #22223b;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 250px;
      background: #181c2f;
      padding: 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      z-index: 1040;
      transition: left 0.3s ease;
    }

    .sidebar .logo {
      font-family: 'Montserrat', sans-serif;
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
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 2rem;
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
      color: #4322a5;
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
      font-family: 'Montserrat', sans-serif;
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

    .online {
      background: #dbeafe;
      color: #2563eb;
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
  </style>
</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="bi bi-list"></i>
  </button>

  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="#" class="active"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="facilities.php"><i class="bi bi-building"></i> Facilities</a>
    <a href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="notifications.php"><i class="bi bi-bell"></i> Notifications</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

  <div class="main-content">
    <div class="topbar">
      <div>
        <div class="dashboard-title">Dashboard</div>
        <div class="breadcrumbs">Home / Dashboard</div>
      </div>
      <div class="profile">
        <div style="position:relative;">
          <i class="bi bi-envelope"></i>
          <span class="badge">2</span>
        </div>
        <img src="https://via.placeholder.com/40" alt="Profile" class="profile-img">
        <div>
          <strong>Steven</strong><br>
          <small>Admin</small>
        </div>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-cards">
      <div class="stats-card">
        <div class="icon"><i class="bi bi-car-front"></i></div>
        <div class="label">Total Reservation</div>
        <div class="value">1,540</div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-people"></i></div>
        <div class="label">Active Reservation</div>
        <div class="value">320</div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-check2-circle"></i></div>
        <div class="label">Completed</div>
        <div class="value">8</div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-geo-alt"></i></div>
        <div class="label">Pending Reservation</div>
        <div class="value">125</div>
      </div>
    </div>

    <!-- Charts -->
    <div class="dashboard-row">
      <div class="dashboard-col" style="max-width:500px;">
        <div class="chart-title">Reservation Status</div>
        <canvas id="rideStatusChart" height="140"></canvas>
      </div>
      <div class="dashboard-col">
        <div class="chart-title">Bookings Overview</div>
        <canvas id="bookingsChart" height="140"></canvas>
      </div>
    </div>

    <!-- Tables -->
    <div class="dashboard-row">
      <div class="dashboard-col">
        <h5>Recent Reservation</h5>
        <table class="table">
          <thead>
            <tr>
              <th>Reservation ID</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Rider</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>2846</td><td>04/12/2025</td><td>J.Santos</td><td>S.Lopez</td></tr>
            <tr><td>2845</td><td>04/12/2025</td><td>M.Reyes</td><td>E.Ramos</td></tr>
            <tr><td>2844</td><td>04/10/2025</td><td>A.Cruz</td><td>S.Lopez</td></tr>
          </tbody>
        </table>
      </div>
      <div class="dashboard-col">
        <h5>Activity</h5>
        <table class="table">
          <thead><tr><th>Customer</th><th>Status</th></tr></thead>
          <tbody>
            <tr><td>L.Tan</td><td><span class="status-badge online">Online</span></td></tr>
            <tr><td>R.Bautista</td><td><span class="status-badge online">Online</span></td></tr>
            <tr><td>J.Villanueva</td><td><span class="status-badge online">Online</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarNav = document.getElementById('sidebarNav');
    sidebarToggle.addEventListener('click', () => {
      sidebarNav.classList.toggle('show');
    });

    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    new Chart(bookingsCtx, {
      type: 'line',
      data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
          label: 'Bookings',
          data: [800, 600, 650, 300],
          borderColor: '#8b5cf6',
          backgroundColor: 'rgba(139,92,246,0.1)',
          tension: 0.4,
          pointBackgroundColor: '#8b5cf6',
          fill: true
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true, grid: { color: '#f3f4f6' } }
        }
      }
    });

    // Reservation Status Chart
    const statusCtx = document.getElementById('rideStatusChart').getContext('2d');
    new Chart(statusCtx, {
      type: 'doughnut',
      data: {
        labels: ['Confirmed', 'Pending', 'Canceled'],
        datasets: [{
          data: [60, 30, 10],
          backgroundColor: ['#10b981', '#facc15', '#ef4444'],
          hoverOffset: 6
        }]
      },
      options: {
        plugins: { legend: { position: 'bottom' } }
      }
    });
  </script>
</body>
</html>