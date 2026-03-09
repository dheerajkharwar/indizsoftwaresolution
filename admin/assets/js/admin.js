// admin/js/admin.js - Complete Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile sidebar toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const mobileOverlay = document.createElement('div');
    mobileOverlay.className = 'sidebar-overlay';
    document.body.appendChild(mobileOverlay);
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.add('show');
            mobileOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.remove('show');
            mobileOverlay.classList.remove('show');
            document.body.style.overflow = '';
        });
    }
    
    mobileOverlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        mobileOverlay.classList.remove('show');
        document.body.style.overflow = '';
    });
    
    // Dropdown menus
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        const menu = dropdown.querySelector('.dropdown-menu');
        if (menu) {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
            });
        }
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown.active').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });
    
    // Initialize charts if on dashboard
    if (document.getElementById('inquiriesChart')) {
        initDashboardCharts();
    }
    
    // Load real-time notifications
    loadNotifications();
    setInterval(loadNotifications, 30000); // Refresh every 30 seconds
    
    // Confirm delete actions
    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
    
    // Table search functionality
    const tableSearch = document.getElementById('tableSearch');
    if (tableSearch) {
        tableSearch.addEventListener('keyup', function() {
            searchTable(this.value);
        });
    }
    
    // Date range picker
    const dateRange = document.getElementById('dateRange');
    if (dateRange) {
        dateRange.addEventListener('change', function() {
            filterByDate(this.value);
        });
    }
    
    // Export functionality
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            exportData(this.dataset.type);
        });
    }
    
    // Bulk actions
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            toggleSelectAll(this.checked);
        });
    }
});

// Dashboard Charts
function initDashboardCharts() {
    // Inquiries Chart
    const inquiriesCtx = document.getElementById('inquiriesChart').getContext('2d');
    new Chart(inquiriesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Inquiries',
                data: [12, 19, 15, 17, 24, 23, 25, 28, 32, 30, 35, 40],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    
    // Services Distribution Chart
    const servicesCtx = document.getElementById('servicesChart').getContext('2d');
    new Chart(servicesCtx, {
        type: 'doughnut',
        data: {
            labels: ['School ERP', 'POS System', 'E-commerce', 'Other'],
            datasets: [{
                data: [45, 30, 20, 5],
                backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#64748b'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '70%'
        }
    });
}

// Load Notifications
function loadNotifications() {
    fetch('ajax.php?action=get_notifications')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? 'block' : 'none';
            }
            
            updateNotificationList(data.notifications);
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function updateNotificationList(notifications) {
    const list = document.getElementById('notificationList');
    if (!list) return;
    
    list.innerHTML = '';
    notifications.forEach(notif => {
        const item = document.createElement('a');
        item.href = notif.link;
        item.className = 'notification-item';
        item.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${notif.icon}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${notif.title}</div>
                <div class="notification-time">${notif.time}</div>
            </div>
        `;
        list.appendChild(item);
    });
}

// Table Search
function searchTable(query) {
    const table = document.getElementById('dataTable');
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    query = query.toLowerCase();
    
    rows.forEach(row => {
        let found = false;
        const cells = row.querySelectorAll('td');
        
        cells.forEach(cell => {
            if (cell.textContent.toLowerCase().includes(query)) {
                found = true;
            }
        });
        
        row.style.display = found ? '' : 'none';
    });
}

// Filter by Date
function filterByDate(range) {
    const table = document.getElementById('dataTable');
    if (!table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    const now = new Date();
    let startDate = new Date();
    
    switch(range) {
        case 'today':
            startDate.setHours(0,0,0,0);
            break;
        case 'week':
            startDate.setDate(now.getDate() - 7);
            break;
        case 'month':
            startDate.setMonth(now.getMonth() - 1);
            break;
        default:
            rows.forEach(row => row.style.display = '');
            return;
    }
    
    rows.forEach(row => {
        const dateCell = row.querySelector('.date-cell');
        if (dateCell) {
            const rowDate = new Date(dateCell.dataset.date);
            row.style.display = rowDate >= startDate ? '' : 'none';
        }
    });
}

// Export Data
function exportData(type) {
    const format = document.getElementById('exportFormat')?.value || 'csv';
    const dateFrom = document.getElementById('dateFrom')?.value || '';
    const dateTo = document.getElementById('dateTo')?.value || '';
    
    window.location.href = `export.php?type=${type}&format=${format}&from=${dateFrom}&to=${dateTo}`;
}

// Select All Checkbox
function toggleSelectAll(checked) {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.checked = checked;
    });
    updateBulkActions();
}

// Bulk Actions
function updateBulkActions() {
    const selected = document.querySelectorAll('.row-checkbox:checked').length;
    const bulkBar = document.getElementById('bulkActionsBar');
    
    if (bulkBar) {
        if (selected > 0) {
            bulkBar.classList.add('show');
            document.getElementById('selectedCount').textContent = selected;
        } else {
            bulkBar.classList.remove('show');
        }
    }
}

// Apply Bulk Action
function applyBulkAction(action) {
    const selected = [];
    document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
        selected.push(cb.value);
    });
    
    if (selected.length === 0) return;
    
    if (confirm(`Are you sure you want to ${action} ${selected.length} item(s)?`)) {
        fetch('ajax.php?action=bulk_action', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: action,
                ids: selected
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error performing bulk action');
            }
        });
    }
}

// View Inquiry Details
function viewInquiry(id) {
    fetch(`ajax.php?action=get_inquiry&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showInquiryModal(data.inquiry);
            }
        });
}

function showInquiryModal(inquiry) {
    const modal = document.getElementById('inquiryModal');
    if (!modal) return;
    
    modal.querySelector('.modal-title').textContent = `Inquiry from ${inquiry.name}`;
    modal.querySelector('.inquiry-details').innerHTML = `
        <div class="detail-row">
            <strong>Name:</strong> ${inquiry.name}
        </div>
        <div class="detail-row">
            <strong>Email:</strong> <a href="mailto:${inquiry.email}">${inquiry.email}</a>
        </div>
        <div class="detail-row">
            <strong>Phone:</strong> <a href="tel:${inquiry.phone}">${inquiry.phone}</a>
        </div>
        <div class="detail-row">
            <strong>Service:</strong> ${inquiry.service}
        </div>
        <div class="detail-row">
            <strong>Date:</strong> ${inquiry.created_at}
        </div>
        <div class="detail-row">
            <strong>Message:</strong>
            <p>${inquiry.message}</p>
        </div>
        <div class="detail-row">
            <strong>IP Address:</strong> ${inquiry.ip_address}
        </div>
    `;
    
    modal.classList.add('show');
}

// Close Modal
function closeModal(modalId) {
    document.getElementById(modalId)?.classList.remove('show');
}

// Update Status
function updateStatus(id, status) {
    fetch('ajax.php?action=update_status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const badge = document.querySelector(`#row-${id} .status-badge`);
            if (badge) {
                badge.className = `status-badge status-${status}`;
                badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            }
        }
    });
}

// Preview Image
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    let isValid = true;
    const required = form.querySelectorAll('[required]');
    
    required.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
            
            const errorMsg = field.dataset.error || 'This field is required';
            showFieldError(field, errorMsg);
        } else {
            field.classList.remove('error');
            hideFieldError(field);
        }
    });
    
    return isValid;
}

function showFieldError(field, message) {
    let error = field.parentNode.querySelector('.field-error');
    if (!error) {
        error = document.createElement('span');
        error.className = 'field-error';
        field.parentNode.appendChild(error);
    }
    error.textContent = message;
}

function hideFieldError(field) {
    const error = field.parentNode.querySelector('.field-error');
    if (error) {
        error.remove();
    }
}

// Auto-save functionality
let autoSaveTimer;
function autoSave(formId, callback) {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        const form = document.getElementById(formId);
        if (form) {
            const formData = new FormData(form);
            
            fetch('ajax.php?action=auto_save', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (callback) callback(data);
                showAutoSaveNotification();
            });
        }
    }, 2000);
}

function showAutoSaveNotification() {
    const notification = document.getElementById('autoSaveNotification');
    if (notification) {
        notification.classList.add('show');
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+S for save
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        const saveBtn = document.getElementById('saveBtn');
        if (saveBtn) saveBtn.click();
    }
    
    // Ctrl+F for search
    if (e.ctrlKey && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('tableSearch');
        if (searchInput) searchInput.focus();
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.show').forEach(modal => {
            modal.classList.remove('show');
        });
    }
});

// Initialize tooltips
document.querySelectorAll('[data-tooltip]').forEach(element => {
    element.addEventListener('mouseenter', function(e) {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = this.dataset.tooltip;
        
        document.body.appendChild(tooltip);
        
        const rect = this.getBoundingClientRect();
        tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        
        this.addEventListener('mouseleave', function() {
            tooltip.remove();
        });
    });
});

// Export functions globally
window.viewInquiry = viewInquiry;
window.closeModal = closeModal;
window.updateStatus = updateStatus;
window.previewImage = previewImage;
window.validateForm = validateForm;
window.applyBulkAction = applyBulkAction;