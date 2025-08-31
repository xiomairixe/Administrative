<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Visitor Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      background: #fafbfc;
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
      color: #181818ff;
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
      font-family: 'QuickSand', 'Poppins', Arial, sans-serif;
      font-size: 1.6rem;
      color: #fff;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sidebar .logo i {
      font-size: 2rem;
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

    .breadcrumbs {
      color: #9a66ff;
      font-size: 1rem;
      text-align: right;
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

      .btn,
      .form-control,
      .form-select {
        width: 100% !important;
        margin-bottom: 0.5rem;
      }
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
  </style>
</head>

<body>
  <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-list"></i>
  </button>
  <div class="sidebar" id="sidebarNav">
    <div class="logo mb-5"> <img src="\Administrative\asset\image.png" alt="Logo" style="height: 60px;"></div>
    <a href="../index.php"><i class="bi bi-grid"></i> Dashboard</a>
    <a href="../visitLog.php"><i class="bi bi-journal-text"></i> Visitor Log</a>
    <a href="../visitor.php"><i class="bi bi-person-lines-fill"></i> Visitors</a>
    <a href="../blacklisted.php"><i class="bi bi-slash-circle"></i> Blacklist</a>
    <a href="../security.php"><i class="bi bi-slash-circle"></i> Security</a>
    <hr>
    <a href="account.php"><i class="bi bi-person"></i> Account</a>
    <a href="setting.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="#"><i class="bi bi-telephone"></i> Call Center</a>
    <a href="help.php" class="active"><i class="bi bi-question-circle"></i> Help</a>
    <a href="#"><i class="bi bi-box-arrow-right"></i> Log Out</a>
  </div>
  <!-- Content Body -->
  <div class="main-content" style="background:#f8f9fb;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <div>
        <div style="font-family:'Montserrat',sans-serif;font-size:2rem;font-weight:700;color:#22223b;">Help Center</div>
      </div>
      <div class="breadcrumbs" style="color:#8b5cf6;font-size:1rem;">
        Home <span style="color:#bfc7d1;">/</span> <span style="color:#22223b;">Help</span>
      </div>
    </div>
    <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
      <div class="text-center mb-3" style="font-family:'Montserrat',sans-serif;font-size:1.18rem;font-weight:700;">
        How can we help you?
      </div>
      <div class="text-center mb-4" style="color:#6c757d;">
        Search our knowledge base or browse frequently asked questions
      </div>
      <form class="d-flex justify-content-center mb-2">
        <input type="text" class="form-control" placeholder="Search for help..." style="max-width:480px;background:#faf6ff;">
      </form>
    </div>
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="bg-white rounded-3 shadow-sm p-4 text-center h-100">
          <div style="background:#f4ebff;width:56px;height:56px;border-radius:50%;margin:0 auto 1rem auto;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-plus-lg" style="color:#a78bfa;font-size:2rem;"></i>
          </div>
          <div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:1.08rem;">Getting Started</div>
          <div style="color:#6c757d;font-size:0.98rem;margin-bottom:0.7rem;">Learn the basics of using our facility reservation system</div>
          <a href="#" style="color:#8b5cf6;font-weight:500;text-decoration:none;">View guides</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bg-white rounded-3 shadow-sm p-4 text-center h-100">
          <div style="background:#f4ebff;width:56px;height:56px;border-radius:50%;margin:0 auto 1rem auto;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-question-lg" style="color:#a78bfa;font-size:2rem;"></i>
          </div>
          <div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:1.08rem;">FAQ</div>
          <div style="color:#6c757d;font-size:0.98rem;margin-bottom:0.7rem;">Find answers to commonly asked questions</div>
          <a href="#" style="color:#8b5cf6;font-weight:500;text-decoration:none;">Browse FAQs</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="bg-white rounded-3 shadow-sm p-4 text-center h-100">
          <div style="background:#f4ebff;width:56px;height:56px;border-radius:50%;margin:0 auto 1rem auto;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-envelope" style="color:#a78bfa;font-size:2rem;"></i>
          </div>
          <div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:1.08rem;">Contact Support</div>
          <div style="color:#6c757d;font-size:0.98rem;margin-bottom:0.7rem;">Can't find what you need? Reach out to our support team</div>
          <a href="#" style="color:#8b5cf6;font-weight:500;text-decoration:none;">Get Support</a>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-3 shadow-sm p-0 mb-4">
      <div style="font-family:'Montserrat',sans-serif;font-size:1.08rem;font-weight:700;padding:1.2rem 1.5rem 0.7rem 1.5rem;">
        Frequently Asked Questions
      </div>
      <div class="accordion accordion-flush" id="faqAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq1h">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="false" aria-controls="faq1" style="font-weight:600;">
              How do I make a facility reservation?
            </button>
          </h2>
          <div id="faq1" class="accordion-collapse collapse" aria-labelledby="faq1h" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Log in to your account, navigate to the Facilities page, select your desired facility, and follow the booking instructions.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq2h">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2" style="font-weight:600;">
              How far in advance can I book a facility?
            </button>
          </h2>
          <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faq2h" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Facilities can typically be booked up to 3 months in advance, subject to availability and your organization's policies.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq3h">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3" style="font-weight:600;">
              How do I cancel a reservation?
            </button>
          </h2>
          <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faq3h" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Go to your Bookings page, find the reservation you wish to cancel, and click the "Cancel" button next to it.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq4h">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4" style="font-weight:600;">
              What if I need to modify my reservation?
            </button>
          </h2>
          <div id="faq4" class="accordion-collapse collapse" aria-labelledby="faq4h" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              You can modify your reservation from the Bookings page by selecting the reservation and choosing "Edit" or "Modify".
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="faq5h">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5" style="font-weight:600;">
              Who can I contact for technical support?
            </button>
          </h2>
          <div id="faq5" class="accordion-collapse collapse" aria-labelledby="faq5h" data-bs-parent="#faqAccordion">
            <div class="accordion-body">
              Please use the "Contact Support" option above or email our IT helpdesk for assistance.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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
  </script>
</body>

</html>