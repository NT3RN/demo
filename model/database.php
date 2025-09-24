<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smartcafe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to execute safe queries
function executeQuery($sql, $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }
    $result = $stmt->execute();
    if ($result) {
        return $stmt;
    }
    return false;
}
?>