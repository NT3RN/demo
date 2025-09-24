<?php
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit();
}

require_once '../../model/database.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$sql = "SELECT user_id, username, email, security_question, security_answer, created_at 
        FROM Users WHERE user_id = $user_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
    echo json_encode(['success' => true, 'data' => $profile]);
} else {
    echo json_encode(['success' => false, 'message' => 'Profile not found.']);
}

$conn->close();
?>