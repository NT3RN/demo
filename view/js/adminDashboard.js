document.addEventListener('DOMContentLoaded', function() {
    initDashboard();
});

function initDashboard() {
    setupTabNavigation();
    setupLogout();
    switchTab('adminSection')
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
    if (sectionId === 'adminSection') loadAdmins();
    else if (sectionId === 'managerSection') loadManagers();
    else if (sectionId === 'customerSection') loadCustomers();
    else if (sectionId === 'profileSection') loadProfile();
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