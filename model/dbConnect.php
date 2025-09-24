<?php 
$servername = "localhost";
$username = "root";
$dbpassword = "";
$dbName = "SmartCafe";

function getConnect() {
    global $servername, $username, $dbpassword, $dbName;
    $conn = mysqli_connect($servername, $username, $dbpassword, $dbName);
    
    return $conn;
}
?>