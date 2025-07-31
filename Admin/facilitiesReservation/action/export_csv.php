<?php
include 'connection.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=reservation_history.csv');

$output = fopen("php://output", "w");

// Column headers
fputcsv($output, ['Facility Name', 'Requester', 'Status', 'Purpose', 'Check-In', 'Check-Out']);

// Query to fetch reservation data
$sql = "SELECT 
          rr.facility_name,
          v.fullname AS visitor_name,
          rr.status,
          rr.purpose,
          vl.check_in,
          vl.check_out
        FROM 
          reservation_requests rr
        LEFT JOIN 
          visitors v ON rr.id = v.reservation_id
        LEFT JOIN 
          visit_log vl ON v.id = vl.visitor_id";

$result = $con->query($sql);

// Output rows
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    fputcsv($output, [
      $row['facility_name'],
      $row['visitor_name'],
      $row['status'],
      $row['purpose'],
      $row['check_in'],
      $row['check_out']
    ]);
  }
} else {
  fputcsv($output, ['No records found']);
}

fclose($output);
exit;
?>
