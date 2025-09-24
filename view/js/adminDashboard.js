document.addEventListener('DOMContentLoaded', function() {
    initDashboard();
});

function initDashboard() {
    setupTabNavigation();
    setupFormHandlers();
    setupLogout();
    loadAdmins();
}

function setupTabNavigation() {
    let tabs = {
        adminTab: 'adminSection',
        managerTab: 'managerSection', 
        customerTab: 'customerSection',
        profileTab: 'profileSection',
        salesTab: 'salesSection'
    };

    for (let [tabId, sectionId] of Object.entries(tabs)) {
        let tabElement = document.getElementById(tabId);
        if (tabElement) {
            tabElement.addEventListener('click', function(e) {
                e.preventDefault();
                switchTab(sectionId);
            });
        }
    }
}

function switchTab(sectionId) {
    let sections = document.querySelectorAll('.admin-section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    let activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.style.display = 'block';
        
        switch(sectionId) {
            case 'adminSection':
                loadAdmins();
                break;
            case 'managerSection':
                loadManagers();
                break;
            case 'customerSection':
                loadCustomers();
                break;
            case 'profileSection':
                loadProfile();
                break;
            case 'salesSection':
                loadSalesReport();
                break;
        }
    }
}
function setupFormHandlers() {
    let addAdminForm = document.getElementById('addAdminForm');
    if (addAdminForm) {
        addAdminForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addUser('admin');
        });
    }

    let addManagerForm = document.getElementById('addManagerForm');
    if (addManagerForm) {
        addManagerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addUser('manager');
        });
    }

    let addCustomerForm = document.getElementById('addCustomerForm');
    if (addCustomerForm) {
        addCustomerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addUser('customer');
        });
    }
}

// User Management Functions
function loadAdmins() {
    sendAjaxRequest('../../controller/admin/getAdmins.php', 'GET', null, function(response) {
        displayUsers(response.data, 'adminTableContainer', 'admin');
    });
}

function loadManagers() {
    sendAjaxRequest('../../controller/admin/getManagers.php', 'GET', null, function(response) {
        displayUsers(response.data, 'managerTableContainer', 'manager');
    });
}

function loadCustomers() {
    sendAjaxRequest('../../controller/admin/getCustomers.php', 'GET', null, function(response) {
        displayUsers(response.data, 'customerTableContainer', 'customer');
    });
}

function displayUsers(users, containerId, userType) {
    let container = document.getElementById(containerId);
    if (!container) return;

    if (!users || users.length === 0) {
        container.innerHTML = '<p class="no-data">No ' + userType + 's found</p>';
        return;
    }

    let isLastAdmin = userType === 'admin' && users.length <= 1;

    let html = `
        <table class="data-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    ${userType === 'manager' ? '<th>Salary</th>' : ''}
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;

    users.forEach(user => {
        let isCurrentAdmin = user.user_id == currentAdminId;
        let deleteButton = '';
        
        if (userType === 'admin') {
            if (!isLastAdmin && !isCurrentAdmin) {
                deleteButton = `<button class="btn-delete" onclick="deleteUser(${user.user_id}, 'admin')">Delete</button>`;
            } else {
                deleteButton = '<span class="no-delete">Cannot delete</span>';
            }
        } else {
            deleteButton = `<button class="btn-delete" onclick="deleteUser(${user.user_id}, '${userType}')">Delete</button>`;
        }

        html += `
            <tr>
                <td>${user.user_id}</td>
                <td>${escapeHtml(user.username)}</td>
                <td>${escapeHtml(user.email)}</td>
                ${userType === 'manager' ? `<td>$${parseFloat(user.salary || 0).toFixed(2)}</td>` : ''}
                <td>${formatDate(user.created_at)}</td>
                <td>
                    <button class="btn-edit" onclick="editUser(${user.user_id}, '${userType}')">Edit</button>
                    ${deleteButton}
                </td>
            </tr>
        `;
    });

    html += '</tbody></table>';
    container.innerHTML = html;
}

function addUser(userType) {
    let form = document.getElementById('add' + capitalizeFirst(userType) + 'Form');
    let formData = new FormData(form);

    sendAjaxRequest('../../controller/admin/add' + capitalizeFirst(userType) + '.php', 'POST', formData, function(response) {
        showMessage(response.message, response.success ? 'success' : 'error');
        if (response.success) {
            form.reset();
            // Reload the current section
            let activeSection = document.querySelector('.admin-section[style="display: block;"]');
            if (activeSection) {
                switchTab(activeSection.id.replace('Section', ''));
            }
        }
    });
}

function editUser(userId, userType) {
    sendAjaxRequest('../../controller/admin/getUser.php?user_id=' + userId + '&user_type=' + userType, 'GET', null, function(response) {
        showEditForm(response.data, userType);
    });
}

function showEditForm(user, userType) {
    let container = document.getElementById(userType + 'TableContainer');
    let formHtml = `
        <div class="edit-form">
            <h3>Edit ${capitalizeFirst(userType)}</h3>
            <form id="edit${capitalizeFirst(userType)}Form">
                <input type="hidden" name="user_id" value="${user.user_id}">
                <input type="hidden" name="user_type" value="${userType}">
                <input type="text" name="username" value="${escapeHtml(user.username)}" placeholder="Username" required>
                <input type="email" name="email" value="${escapeHtml(user.email)}" placeholder="Email" required>
                ${userType === 'manager' ? `<input type="number" name="salary" value="${user.salary}" placeholder="Salary" step="0.01" required>` : ''}
                <select name="security_question" required>
                    <option value="What is your mother's maiden name?" ${user.security_question === "What is your mother's maiden name?" ? 'selected' : ''}>What is your mother's maiden name?</option>
                    <option value="What was the name of your first pet?" ${user.security_question === "What was the name of your first pet?" ? 'selected' : ''}>What was the name of your first pet?</option>
                    <option value="What city were you born in?" ${user.security_question === "What city were you born in?" ? 'selected' : ''}>What city were you born in?</option>
                </select>
                <input type="text" name="security_answer" value="${escapeHtml(user.security_answer)}" placeholder="Security Answer" required>
                <button type="submit">Update ${capitalizeFirst(userType)}</button>
                <button type="button" onclick="cancelEdit('${userType}')">Cancel</button>
            </form>
        </div>
    `;
    
    container.innerHTML = formHtml + container.innerHTML;
    
    let editForm = document.getElementById('edit' + capitalizeFirst(userType) + 'Form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            updateUser(userType);
        });
    }
}

function updateUser(userType) {
    let form = document.getElementById('edit' + capitalizeFirst(userType) + 'Form');
    let formData = new FormData(form);

    sendAjaxRequest('../../controller/admin/updateUser.php', 'POST', formData, function(response) {
        showMessage(response.message, response.success ? 'success' : 'error');
        if (response.success) {
            // Reload the current section
            let activeSection = document.querySelector('.admin-section[style="display: block;"]');
            if (activeSection) {
                switchTab(activeSection.id.replace('Section', ''));
            }
        }
    });
}

function cancelEdit(userType) {
    switchTab(userType);
}

function deleteUser(userId, userType) {
    if (!confirm(`Are you sure you want to delete this ${userType}? This action cannot be undone.`)) {
        return;
    }

    let formData = new FormData();
    formData.append('user_id', userId);
    formData.append('user_type', userType);

    sendAjaxRequest('../../controller/admin/deleteUser.php', 'POST', formData, function(response) {
        showMessage(response.message, response.success ? 'success' : 'error');
        if (response.success) {
            let activeSection = document.querySelector('.admin-section[style="display: block;"]');
            if (activeSection) {
                switchTab(activeSection.id.replace('Section', ''));
            }
        }
    });
}

// Profile Functions
function loadProfile() {
    sendAjaxRequest('../../controller/admin/getProfile.php', 'GET', null, function(response) {
        displayProfileForm(response.data);
    });
}

function displayProfileForm(profile) {
    let container = document.getElementById('profileContainer');
    if (!container) return;

    container.innerHTML = `
        <div class="profile-form">
            <form id="profileForm">
                <div class="form-group">
                    <label>User ID:</label>
                    <span>${profile.user_id}</span>
                </div>
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" value="${escapeHtml(profile.username)}" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="${escapeHtml(profile.email)}" required>
                </div>
                <div class="form-group">
                    <label>Security Question:</label>
                    <select name="security_question" required>
                        <option value="What is your mother's maiden name?" ${profile.security_question === "What is your mother's maiden name?" ? 'selected' : ''}>What is your mother's maiden name?</option>
                        <option value="What was the name of your first pet?" ${profile.security_question === "What was the name of your first pet?" ? 'selected' : ''}>What was the name of your first pet?</option>
                        <option value="What city were you born in?" ${profile.security_question === "What city were you born in?" ? 'selected' : ''}>What city were you born in?</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Security Answer:</label>
                    <input type="text" name="security_answer" value="${escapeHtml(profile.security_answer)}" required>
                </div>
                <div class="form-group">
                    <label>New Password (leave blank to keep current):</label>
                    <input type="password" name="new_password" placeholder="New password">
                </div>
                <div class="form-group">
                    <label>Confirm New Password:</label>
                    <input type="password" name="confirm_password" placeholder="Confirm new password">
                </div>
                <button type="submit">Update Profile</button>
            </form>
        </div>
    `;

    let profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            updateProfile();
        });
    }
}

function updateProfile() {
    let form = document.getElementById('profileForm');
    let formData = new FormData(form);

    sendAjaxRequest('../../controller/admin/updateProfile.php', 'POST', formData, function(response) {
        showMessage(response.message, response.success ? 'success' : 'error');
        if (response.success) {
            loadProfile();
        }
    });
}

// Sales Report Functions
function loadSalesReport() {
    sendAjaxRequest('../../controller/admin/getSalesReport.php', 'GET', null, function(response) {
        displaySalesReport(response.data);
    });
}

function displaySalesReport(salesData) {
    let container = document.getElementById('salesReportContainer');
    if (!container) return;

    let html = `
        <div class="sales-summary">
            <div class="sales-card">
                <h3>Total Revenue</h3>
                <p class="sales-amount">$${parseFloat(salesData.totalRevenue || 0).toFixed(2)}</p>
            </div>
            <div class="sales-card">
                <h3>Total Orders</h3>
                <p class="sales-amount">${salesData.totalOrders || 0}</p>
            </div>
            <div class="sales-card">
                <h3>Average Order Value</h3>
                <p class="sales-amount">$${parseFloat(salesData.averageOrderValue || 0).toFixed(2)}</p>
            </div>
        </div>
    `;

    if (salesData.recentOrders && salesData.recentOrders.length > 0) {
        html += `
            <div class="recent-orders">
                <h3>Recent Orders</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        salesData.recentOrders.forEach(order => {
            html += `
                <tr>
                    <td>#${order.order_id}</td>
                    <td>${escapeHtml(order.customer_name)}</td>
                    <td>$${parseFloat(order.amount).toFixed(2)}</td>
                    <td>${formatDate(order.order_date)}</td>
                    <td><span class="status ${order.status.toLowerCase()}">${order.status}</span></td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
    }

    container.innerHTML = html;
}

function sendAjaxRequest(url, method, data, callback) {
    let xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    
    xhr.onload = function() {
        if (this.status === 200) {
            try {
                let response = JSON.parse(this.responseText);
                callback(response);
            } catch (e) {
                showMessage('Error parsing response: ' + e.message, 'error');
            }
        } else {
            showMessage('Request failed: ' + this.statusText, 'error');
        }
    };
    
    xhr.onerror = function() {
        showMessage('Network error occurred', 'error');
    };
    
    if (data instanceof FormData) {
        xhr.send(data);
    } else {
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(data);
    }
}

function showMessage(message, type) {
    let existingMessages = document.querySelectorAll('.message');
    existingMessages.forEach(msg => msg.remove());

    let messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}`;
    messageDiv.textContent = message;
    document.body.appendChild(messageDiv);

    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.parentNode.removeChild(messageDiv);
        }
    }, 5000);
}

function escapeHtml(text) {
    if (!text) return '';
    let div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    let date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}

function capitalizeFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Logout Functionality
function setupLogout() {
    let logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        location.href = '../../controller/logout.php';
    }
}