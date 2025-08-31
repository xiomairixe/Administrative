<?php
// filepath: c:\xampp\htdocs\Administrative\visitor-management\export_visitors_report.php
include('../connection.php');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=visitors_report_' . date('Ymd_His') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, [
    'Name', 'Company', 'Contact', 'Date & Time', 'Status', 'Host'
]);

$res = $conn->query("SELECT full_name, company, contact_number, visit_datetime, visit_status, host_name FROM visitors ORDER BY visit_datetime DESC");
while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['full_name'],
        $row['company'],
        $row['contact_number'],
        date('M d, Y H:i', strtotime($row['visit_datetime'])),
        $row['visit_status'],
        $row['host_name']
    ]);
}
fclose($output);
exit;
?>