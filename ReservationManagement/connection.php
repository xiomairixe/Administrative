<?php
    $host = "localhost:3306";
    $user = "root";
    $password ="";
    $database = "Administrative";


    $conn = mysqli_connect($host, $user, $password, $database);

    if(!$conn){
        die("Connection Failed: " . mysqli_connect_error());
    }
?>