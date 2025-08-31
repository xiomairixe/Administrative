<?php
include '../../connection.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=event_history_' . date('Ymd_His') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, [
    'Date', 'Host', 'Department', 'Facility', 'Purpose',
    'Expected', 'Actual', 'Condition', 'Incident', 'Feedback'
]);

$res = $conn->query("SELECT * FROM reservation_event_report ORDER BY reservation_date DESC, reservation_start DESC");
while ($row = $res->fetch_assoc()) {
    fputcsv($output, [
        $row['reservation_date'] . ' ' . $row['reservation_start'] . '-' . $row['reservation_end'],
        $row['host_name'],
        $row['department'],
        $row['facility_reserved'],
        $row['purpose'],
        $row['expected_attendees'],
        $row['actual_attendees'],
        $row['facility_condition'],
        $row['incident_report'],
        $row['feedback']
    ]);
}
fclose($output);
exit;
?>