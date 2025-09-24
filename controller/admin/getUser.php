<?php
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit();
}

require_once '../../model/database.php';

header('Content-Type: application/json');

if (isset($_GET['user_id']) && isset($_GET['user_type'])) {
    $user_id = intval($_GET['user_id']);
    $user_type = $conn->real_escape_string($_GET['user_type']);
    
    // Build query based on user type
    if ($user_type === 'admin') {
        $sql = "SELECT u.user_id, u.username, u.email, u.security_question, u.security_answer 
                FROM Users u 
                INNER JOIN Admins a ON u.user_id = a.admin_id 
                WHERE u.user_id = $user_id";
    } else if ($user_type === 'manager') {
        $sql = "SELECT u.user_id, u.username, u.email, u.security_question, u.security_answer, m.salary 
                FROM Users u 
                INNER JOIN Managers m ON u.user_id = m.manager_id 
                WHERE u.user_id = $user_id";
    } else if ($user_type === 'customer') {
        $sql = "SELECT u.user_id, u.username, u.email, u.security_question, u.security_answer 
                FROM Users u 
                INNER JOIN Customers c ON u.user_id = c.customer_id 
                WHERE u.user_id = $user_id";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid user type.']);
        exit();
    }
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
}

$conn->close();
?>