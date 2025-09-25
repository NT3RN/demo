<?php 
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbName = "SmartCafe";

function getConnect() {
    global $servername, $dbusername, $dbpassword, $dbName;
    $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbName);
    
    return $conn;
}
?>