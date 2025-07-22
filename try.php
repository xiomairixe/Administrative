<?php

include('connection.php');

if(isset($_POST["submit"])){

$sql = "INSERT INTO users (username, password, email, role, department, status) VALUES ( '".$_POST["username"]."', '".$_POST["password"]."', '".$_POST["email"]."', '".$_POST["role"]."', '".$_POST["department"]."', '".$_POST["status"]."')";

$result = mysqli_query($conn, $sql);


	if($result)
	{
		echo 'inserted';
        header("Location: ".$_SERVER['PHP_SELF']."?inserted=1");
        exit();

	}
	else 
	{
		  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Legal Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <link rel ="stylesheet" href="accessControl.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




</head>
<body>
<!-- Page Wrapper -->
<div class="container-fluid">
  <div class="row">

    <!-- Sidebar Column -->
    <div class="col-md-2 p-0">
      <div class="sidebar d-flex flex-column justify-content-between shadow-sm border-end">

        <!-- Top Section -->
        <div class="p-3">
          <div class="d-flex justify-content-center align-items-center m-4">
            <img src="\Administrative\asset\image.png" class="img-fluid me-2" style="height: 60px;" alt="Logo">
          </div>

          <!-- Main Navigation -->
          <div class="mb-5">
            <h6 class="text-uppercase mb-2">Main</h6>
            <nav class="nav flex-column">
              <a class="nav-link" href="index.php"><ion-icon name="home-outline"></ion-icon>Dashboard</a>
              <a class="nav-link" href="regulatory.php"><ion-icon name="newspaper-outline"></ion-icon>Regulatory</a>
              <a class="nav-link" href="legalCases.php"><ion-icon name="document-text-outline"></ion-icon>Legal Request</a>
              <a class="nav-link" href="reports.php"><ion-icon name="bar-chart-outline"></ion-icon>Reports</a>
              <a class="nav-link active" href="#"><ion-icon name="key-outline"></ion-icon>Access Control</a>
              <a class="nav-link" href="notifications.php"><ion-icon name="notifications-outline"></ion-icon>Notifications</a>
            </nav>
          </div>

          <!-- Facility Reservation -->
          <div class="mb-5">
            <h6 class="text-uppercase px-2 mb-2">Facility Reservation</h6>
            <nav class="nav flex-column">
              <a class="nav-link" href="facilitiesReservation/index.php"><ion-icon name="business-outline"></ion-icon>Overview</a>
              <a class="nav-link" href="facilitiesReservation/facilities.php"><ion-icon name="build-outline"></ion-icon>Facilities</a>
              <a class="nav-link" href="facilitiesReservation/request.php"><ion-icon name="clipboard-outline"></ion-icon>Requests</a>
              <a class="nav-link" href="facilitiesReservation/history.php"><ion-icon name="time-outline"></ion-icon>History</a>
            </nav>
          </div>

          <!-- Document Management -->
          <div class="mb-5">
            <h6 class="text-uppercase px-2 mb-2">Document Management</h6>
            <nav class="nav flex-column">
              <a class="nav-link" href="documentManagement/index.php"><ion-icon name="folder-outline"></ion-icon>Documents</a>
              <a class="nav-link" href="documentManagement/review&approve.php"><ion-icon name="checkmark-done-outline"></ion-icon>Review & Approve</a>
              <a class="nav-link" href="documentManagement/countersign.php"><ion-icon name="pencil-outline"></ion-icon>Countersign</a>
              <a class="nav-link" href="documentManagement/release.php"><ion-icon name="cloud-upload-outline"></ion-icon>Release</a>
              <a class="nav-link" href="documentManagement/archive.php"><ion-icon name="archive-outline"></ion-icon>Archive</a>
              <a class="nav-link" href="documentManagement/trash.php"><ion-icon name="trash-outline"></ion-icon>Trash</a>
            </nav>
          </div>
        </div>

        <!-- Logout -->
        <div class="p-3 border-top">
          <a class="nav-link text-danger" href="/Administrative/login.php">
            <ion-icon name="log-out-outline"></ion-icon>Logout
          </a>
        </div>
      </div>
    </div>
<!-- Main Content Column -->
    <div class="col-md-10 p-4">
      <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
        <div style="font-family:'Montserrat',sans-serif;font-size:2rem;font-weight:700;color:#22223b;">Access Control</div>
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <input type="text" class="form-control" placeholder="Search users...">
          </div>
          <div class="col-md-3">
            <select class="form-select">
              <option>All Roles</option>
              <option>Super Admin</option>
              <option>Legal Staff</option>
              <option>Facility Manager</option>
            </select>
          </div>

<!-- ADD USER MODAL -->
<div class="container mt-5">
  <div class="text-end">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
      <i class="bi bi-person-plus"></i> Add User
    </button>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action=""> <!-- same page -->
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" required>
              <option value="">Select Role</option>
              <option value="Super Admin">Super Admin</option>
              <option value="Facility Admin">Facility Admin</option>
              <option value="HR">HR</option>
              <option value="Payroll">Payroll</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Department</label>
            <select class="form-select" name="department" required>
              <option value="">Select Department</option>
              <option value="HR">HR</option>
              <option value="Payroll">Payroll</option>
              <option value="IT">IT</option>
              <option value="Logistics">Logistics</option>
              <option value="Security">Security</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" required>
              <option value="">Select Status</option>
              <option value="Active">Active</option>
              <option value="Pending">Pending</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" name="submit" class="btn btn-success">Add User</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- ADD USER MODAL -->



        </div>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Department</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>

  <?php 
	
	$sql="select * from users";
	$result=mysqli_query($conn,$sql);
	
	$i=1;
	while($data=mysqli_fetch_array($result))
	{?>
             
              <tr>
                <td>
                  <span class="d-inline-flex align-items-center">
                    <span style="background:#fef9c3;border-radius:50%;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;margin-right:8px;">
                      <i class="bi bi-person-badge" style="color:#eab308;font-size:1.3rem;"></i>
                    </span>
                    <strong><?php echo $data['username'];?></strong>
                  </span>
                </td>
                <td><?php echo $data['password'];?></td>
                <td><span class="badge rounded-pill" style="background:#fef9c3;color:#eab308;"><?php echo $data['email'];?></span></td>
                <td><?php echo $data['role'];?></td>
                <td><span class="text-danger"><i class="bi bi-lock"></i><?php echo $data['status'];?></span></td>
                <td><?php echo $data['department'];?></td>
                <td>


                   <a href="#" 
                    class="text-primary me-2 editBtn"
                    data-bs-toggle="modal" 
                    data-bs-target="#editUserModal"
                    data-user_id="<?= $data['user_id'] ?>"
                    data-username="<?= $data['username'] ?>"
                    data-password="<?= $data['password'] ?>"
                    data-email="<?= $data['email'] ?>"
                    data-role="<?= $data['role'] ?>"
                    data-department="<?= $data['department'] ?>"
                    data-status="<?= $data['status'] ?>">
                    <i class="bi bi-pencil-square"></i>
                  </a>



                  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="update.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="user_id" id="edit_user_id">

          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" id="edit_username" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="text" name="password" id="edit_password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="edit_email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" id="edit_role" required>
              <option value="Super Admin">Super Admin</option>
              <option value="Facility Admin">Facility Admin</option>
              <option value="HR">HR</option>
              <option value="Payroll">Payroll</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Department</label>
            <select class="form-select" name="department" id="edit_department" required>
              <option value="HR">HR</option>
              <option value="Payroll">Payroll</option>
              <option value="IT">IT</option>
              <option value="Logistics">Logistics</option>
              <option value="Security">Security</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="edit_status" required>
              <option value="Active">Active</option>
              <option value="Pending">Pending</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" name="update" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>





                  
                  <a href="delete.php?user_id=<?= $data['user_id'] ?>" 
                    class="text-danger" 
                    onclick="return confirm('Are you sure you want to delete this user?');">
                    <i class="bi bi-trash"></i>
                  </a>

            
                </td>
              </tr>
              
	<?php $i++; } ?>     
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div style="color:#6c757d;font-size:0.98rem;">Showing 1-3 of 24 users</div>
          <nav>
            <ul class="pagination mb-0">
              <li class="page-item"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav>
        </div>
      </div>
      <footer class="text-center py-3" style="color:#6c757d;font-size:0.98rem;">
        © 2025 Legal Admin
      </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.editBtn').forEach(button => {
  button.addEventListener('click', function () {
    document.getElementById('edit_user_id').value = this.dataset.user_id;
    document.getElementById('edit_username').value = this.dataset.username;
    document.getElementById('edit_password').value = this.dataset.password;
    document.getElementById('edit_email').value = this.dataset.email;
    document.getElementById('edit_role').value = this.dataset.role;
    document.getElementById('edit_department').value = this.dataset.department;
    document.getElementById('edit_status').value = this.dataset.status;
  });
});
</script>

</body>
</html>