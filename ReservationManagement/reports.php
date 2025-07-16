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
    <a href="facilities.php"><i class="bi bi-building"></i> Facilities</a>
    <a href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="#" class="active"><i class="bi bi-bar-chart"></i> Reports</a>
    <a href="notifications.php"><i class="bi bi-bell"></i> Notifications</a>
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
      <div class="dashboard-title">Reports</div>
      <div class="breadcrumbs">Home / Reports</div>
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
      <div class="row g-3 mb-3">
        <div class="col-lg-6">
          <div class="p-3 bg-white rounded-3 shadow-sm h-60">
            <div style="font-family:'Montserrat',sans-serif;font-size:1.08rem;font-weight:600;margin-bottom:0.7rem;">Facility Usage</div>
            <canvas id="facilityUsageChart" height="140"></canvas>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="p-3 bg-white rounded-3 shadow-sm h-60">
            <div style="font-family:'Montserrat',sans-serif;font-size:1.08rem;font-weight:600;margin-bottom:0.7rem;">Booking Status Distribution</div>
            <canvas id="bookingStatusChart" height="60"></canvas>
            <div class="d-flex justify-content-center gap-3 mt-2" style="font-size:0.93rem;">
              <span style="color:#8b5cf6;"><i class="bi bi-square-fill" style="color:#8b5cf6;font-size:0.9rem;"></i> Confirmed</span>
              <span style="color:#22c55e;"><i class="bi bi-square-fill" style="color:#22c55e;font-size:0.9rem;"></i> Pending</span>
              <span style="color:#f59e42;"><i class="bi bi-square-fill" style="color:#f59e42;font-size:0.9rem;"></i> Cancelled</span>
              <span style="color:#ef4444;"><i class="bi bi-square-fill" style="color:#ef4444;font-size:0.9rem;"></i> Completed</span>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-3 shadow-sm p-3 mb-3">
        <div style="font-family:'Montserrat',sans-serif;font-size:1rem;font-weight:600;margin-bottom:0.7rem;">Usage Metrics</div>
        <div class="row g-2">
          <div class="col-md-4">
            <div class="border rounded-3 p-2 h-100">
              <div style="color:#6c757d;font-size:0.98rem;">Average Booking Duration</div>
              <div style="font-size:1.25rem;font-weight:700;color:#22223b;">2.5 hrs</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded-3 p-2 h-100">
              <div style="color:#6c757d;font-size:0.98rem;">Most Popular Day</div>
              <div style="font-size:1.25rem;font-weight:700;color:#22223b;">Wednesday</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded-3 p-2 h-100">
              <div style="color:#6c757d;font-size:0.98rem;">Peak Hours</div>
              <div style="font-size:1.25rem;font-weight:700;color:#22223b;">10AM - 2PM</div>
            </div>
          </div>
        </div>
  </section>


  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarNav = document.getElementById('sidebarNav');
    sidebarToggle?.addEventListener('click', function () {
      sidebarNav.classList.toggle('show');
    });
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 900 && sidebarNav.classList.contains('show')) {
        if (!sidebarNav.contains(e.target) && !sidebarToggle.contains(e.target)) {
          sidebarNav.classList.remove('show');
        }
      }
    });

     // Facility Usage Bar Chart
    new Chart(document.getElementById('facilityUsageChart').getContext('2d'), {
      type: 'bar',
      data: {
        labels: ['Conference Room A', 'Auditorium', 'Training Room B', 'Meeting Room C', 'Gym'],
        datasets: [{
          label: 'Usage',
          data: [42, 28, 35, 22, 15],
          backgroundColor: '#a78bfa',
          borderRadius: 8,
          barThickness: 28,
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { color: '#22223b', font: { family: 'Inter' } } },
          y: { grid: { color: '#e5e7eb' }, beginAtZero: true, ticks: { color: '#22223b', font: { family: 'Inter' } } }
        }
      }
    });

    // Booking Status Pie Chart
    new Chart(document.getElementById('bookingStatusChart').getContext('2d'), {
      type: 'pie',
      data: {
        labels: ['Confirmed', 'Pending', 'Cancelled', 'Completed'],
        datasets: [{
          data: [65, 15, 10, 10],
          backgroundColor: ['#8b5cf6', '#22c55e', '#f59e42', '#ef4444'],
        }]
      },
      options: {
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.label || '';
                let value = context.parsed;
                let total = context.chart._metasets[0].total;
                let percent = Math.round((value / total) * 100);
                return `${label}: ${percent}%`;
              }
            }
          }
        }
      }
    });
  </script>
</body>

</html>