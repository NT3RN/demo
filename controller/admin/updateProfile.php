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
    $user_id = $_SESSION['user_id'];
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $security_question = $conn->real_escape_string($_POST['security_question']);
    $security_answer = $conn->real_escape_string($_POST['security_answer']);
    
    // Check if email or username already exists for other users
    $check_sql = "SELECT user_id FROM Users WHERE (email = '$email' OR username = '$username') AND user_id != $user_id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email or username already exists for another user.']);
        exit();
    }
    
    // Handle password update if provided
    $password_update = "";
    if (!empty($_POST['new_password'])) {
        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
            exit();
        }
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $password_update = ", password = '$new_password'";
    }
    
    // Update Users table
    $user_sql = "UPDATE Users SET username = '$username', email = '$email', 
                 security_question = '$security_question', security_answer = '$security_answer'
                 $password_update WHERE user_id = $user_id";
    
    if ($conn->query($user_sql)) {
        // Update session username if it was changed
        $_SESSION['email'] = $email;
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>