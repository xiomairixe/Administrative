<?php
    $host = "localhost:3307";
    $user = "root";
    $password ="";
    $database = "tnvs";


    $conn = mysqli_connect($host, $user, $password, $database);

    if(!$conn){
        die("Connection Failed: " . mysqli_connect_error());
    }
?>