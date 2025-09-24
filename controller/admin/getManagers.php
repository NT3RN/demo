<?php
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit();
}

require_once '../../model/database.php';

header('Content-Type: application/json');

$sql = "SELECT u.user_id, u.username, u.email, u.security_question, u.security_answer, m.salary, u.created_at 
        FROM Users u 
        INNER JOIN Managers m ON u.user_id = m.manager_id 
        ORDER BY u.created_at DESC";

$result = $conn->query($sql);

$managers = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $managers[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $managers]);
$conn->close();
?>