<?php
include('connection.php');

if (isset($_POST['update'])) {
  $user_id   = intval($_POST['user_id']);
  $username  = $_POST['username'];
  $password  = $_POST['password'];
  $email     = $_POST['email'];
  $role      = $_POST['role'];
  $department = $_POST['department'];
  $status    = $_POST['status'];

  $sql = "UPDATE users SET 
          username = '$username',
          password = '$password',
          email = '$email',
          role = '$role',
          department = '$department',
          status = '$status'
          WHERE user_id = $user_id";

  if (mysqli_query($conn, $sql)) {
    header("Location: accessControl.php?updated=1");
    exit();
  } else {
    echo "Error updating record: " . mysqli_error($conn);
  }
}
?>