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
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $security_question = $conn->real_escape_string($_POST['security_question']);
    $security_answer = $conn->real_escape_string($_POST['security_answer']);
    $salary = floatval($_POST['salary']);
    
    // Check if user already exists
    $check_sql = "SELECT user_id FROM Users WHERE email = '$email' OR username = '$username'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'User with this email or username already exists.']);
        exit();
    }
    
    // Insert into Users table
    $user_sql = "INSERT INTO Users (username, email, password, role, security_question, security_answer) 
                 VALUES ('$username', '$email', '$password', 'Manager', '$security_question', '$security_answer')";
    
    if ($conn->query($user_sql)) {
        $user_id = $conn->insert_id;
        
        // Insert into Managers table
        $manager_sql = "INSERT INTO Managers (manager_id, salary) VALUES ($user_id, $salary)";
        
        if ($conn->query($manager_sql)) {
            echo json_encode(['success' => true, 'message' => 'Manager added successfully.']);
        } else {
            // Rollback user insertion if manager insertion fails
            $conn->query("DELETE FROM Users WHERE user_id = $user_id");
            echo json_encode(['success' => false, 'message' => 'Failed to add manager: ' . $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add user: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>