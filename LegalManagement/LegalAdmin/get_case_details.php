<?php
include('../connection.php');
$case_id = intval($_GET['case_id']);
$case = $conn->query("SELECT c.*, u.fullname AS assigned_to_name FROM cases c LEFT JOIN users u ON c.assigned_to = u.user_id WHERE c.case_id = $case_id")->fetch_assoc();
$docs = [];
$docRes = $conn->query("SELECT * FROM case_documents WHERE case_id = $case_id");
while ($d = $docRes->fetch_assoc()) $docs[] = $d;
$notes = [];
$noteRes = $conn->query("SELECT cn.*, u.fullname AS author FROM case_notes cn LEFT JOIN users u ON cn.user_id = u.user_id WHERE cn.case_id = $case_id ORDER BY cn.created_at ASC");
while ($n = $noteRes->fetch_assoc()) $notes[] = ['author'=>$n['author'],'comment'=>$n['note'],'date'=>$n['created_at']];
echo json_encode([
  'name' => $case['name'] ?? '',
  'client' => $case['client'] ?? '',
  'status' => $case['status'] ?? '',
  'assigned_to_name' => $case['assigned_to_name'] ?? '',
  'start_date' => $case['start_date'] ?? '',
  'documents' => $docs,
  'notes' => $notes
]);
?>