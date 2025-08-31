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
    <a href="case.php"><i class="bi bi-building"></i> Assigned Cases</a>
    <a href="reports.php"><i class="bi bi-bar-chart"></i> Reports</a>
    <hr>
    <a href="#" class="actives"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="help.php"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>

  <!-- Main Content -->
<div class="main-content">
  <div class="topbar">
    <div>
      <div class="dashboard-title">Account</div>
      <div class="breadcrumbs">Home / Account</div>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <div style="font-family:'Montserrat',sans-serif;font-size:2rem;font-weight:700;color:#22223b;">Account</div>
        </div>
        <div class="breadcrumbs" style="color:#8b5cf6;font-size:1rem;">
          Home <span style="color:#bfc7d1;">/</span> <span style="color:#22223b;">Account</span>
        </div>
      </div>
      <div class="row g-4">
        <!-- Left Profile Card -->
        <div class="col-lg-4">
          <div class="bg-white rounded-3 shadow-sm p-4 h-100 d-flex flex-column align-items-center">
            <img src="#" alt="Profile" class="mb-3"
              style="width:90px;height:90px;border-radius:50%;object-fit:cover;">
            <div style="font-family:'Montserrat',sans-serif;font-size:1.25rem;font-weight:700;">L. Ramos</div>
            <div style="color:#6c757d;font-size:1.05rem;margin-bottom:1.5rem;">Administrator</div>
            <div class="w-100">
              <a href="#" class="d-flex align-items-center gap-2 mb-2 px-3 py-2 rounded-2"
                style="background:#f4ebff;color:#8b5cf6;font-weight:600;text-decoration:none;">
                <i class="bi bi-person"></i> Profile
              </a>
              <a href="#" class="d-flex align-items-center gap-2 mb-2 px-3 py-2 rounded-2"
                style="color:#22223b;text-decoration:none;">
                <i class="bi bi-key"></i> Password
              </a>
              <a href="#" class="d-flex align-items-center gap-2 mb-2 px-3 py-2 rounded-2"
                style="color:#22223b;text-decoration:none;">
                <i class="bi bi-bell"></i> Notifications
              </a>
              <a href="#" class="d-flex align-items-center gap-2 px-3 py-2 rounded-2"
                style="color:#22223b;text-decoration:none;">
                <i class="bi bi-shield-lock"></i> Security
              </a>
            </div>
          </div>
        </div>
        <!-- Profile Information Form -->
        <div class="col-lg-8">
          <div class="bg-white rounded-3 shadow-sm p-4 h-100">
            <div style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;margin-bottom:1.2rem;">Profile
              Information</div>
            <form>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label" style="font-weight:600;">First Name</label>
                  <input type="text" class="form-control" value="Lance">
                </div>
                <div class="col-md-6">
                  <label class="form-label" style="font-weight:600;">Last Name</label>
                  <input type="text" class="form-control" value="Ramos">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label" style="font-weight:600;">Email</label>
                  <input type="email" class="form-control" value="lance.ramos@example.com">
                </div>
                <div class="col-md-6">
                  <label class="form-label" style="font-weight:600;">Phone</label>
                  <input type="text" class="form-control" value="(555) 123-4567">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label" style="font-weight:600;">Department</label>
                <input type="text" class="form-control" value="Administration">
              </div>
              <div class="mb-4">
                <label class="form-label" style="font-weight:600;">Bio</label>
                <textarea class="form-control" rows="2">Facility administrator responsible for managing bookings and user requests.</textarea>
              </div>
              <div class="text-end">
                <button type="submit" class="btn"
                  style="background:#8b5cf6;color:#fff;font-weight:600;padding:0.6rem 2.2rem;font-size:1.08rem;">Save
                  Changes</button>
              </div>
            </form>
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