document.addEventListener('DOMContentLoaded', function() {
    initDashboard();
});

function initDashboard() {
    setupTabNavigation();
    setupFormValidation();
    setupLogout();
}

function setupTabNavigation() {
    let adminTab = document.getElementById("adminTab");
    if (adminTab) {
        adminTab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('adminSection');
        });
    }

    let managerTab = document.getElementById("managerTab");
    if (managerTab) {
        managerTab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('managerSection');
        });
    }

    let customerTab = document.getElementById("customerTab");
    if (customerTab) {
        customerTab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('customerSection');
        });
    }

    let profileTab = document.getElementById("profileTab");
    if (profileTab) {
        profileTab.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('profileSection');
        });
    }
}

function switchTab(sectionId) {
    document.querySelectorAll('.admin-section').forEach(s => s.style.display = 'none');
    let active = document.getElementById(sectionId);
    if (!active) return;
    active.style.display = 'block';
    
    if (sectionId === 'adminSection'){
        loadAdmins();
    } 
    else if (sectionId === 'managerSection'){
        // Future: loadManagers();
    } 
    else if (sectionId === 'customerSection') {
        // Future: loadCustomers();
    }
    else if (sectionId === 'profileSection') {
        // Future: loadProfile();
    }
}

function setupFormValidation() {
    let adminForm = document.getElementById('addAdminForm');
    if (adminForm) {
        adminForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (basicUserFormCheck(this, false)) {
                addAdmin(this);
            }
        });
    }
    
    let managerForm = document.getElementById('addManagerForm');
    if (managerForm) {
        managerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (basicUserFormCheck(this, true)) {
                // Future: addManager(this);
            }
        });
    }
    
    let customerForm = document.getElementById('addCustomerForm');
    if (customerForm) {
        customerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (basicUserFormCheck(this, false)) {
                // Future: addCustomer(this);
            }
        });
    }
}

function basicUserFormCheck(form, isManager) {
    let valid = true;
    
    form.querySelectorAll('.error-message').forEach(span => span.textContent = '');

    let username = form.querySelector('input[name="username"]');
    let email = form.querySelector('input[name="email"]');
    let password = form.querySelector('input[name="password"]');
    let securityQuestion = form.querySelector('select[name="security_question"]');
    let securityAnswer = form.querySelector('input[name="security_answer"]');

    let formType = 'admin';
    if (form.id === 'addManagerForm') formType = 'manager';
    else if (form.id === 'addCustomerForm') formType = 'customer';

    if (!/^[A-Za-z0-9_]{4,}$/.test(username.value.trim())) {
        document.getElementById(`${formType}-username-error`).textContent =
            'Username must be at least 4 characters, only letters, numbers, underscores, and no spaces';
        valid = false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        document.getElementById(`${formType}-email-error`).textContent =
            'Enter a valid email address';
        valid = false;
    }

    if (!/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(password.value.trim())) {
        document.getElementById(`${formType}-password-error`).textContent =
            'Password must be at least 8 characters and include at least one letter and one number';
        valid = false;
    }

    if (!securityQuestion.value.trim()) {
        document.getElementById(`${formType}-sq-error`).textContent =
            'Please select a security question';
        valid = false;
    }

    if (!securityAnswer.value.trim()) {
        document.getElementById(`${formType}-sa-error`).textContent =
            'Security answer cannot be empty';
        valid = false;
    }

    return valid;
}

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

function loadAdmins() {
    fetch('../../controller/adminController.php')
        .then(response => response.json())
        .then(admins => {
            displayAdmins(admins);
        })
        .catch(error => {
            console.error('Error loading admins:', error);
            document.getElementById('adminTableContainer').innerHTML = 
                '<p style="color: red;">Error loading admins</p>';
        });
}

function displayAdmins(admins) {
    let html = '';
    
    if (admins.length === 0) {
        html = '<p>No admins found</p>';
    } else {
        html = `
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        admins.forEach(admin => {
            html += `
                <tr>
                    <td>${admin.user_id}</td>
                    <td>${admin.username}</td>
                    <td>${admin.email}</td>
                    <td>${admin.created_at}</td>
                    <td>
                        <button class="btn-delete" onclick="deleteAdmin(${admin.user_id})">Delete</button>
                    </td>
                </tr>
            `;
        });
        
        html += `
                </tbody>
            </table>
        `;
    }
    
    document.getElementById('adminTableContainer').innerHTML = html;
}

function addAdmin(form) {
    let formData = new FormData(form);
    
    fetch('../../controller/adminController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            form.reset();
            form.querySelectorAll('.error-message').forEach(span => span.textContent = '');
            loadAdmins();
            alert('Admin added successfully');
        } else {
            alert(data.error || 'Failed to add admin');
        }
    })
    .catch(error => {
        console.error('Error adding admin:', error);
        alert('Error adding admin');
    });
}

function deleteAdmin(userId) {
    if (!confirm('Are you sure you want to delete this admin?')) {
        return;
    }
    
    fetch('../../controller/adminController.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadAdmins();
            alert('Admin deleted successfully');
        } else {
            alert(data.error || 'Failed to delete admin');
        }
    })
    .catch(error => {
        console.error('Error deleting admin:', error);
        alert('Error deleting admin');
    });
}