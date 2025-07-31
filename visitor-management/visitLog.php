<?php
  include('connection.php');

  $sql = "SELECT * FROM visitors";
  $visitor = $conn->query($sql)or die($conn->error);
  $row = $visitor->fetch_assoc();
?>

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
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="facilities.php"><i class="bi bi-person-plus"></i> Facilities</a>
    <a href="#" class="active"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <div class="main-content">
    <div class="topbar mb-4">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle d-lg-none" id="sidebarToggle2" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </button>
        <nav class="nav">
          <a class="nav-link" href="#">Home</a>
          <a class="nav-link" href="#">Contact</a>
        </nav>
      </div>
      <form class="d-none d-md-block">
        <input type="text" class="search-bar" placeholder="Search here">
      </form>
      <div class="profile">
        <div style="position:relative;">
          <i class="bi bi-bell"></i>
          <span class="badge">2</span>
        </div>
        <img src="#" class="profile-img" alt="profile">
        <div class="profile-info">
          <strong>J.Amongo</strong><br>
          <small>Admin</small>
        </div>
      </div>
    </div>
    <div class="container-fluid px-0">
      <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
        <div style="font-family:'Montserrat',sans-serif;font-size:2rem;font-weight:700;color:#22223b;">Visitor Log</div>
      </div>
      <div style="color:#6c757d;font-size:1.08rem;margin-bottom:1.5rem;">View and search visitor history</div>
      <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
        <div class="row g-2 align-items-center">
          <div class="col-md-9 mb-2 mb-md-0">
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
              <input type="text" class="form-control border-start-0" placeholder="Search by name, email, company...">
            </div>
          </div>
          <div class="col-md-3">
            <div class="dropdown w-100">
              <button class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-between" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-funnel me-2"></i> <span id="statusLabel">All Statuses</span>
              </button>
              <ul class="dropdown-menu w-100" aria-labelledby="statusDropdown">
                <li><a class="dropdown-item" href="#" onclick="setStatus('All Statuses')">All Statuses</a></li>
                <li><a class="dropdown-item" href="#" onclick="setStatus('Pre-registered')">Pre-registered</a></li>
                <li><a class="dropdown-item" href="#" onclick="setStatus('Checked In')">Checked In</a></li>
                <li><a class="dropdown-item" href="#" onclick="setStatus('Checked Out')">Checked Out</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Visitor Name</th>
              <th scope="col">Email</th>
              <th scope="col">Company</th>
              <th scope="col">Status</th>
              <th scope="col">Check-In</th>
              <th scope="col">Check-Out</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $visitor->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['full_name']); ?></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
              <!-- <td><?php echo htmlspecialchars($row['status']); ?></td>
              <td><?php echo htmlspecialchars($row['check_in']); ?></td>
              <td><?php echo htmlspecialchars($row['check_out']); ?></td> -->
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>