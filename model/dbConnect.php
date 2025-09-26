<?php 
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbName = "smartcafe";

function getConnect() {
    global $servername, $dbusername, $dbpassword, $dbName;
    $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbName);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}
?>