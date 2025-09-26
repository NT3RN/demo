<?php
session_start();
if (!isset($_SESSION["email"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location:../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SmartCafe Admin Dashboard</title>
    <link rel="stylesheet" href="../css/adminDashboard.css"/>

</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <img src="../../assets/logo.png" alt="SmartCafe Logo" class="logo">
                <span>Smart Cafe Admin</span>
            </div>
            <nav>
                <ul>
                    <li><a href="#" id="adminTab">Admins</a></li>
                    <li><a href="#" id="managerTab">Managers</a></li>
                    <li><a href="#" id="customerTab">Customers</a></li>
                    <li><a href="#" id="profileTab">My Profile</a></li>
                    <li><button id="logoutBtn">Logout</button></li>
                </ul>
            </nav>
        </aside>
        <main class="admin-main">

            <section id="adminSection" class="admin-section">
                <h2>Admin Management</h2>
                <form id="addAdminForm" class="user-form" novalidate>
                    <input type="text" name="username" placeholder="Username" required>
                    <span class="error-message" id="admin-username-error"></span>
                    <input type="email" name="email" placeholder="Email" required>
                    <span class="error-message" id="admin-email-error"></span>
                    <input type="password" name="password" placeholder="Password" required>
                    <span class="error-message" id="admin-password-error"></span>
                    <select name="security_question" required>
                        <option value="">-- Select Security Question --</option>
                        <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                    </select>
                    <span class="error-message" id="admin-sq-error"></span>
                    <input type="text" name="security_answer" placeholder="Security Answer" required>
                    <span class="error-message" id="admin-sa-error"></span>
                    <button type="submit">Add Admin</button>
                </form>
                <div id="adminTableContainer" class="table-container">
                    <p>Loading Admins </p>
                </div>
            </section>

            <section id="managerSection" class="admin-section" style="display:none;">
                <h2>Manager Management</h2>
                <form id="addManagerForm" class="user-form" novalidate>
                    <input type="text" name="username" placeholder="Username" required>
                    <span class="error-message" id="manager-username-error"></span>
                    <input type="email" name="email" placeholder="Email" required>
                    <span class="error-message" id="manager-email-error"></span>
                    <input type="password" name="password" placeholder="Password" required>
                    <span class="error-message" id="manager-password-error"></span>
                    <select name="security_question" required>
                        <option value="">-- Select Security Question --</option>
                        <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                    </select>
                    <span class="error-message" id="manager-sq-error"></span>
                    <input type="text" name="security_answer" placeholder="Security Answer" required>
                    <span class="error-message" id="manager-sa-error"></span>
                    <input type="number" name="salary" placeholder="Salary" step="0.01" required>
                    <span class="error-message" id="manager-salary-error"></span>
                    <button type="submit">Add Manager</button>
                </form>
                <div id="managerTableContainer" class="table-container">
                    <p>Loading Manager</p>
                </div>
            </section>

            <section id="customerSection" class="admin-section" style="display:none;">
                <h2>Customer Management</h2>
                <form id="addCustomerForm" class="user-form" novalidate>
                    <input type="text" name="username" placeholder="Username" required>
                    <span class="error-message" id="customer-username-error"></span>
                    <input type="email" name="email" placeholder="Email" required>
                    <span class="error-message" id="customer-email-error"></span>
                    <input type="password" name="password" placeholder="Password" required>
                    <span class="error-message" id="customer-password-error"></span>
                    <select name="security_question" required>
                        <option value="">-- Select Security Question --</option>
                        <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                        <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                    </select>
                    <span class="error-message" id="customer-sq-error"></span>
                    <input type="text" name="security_answer" placeholder="Security Answer" required>
                    <span class="error-message" id="customer-sa-error"></span>
                    <button type="submit">Add Customer</button>
                </form>
                <div id="customerTableContainer" class="table-container">
                    <p>Loading Customers </p>
                </div>
            </section>

            <section id="profileSection" class="admin-section" style="display:none;">
                <h2>My Profile</h2>
                <div id="profileContainer" class="profile-container"></div>
            </section>
        </main>
    </div>
    <script src="../js/adminDashboard.js"></script>
</body>
</html>