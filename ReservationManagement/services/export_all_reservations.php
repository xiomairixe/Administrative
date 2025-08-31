<?php
include '../../connection.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=all_reservations_' . date('Ymd_His') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, [
    'Reservation ID', 'Requested At', 'Customer', 'Facility', 'Status', 'Reservation Date', 'Start Time', 'End Time', 'Purpose'
]);

$res = $conn->query("SELECT r.request_id, r.requested_at, v.full_name AS customer, f.facility_name AS facility, r.status, r.reservation_date, r.start_time, r.end_time, r.purpose
  FROM reservation_requests r
  LEFT JOIN visitors v ON v.visitor_id = r.request_id AND v.is_head = 1
  LEFT JOIN facilities f ON f.facility_id = r.facility_id
  ORDER BY r.requested_at DESC");

while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['request_id'],
        $row['requested_at'],
        $row['customer'],
        $row['facility'],
        $row['status'],
        $row['reservation_date'],
        $row['start_time'],
        $row['end_time'],
        $row['purpose']
    ]);
}
fclose($output);
exit;
?>