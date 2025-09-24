<?php
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit();
}

require_once '../../model/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $user_type = $conn->real_escape_string($_POST['user_type']);
    
    if ($user_type === 'admin') {
        $count_sql = "SELECT COUNT(*) as admin_count FROM Admins";
        $count_result = $conn->query($count_sql);
        $count_row = $count_result->fetch_assoc();
        
        if ($count_row['admin_count'] <= 1) {
            echo json_encode(['success' => false, 'message' => 'Cannot delete the last admin.']);
            exit();
        }
    }
    
    $role_table = ucfirst($user_type) . 's';
    $role_id_field = $user_type . '_id';
    
    $delete_sql = "DELETE FROM $role_table WHERE $role_id_field = $user_id";
    
    if ($conn->query($delete_sql)) {
        echo json_encode(['success' => true, 'message' => ucfirst($user_type) . ' deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete user: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>