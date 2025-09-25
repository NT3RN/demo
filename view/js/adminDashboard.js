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
            console.log("Admin clicked");
            switchTab('adminSection');
        });
    }

    let managerTab = document.getElementById("managerTab");
    if (managerTab) {
        managerTab.addEventListener('click', function(e) {
            e.preventDefault();
            console.log("manager clicked");
            switchTab('managerSection');
        });
    }

    let customerTab = document.getElementById("customerTab");
    if (customerTab) {
        customerTab.addEventListener('click', function(e) {
            e.preventDefault();
            console.log("customer clicked");
            switchTab('customerSection');
        });
    }

    let profileTab = document.getElementById("profileTab");
    if (profileTab) {
        profileTab.addEventListener('click', function(e) {
            e.preventDefault();
            console.log("Profile clicked");
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
        //loadAdmins();
        console.log("Loading admins");
    } 
    else if (sectionId === 'managerSection'){
        //loadManagers();
        console.log("Loading managers");
    } 
    else if (sectionId === 'customerSection') {
        //loadCustomers();
        console.log("Loading customers");
    }
    else if (sectionId === 'profileSection') {
        //loadProfile();
        console.log("Loading profile");
    }
}

function setupFormValidation() {

    let adminForm = document.getElementById('addAdminForm');
    if (adminForm) {
        adminForm.addEventListener('submit', function(e) {
            if (!basicUserFormCheck(this, false)) {
                e.preventDefault();
            }
        });
    }
    let managerForm = document.getElementById('addManagerForm');
    if (managerForm) {
        managerForm.addEventListener('submit', function(e) {
            if (!basicUserFormCheck(this, true)) {
                e.preventDefault();
            }
        });
    }
    let customerForm = document.getElementById('addCustomerForm');
    if (customerForm) {
        customerForm.addEventListener('submit', function(e) {
            if (!basicUserFormCheck(this, false)) {
                e.preventDefault();
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
    let securityAnswer = form.querySelector('input[name="security_answer"]');
    let salary = isManager ? form.querySelector('input[name="salary"]') : null;

    if (!/^[A-Za-z_]{4,}$/.test(username.value.trim())) {
        document.getElementById('admin-username-error').textContent =
            'Username must be at least 4 characters, only letters,  underscores, and no spaces';
        valid = false;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
        document.getElementById('admin-email-error').textContent =
            'Enter a valid email address';
        valid = false;
    }

    if (!/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(password.value.trim())) {
        document.getElementById('admin-password-error').textContent =
            'Password must be at least 8 characters and include at least one letter and one number';
        valid = false;
    }

    if (!securityAnswer.value.trim()) {
        document.getElementById('admin-sa-error').textContent =
            'Security answer cannot be empty';
        valid = false;
    }

    if (isManager && salary) {
        if (Number(salary.value) <= 0 || isNaN(Number(salary.value))) {
            document.getElementById('admin-salary-error').textContent =
                'Enter a valid positive salary';
            valid = false;
        }
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