<?php
// filepath: c:\xampp\htdocs\Administrative\ReservationManagement\index.php
include '../connection.php';

// Stats
$total = $conn->query("SELECT COUNT(*) AS cnt FROM reservation_requests")->fetch_assoc()['cnt'];
$active = $conn->query("SELECT COUNT(*) AS cnt FROM reservation_requests WHERE status = 'Approved'")->fetch_assoc()['cnt'];
$completed = $conn->query("SELECT COUNT(*) AS cnt FROM reservation_requests WHERE status = 'Completed'")->fetch_assoc()['cnt'];
$pending = $conn->query("SELECT COUNT(*) AS cnt FROM reservation_requests WHERE status = 'Pending'")->fetch_assoc()['cnt'];

// Recent Reservations
$recent = $conn->query("SELECT r.request_id, r.requested_at, v.full_name AS customer, f.facility_name AS rider
  FROM reservation_requests r
  LEFT JOIN visitors v ON v.visitor_id = r.request_id AND v.is_head = 1
  LEFT JOIN facilities f ON f.facility_id = r.facility_id
  ORDER BY r.requested_at DESC LIMIT 5");

// Activity (show last 5 visitors who registered)
$activity = $conn->query("SELECT v.full_name AS customer, 'Online' AS status
  FROM visitors v
  ORDER BY v.created_at DESC LIMIT 5");

// Fetch reservations for calendar
$calendarEvents = [];
$resCal = $conn->query("SELECT r.request_id, r.requested_at, r.status, f.facility_name
  FROM reservation_requests r
  LEFT JOIN facilities f ON f.facility_id = r.facility_id");
while ($row = $resCal->fetch_assoc()) {
  $calendarEvents[] = [
    'id' => $row['request_id'],
    'title' => $row['facility_name'] . ' (' . $row['status'] . ')',
    'start' => date('Y-m-d', strtotime($row['requested_at'])),
    'color' => ($row['status'] == 'Approved' ? '#10b981' : ($row['status'] == 'Pending' ? '#facc15' : ($row['status'] == 'Completed' ? '#6366f1' : '#ef4444')))
  ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Facilities Reservation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
  <link rel="stylesheet" href="styles/index.css">
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
    <a href="submenu/account.php"><i class="bi bi-person"></i> Account</a>
    <a href="submenu/setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="submenu/help.php"><i class="bi bi-question-circle"></i> Help</a>
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
    <div class="mb-3 d-flex justify-content-end">
      <a href="services/export_all_reservations.php" class="btn btn-success">
        <i class="bi bi-file-earmark-excel"></i> Export All Reservations (Excel)
      </a>
    </div>

    <!-- Stats -->
    <div class="stats-cards">
      <div class="stats-card">
        <div class="icon"><i class="bi bi-clipboard-data"></i></div>
        <div>
          <div class="label">Total Reservation</div>
          <div class="value"><?php echo $total; ?></div>
        </div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-people"></i></div>
        <div>
          <div class="label">Active Reservation</div>
          <div class="value"><?php echo $active; ?></div>
        </div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-check2-circle"></i></div>
        <div>
          <div class="label">Completed</div>
          <div class="value"><?php echo $completed; ?></div>
        </div>
      </div>
      <div class="stats-card">
        <div class="icon"><i class="bi bi-geo-alt"></i></div>
        <div>
          <div class="label">Pending Reservation</div>
          <div class="value"><?php echo $pending; ?></div>
        </div>
      </div>
    </div>

    <!-- Minimalist Reservation Status Chart -->
    <div class="dashboard-row">
      <div class="dashboard-col" style="max-width:340px; text-align:center;">
        <div class="chart-title">Reservation Status</div>
        <canvas id="rideStatusChart" height="140" style="max-width:180px;margin:0 auto;"></canvas>
        <div class="d-flex justify-content-center gap-2 mt-2" style="font-size:0.93rem;">
          <span style="color:#10b981;"><i class="bi bi-circle-fill" style="color:#10b981;font-size:0.9rem;"></i> Approved</span>
          <span style="color:#facc15;"><i class="bi bi-circle-fill" style="color:#facc15;font-size:0.9rem;"></i> Pending</span>
          <span style="color:#ef4444;"><i class="bi bi-circle-fill" style="color:#ef4444;font-size:0.9rem;"></i> Rejected</span>
          <span style="color:#6366f1;"><i class="bi bi-circle-fill" style="color:#6366f1;font-size:0.9rem;"></i> Completed</span>
        </div>
      </div>
      <div class="dashboard-col">
        <div class="chart-title">Bookings Overview</div>
        <canvas id="bookingsChart" height="140"></canvas>
      </div>
    </div>

    <!-- Tables -->
    <div class="dashboard-row">
      <div class="dashboard-col">
        <h5 style="font-size:1.08rem;">Recent Reservation</h5>
        <table class="table table-borderless align-middle">
          <thead>
            <tr style="background:#f8f9fa;">
              <th>Reservation ID</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Facility</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $recent->fetch_assoc()): ?>
              <tr>
                <td><?php echo $row['request_id']; ?></td>
                <td><?php echo date('m/d/Y', strtotime($row['requested_at'])); ?></td>
                <td><?php echo htmlspecialchars($row['customer']); ?></td>
                <td><?php echo htmlspecialchars($row['rider']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
      <div class="dashboard-col">
        <h5 style="font-size:1.08rem;">Activity</h5>
        <table class="table table-borderless align-middle">
          <thead>
            <tr style="background:#f8f9fa;">
              <th>Customer</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $activity->fetch_assoc()): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['customer']); ?></td>
                <td><span class="status-badge online"><?php echo $row['status']; ?></span></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Reservation Calendar -->
    <div class="dashboard-row">
      <div class="dashboard-col">
        <div class="chart-title">Reservation Calendar</div>
        <div id="reservationCalendar"></div>
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

    // Minimalist Reservation Status Chart
    const statusCtx = document.getElementById('rideStatusChart').getContext('2d');
    new Chart(statusCtx, {
      type: 'doughnut',
      data: {
        labels: ['Approved', 'Pending', 'Completed', 'Rejected'],
        datasets: [{
          data: [
            <?php echo $active; ?>,
            <?php echo $pending; ?>,
            <?php echo $completed; ?>,
            <?php echo $conn->query("SELECT COUNT(*) AS cnt FROM reservation_requests WHERE status = 'Rejected'")->fetch_assoc()['cnt']; ?>
          ],
          backgroundColor: ['#10b981', '#facc15', '#6366f1', '#ef4444'],
          borderWidth: 0,
          hoverOffset: 6
        }]
      },
      options: {
        cutout: '75%',
        plugins: {
          legend: { display: false },
          tooltip: { enabled: true }
        }
      }
    });

    // Bookings Chart (static demo)
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

    // Reservation Calendar (dynamic)
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('reservationCalendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 500,
        events: <?php echo json_encode($calendarEvents); ?>,
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek'
        },
        eventClick: function(info) {
          alert('Reservation ID: ' + info.event.id + '\nFacility: ' + info.event.title);
        }
      });
      calendar.render();
    });
  </script>
</body>

</html>