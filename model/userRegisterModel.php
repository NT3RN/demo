<?php
require_once("dbConnect.php");

function doseEmailExists($email) {
    $conn = getConnect();
    $sql  = "SELECT 1 FROM Users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $exists;
}

function insertUser($username, $email, $password, $role, $security_question, $security_answer) {
    $conn = getConnect();
    $sql  = "INSERT INTO Users (username, email, password, role, security_question, security_answer)
             VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $password, $role, $security_question, $security_answer);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $ok;
}
