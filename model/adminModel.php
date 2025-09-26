<?php
require_once("dbConnect.php");

function getAllAdmins() {
    $conn = getConnect();
    $sql = "SELECT u.user_id, u.username, u.email, u.created_at 
            FROM Users u
            INNER JOIN Admins a ON u.user_id = a.admin_id 
            ORDER BY u.created_at DESC";
    $result = mysqli_query($conn, $sql);
    
    $admins = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $admins[] = $row;
    }
    
    mysqli_close($conn);
    return $admins;
}

function addAdmin($username, $email, $password, $security_question, $security_answer) {
    $conn = getConnect();
    
    $role = 'Admin';
    
    $sql = "INSERT INTO Users (username, email, password, role, security_question, security_answer) 
            VALUES ('$username', '$email', '$password', '$role', '$security_question', '$security_answer')";
    
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    
    return $result;
}

function checkEmailExists($email) {
    $conn = getConnect();
    
    $sql = "SELECT * FROM Users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    $exists = mysqli_num_rows($result) > 0;
    mysqli_close($conn);
    
    return $exists;
}

function deleteAdmin($user_id) {
    $conn = getConnect();
    
    $sql = "DELETE FROM Users WHERE user_id='$user_id' AND role='Admin'";
    $result = mysqli_query($conn, $sql);
    
    $affected = mysqli_affected_rows($conn);
    mysqli_close($conn);
    
    return $affected > 0;
}
?>