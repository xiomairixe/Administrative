<?php
// filepath: c:\xampp\htdocs\Administrative\LegalManagement\LegalAdmin\send_to_legal_officer.php
include('../connection.php');
$request_id = $_POST['request_id'] ?? null;
if ($request_id) {
    $req = $conn->query("SELECT * FROM legal_requests WHERE request_id = $request_id")->fetch_assoc();
    if ($req) {
        $exists = $conn->query("SELECT * FROM cases WHERE request_id = $request_id")->num_rows;
        if (!$exists) {
            $officer = $conn->query("SELECT user_id FROM users WHERE role='Legal Officer' LIMIT 1")->fetch_assoc();
            $assigned_to = $officer ? $officer['user_id'] : null;
            $sql = "INSERT INTO cases (user_id, name, client, status, assigned_to, start_date, request_id)
                    VALUES (
                        '{$req['user_id']}',
                        '{$conn->real_escape_string($req['title'])}',
                        '{$conn->real_escape_string($req['stakeholders'])}',
                        'Active',
                        '$assigned_to',
                        CURDATE(),
                        '$request_id'
                    )";
            if (!$conn->query($sql)) {
                echo json_encode(['success' => false, 'error' => $conn->error]);
                exit;
            }
        }
        if (!$conn->query("UPDATE legal_requests SET status='In Review' WHERE request_id=$request_id")) {
            echo json_encode(['success' => false, 'error' => $conn->error]);
            exit;
        }
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Legal request not found.']);
        exit;
    }
}
echo json_encode(['success' => false, 'error' => 'Invalid request ID.']);
?>