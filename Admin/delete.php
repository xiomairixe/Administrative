<?php
include('connection.php');

if (isset($_GET['user_id'])) {
    $id = intval($_GET['user_id']);
    $sql = "DELETE FROM users WHERE user_id = $id"; 

    if (mysqli_query($conn, $sql)) {
        header("Location: accessControl.php?deleted=1"); 
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>