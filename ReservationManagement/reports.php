<?php
include '../connection.php';
// 1. Facility Usage (how many events per facility)
$facilityUsage = [];
$facRes = $conn->query("SELECT facility_reserved, COUNT(*) AS usage_count FROM reservation_event_report GROUP BY facility_reserved ORDER BY usage_count DESC");
while ($row = $facRes->fetch_assoc()) {
  $facilityUsage['labels'][] = $row['facility_reserved'];
  $facilityUsage['data'][] = (int)$row['usage_count'];
}

// 2. Event Status Distribution (based on handover/incident/feedback)
$statusCounts = [
  'With Incident' => 0,
  'No Incident' => 0,
  'Needs Maintenance' => 0,
  'Clean' => 0,
  'Damaged' => 0,
];
$res = $conn->query("SELECT incident_report, facility_condition FROM reservation_event_report");
while ($row = $res->fetch_assoc()) {
  if (strtolower($row['incident_report']) === 'yes') $statusCounts['With Incident']++;
  else $statusCounts['No Incident']++;
  if ($row['facility_condition'] === 'Needs Maintenance') $statusCounts['Needs Maintenance']++;
  if ($row['facility_condition'] === 'Clean') $statusCounts['Clean']++;
  if ($row['facility_condition'] === 'Damaged') $statusCounts['Damaged']++;
}

// 3. Average Attendance Rate
$attRes = $conn->query("SELECT AVG(actual_attendees/expected_attendees)*100 AS avg_attendance FROM reservation_event_report WHERE expected_attendees > 0");
$avgAttendance = $attRes->fetch_assoc()['avg_attendance'] ? round($attRes->fetch_assoc()['avg_attendance'], 1) : 'N/A';

// 4. Most Used Equipment
$equipCounts = [];
$equipRes = $conn->query("SELECT equipment_used FROM reservation_event_report WHERE equipment_used IS NOT NULL AND equipment_used != ''");
while ($row = $equipRes->fetch_assoc()) {
  foreach (explode(',', $row['equipment_used']) as $equip) {
    $equip = trim($equip);
    if ($equip) $equipCounts[$equip] = ($equipCounts[$equip] ?? 0) + 1;
  }
}
arsort($equipCounts);
$topEquipment = $equipCounts ? array_key_first($equipCounts) : 'N/A';

// 5. Most Frequent Host
$hostRes = $conn->query("SELECT host_name, COUNT(*) as cnt FROM reservation_event_report GROUP BY host_name ORDER BY cnt DESC LIMIT 1");
$topHost = $hostRes->num_rows ? $hostRes->fetch_assoc()['host_name'] : 'N/A';

// 6. History Table
$historyRes = $conn->query("SELECT * FROM reservation_event_report ORDER BY reservation_date DESC, reservation_start DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Post-Reservation Event Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="/Administrative/asset/image.png" alt="Logo" style="height: 60px;"></div>
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="facilities.php"><i class="bi bi-building"></i> Facilities</a>
    <a href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
    <a href="#" class="active"><i class="bi bi-bar-chart"></i> Reports</a>
    <hr>
    <a href="submenu/account.php"><i class="bi bi-person"></i> Account</a>
    <a href="submenu/setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="submenu/help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <div class="main-content">
          <div class="mb-3 d-flex justify-content-end">
        <a href="services/export_event_history.php" class="btn btn-success">
          <i class="bi bi-file-earmark-excel"></i> Generate Event History Report (Excel)
        </a>
      </div>
    <div class="dashboard-title mb-4">Post-Reservation Event Reports</div>
    <div class="row g-4 mb-4">
      <div class="col-md-3">
        <div class="stats-card">
          <div class="icon"><i class="bi bi-people"></i></div>
          <div class="label">Avg. Attendance Rate</div>
          <div class="value"><?= $avgAttendance ?>%</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card">
          <div class="icon"><i class="bi bi-tools"></i></div>
          <div class="label">Most Used Equipment</div>
          <div class="value"><?= htmlspecialchars($topEquipment) ?></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card">
          <div class="icon"><i class="bi bi-person-badge"></i></div>
          <div class="label">Top Host</div>
          <div class="value"><?= htmlspecialchars($topHost) ?></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stats-card">
          <div class="icon"><i class="bi bi-building"></i></div>
          <div class="label">Most Used Facility</div>
          <div class="value"><?= $facilityUsage['labels'][0] ?? 'N/A' ?></div>
        </div>
      </div>
    </div>
    <div class="row g-4 mb-4">
      <div class="col-lg-6">
        <div class="bg-white rounded-3 shadow-sm p-3 mb-3">
          <div style="font-weight:600;font-size:1.08rem;margin-bottom:0.7rem;">Facility Usage</div>
          <canvas id="facilityUsageChart" height="140"></canvas>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="bg-white rounded-3 shadow-sm p-3 mb-3">
          <div style="font-weight:600;font-size:1.08rem;margin-bottom:0.7rem;">Event Status Distribution</div>
          <canvas id="eventStatusChart" height="140"></canvas>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-3 shadow-sm p-3 mb-3">
      <div style="font-weight:600;font-size:1.08rem;margin-bottom:0.7rem;">Event History</div>
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Date</th>
              <th>Host</th>
              <th>Department</th>
              <th>Facility</th>
              <th>Purpose</th>
              <th>Expected</th>
              <th>Actual</th>
              <th>Condition</th>
              <th>Incident</th>
              <th>Feedback</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $historyRes->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['reservation_date']) ?> <?= htmlspecialchars($row['reservation_start']) ?>-<?= htmlspecialchars($row['reservation_end']) ?></td>
                <td><?= htmlspecialchars($row['host_name']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['facility_reserved']) ?></td>
                <td><?= htmlspecialchars($row['purpose']) ?></td>
                <td><?= htmlspecialchars($row['expected_attendees']) ?></td>
                <td><?= htmlspecialchars($row['actual_attendees']) ?></td>
                <td>
                  <?php
                    if ($row['facility_condition'] === 'Clean') echo '<span class="badge bg-success">Clean</span>';
                    elseif ($row['facility_condition'] === 'Damaged') echo '<span class="badge bg-danger">Damaged</span>';
                    elseif ($row['facility_condition'] === 'Needs Maintenance') echo '<span class="badge bg-warning text-dark">Needs Maintenance</span>';
                    else echo htmlspecialchars($row['facility_condition']);
                  ?>
                </td>
                <td>
                  <?php
                    if (strtolower($row['incident_report']) === 'yes') echo '<span class="badge bg-danger">Yes</span>';
                    else echo '<span class="badge bg-success">No</span>';
                  ?>
                </td>
                <td><?= htmlspecialchars($row['feedback']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Facility Usage Bar Chart
    new Chart(document.getElementById('facilityUsageChart').getContext('2d'), {
      type: 'bar',
      data: {
        labels: <?= json_encode($facilityUsage['labels'] ?? []); ?>,
        datasets: [{
          label: 'Usage',
          data: <?= json_encode($facilityUsage['data'] ?? []); ?>,
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

    // Event Status Pie Chart
    new Chart(document.getElementById('eventStatusChart').getContext('2d'), {
      type: 'pie',
      data: {
        labels: <?= json_encode(array_keys($statusCounts)); ?>,
        datasets: [{
          data: <?= json_encode(array_values($statusCounts)); ?>,
          backgroundColor: ['#ef4444', '#10b981', '#facc15', '#6366f1', '#a78bfa'],
        }]
      },
      options: {
        plugins: {
          legend: { display: true },
          tooltip: {
            callbacks: {
              label: function(context) {
                let label = context.label || '';
                let value = context.parsed;
                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                let percent = total ? Math.round((value / total) * 100) : 0;
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