<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ViaHale Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
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
        <div class="icon"><i class=""></i></div>
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