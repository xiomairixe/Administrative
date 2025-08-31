<?php
require '../connection.php';
$request_id = intval($_GET['request_id']);
$data = [];
$sql = "SELECT * FROM legal_requests WHERE request_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $data = $row;
    // Get documents linked to this request
    $docs = [];
    $doc_sql = "SELECT d.file_name, d.file_path FROM legal_request_documents lrd
                JOIN document d ON lrd.document_id = d.document_id
                WHERE lrd.request_id = ?";
    $doc_stmt = $conn->prepare($doc_sql);
    $doc_stmt->bind_param("i", $request_id);
    $doc_stmt->execute();
    $doc_result = $doc_stmt->get_result();
    while ($doc = $doc_result->fetch_assoc()) {
        $docs[] = $doc;
    }
    $doc_stmt->close();
    $data['documents'] = $docs;
}
$stmt->close();
header('Content-Type: application/json');
echo json_encode($data);
?>