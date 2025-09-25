<?php
// filepath: c:\xampp\htdocs\demo\test_admin.php
require_once("model/adminModel.php");

// Test adding admin
$result = addAdmin('testuser', 'test@example.com', 'password123', 'What is your mother\'s maiden name?', 'Smith');

if ($result) {
    echo "Admin added successfully!<br>";
} else {
    echo "Failed to add admin!<br>";
}

// Test getting all admins
$admins = getAllAdmins();
echo "Total admins: " . count($admins) . "<br>";
foreach ($admins as $admin) {
    echo "Admin: " . $admin['username'] . " - " . $admin['email'] . "<br>";
}
?>