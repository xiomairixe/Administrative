
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Legal Request</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .form-section {
      background-color: #f8f9fa;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .form-label {
      font-weight: 600;
    }
    .history-card {
      border-left: 5px solid #6c63ff;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <h2 class="mb-4">New Legal Request</h2>

  <!-- Legal Request Form -->
  <form action="submit_request.php" method="POST" enctype="multipart/form-data" class="form-section mb-5">
    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Request Type*</label>
        <select class="form-select" name="request_type" required>
          <option value="">Select type</option>
          <option value="contract">Contract Review</option>
          <option value="compliance">Compliance Check</option>
          <option value="litigation">Litigation Support</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Priority*</label>
        <select class="form-select" name="priority" required>
          <option value="High">High</option>
          <option value="Medium" selected>Medium</option>
          <option value="Low">Low</option>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Request Title*</label>
      <input type="text" class="form-control" name="title" required placeholder="Brief title describing the request">
    </div>

    <div class="mb-3">
      <label class="form-label">Description*</label>
      <textarea class="form-control" name="description" rows="4" required placeholder="Provide detailed information about your request"></textarea>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label class="form-label">Deadline (if applicable)</label>
        <input type="date" class="form-control" name="deadline">
      </div>
      <div class="col-md-6">
        <label class="form-label">Additional Stakeholders</label>
        <input type="text" class="form-control" name="stakeholders" placeholder="Names or emails, separated by commas">
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label">Supporting Documents</label>
      <input class="form-control" type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.png">
      <small class="text-muted">PDF, DOC, DOCX, JPG, PNG up to 10MB each</small>
    </div>

    <div class="d-flex justify-content-between">
      <button type="reset" class="btn btn-secondary">Save Draft</button>
      <button type="submit" class="btn btn-primary">Submit Request</button>
    </div>
  </form>

  <!-- Request History (Example, should be loaded from DB) -->
  <h4>Request History</h4>
  <div class="form-section">
    <?php
    // Example: Fetch request history for the logged-in user
    // require 'db.php'; // Your DB connection
    // $user_id = $_SESSION['user_id'];
    // $stmt = $pdo->prepare("SELECT * FROM legal_requests WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    // $stmt->execute([$user_id]);
    // while ($row = $stmt->fetch()):
    ?>
    <!--
    <div class="mb-3 p-3 border history-card">
      <h6><?= htmlspecialchars($row['request_type']) ?>: <?= htmlspecialchars($row['title']) ?></h6>
      <small class="text-muted">Submitted: <?= htmlspecialchars($row['created_at']) ?> | Priority: <?= htmlspecialchars($row['priority']) ?></small><br>
      <small class="text-muted">Status: <?= htmlspecialchars($row['status']) ?></small>
    </div>
    -->
    <?php // endwhile; ?>
    <!-- Demo static history below, remove if using PHP above -->
    <div class="mb-3 p-3 border history-card">
      <h6>Contract Review: NDA Agreement</h6>
      <small class="text-muted">Submitted: 2025-07-28 | Priority: High</small><br>
      <small class="text-muted">Status: Pending</small>
    </div>
    <div class="mb-3 p-3 border history-card">
      <h6>Compliance Check: Policy Update</h6>
      <small class="text-muted">Submitted: 2025-07-20 | Priority: Medium</small><br>
      <small class="text-muted">Status: In Review</small>
    </div>
  </div>
</div>
</body>
</html>
