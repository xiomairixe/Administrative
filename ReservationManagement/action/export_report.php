<?php
include("connection.php");

// Set headers to force CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reservation_report.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, ['Facility', 'Reserved By', 'Date Created', 'Status']);

// Fetch data from database
$sql = "SELECT facility_name, created_by, created_at, status FROM reservation_requests";
$result = $con->query($sql) or die($con->error);

// Output each row of the data, format as CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['facility_name'],
        $row['created_by'],
        $row['created_at'],
        $row['status']
    ]);
}

fclose($output);
exit;
?>
