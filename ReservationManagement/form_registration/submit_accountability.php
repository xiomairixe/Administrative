<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Host & Event Details
    $host_name = $_POST['host_name'];
    $department = $_POST['department'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $facility_reserved = $_POST['facility_reserved'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_start = $_POST['reservation_start'];
    $reservation_end = $_POST['reservation_end'];
    $purpose = $_POST['purpose'];

    // Attendance Tracking
    $expected_attendees = $_POST['expected_attendees'];
    $actual_attendees = $_POST['actual_attendees'];
    $attendees_list = $_POST['attendees_list'];
    $external_guests = $_POST['external_guests'];
    $no_show_list = $_POST['no_show_list'];
    // $qr_log = $_FILES['qr_log']; // File upload handling (optional)

    // Facility Usage & Feedback
    $equipment_used = $_POST['equipment_used'];
    $room_setup = $_POST['room_setup'];
    $issues_encountered = $_POST['issues_encountered'];
    $issue_description = $_POST['issue_description'];
    $facility_condition = $_POST['facility_condition'];
    $feedback = $_POST['feedback'];

    // Compliance & Handover
    $handover_confirmation = isset($_POST['handover_confirmation']) ? 1 : 0;
    $handover_time = $_POST['handover_time'];
    $handover_staff = $_POST['handover_staff'];
    $incident_report = $_POST['incident_report'];
    $incident_reference = $_POST['incident_reference'];
    $ack_terms = isset($_POST['ack_terms']) ? 1 : 0;
    $digital_signature = $_POST['digital_signature'];

    // Insert into reservation_event_report table
    $stmt = $conn->prepare("INSERT INTO reservation_event_report (
        host_name, department, contact_number, email, facility_reserved, reservation_date, reservation_start, reservation_end, purpose,
        expected_attendees, actual_attendees, attendees_list, external_guests, no_show_list,
        equipment_used, room_setup, issues_encountered, issue_description, facility_condition, feedback,
        handover_confirmation, handover_time, handover_staff, incident_report, incident_reference, ack_terms, digital_signature
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssssssssssssssssssssssi",
        $host_name, $department, $contact_number, $email, $facility_reserved, $reservation_date, $reservation_start, $reservation_end, $purpose,
        $expected_attendees, $actual_attendees, $attendees_list, $external_guests, $no_show_list,
        $equipment_used, $room_setup, $issues_encountered, $issue_description, $facility_condition, $feedback,
        $handover_confirmation, $handover_time, $handover_staff, $incident_report, $incident_reference, $ack_terms, $digital_signature
    );
    $stmt->execute();
    $stmt->close();

    echo '<script>alert("Accountability form submitted successfully!");window.location.href="receipt.php";</script>';
    exit;
}
?>