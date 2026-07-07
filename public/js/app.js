// Frontend JavaScript for FYP Management System

document.addEventListener('DOMContentLoaded', function() {
    // Global fix for Bootstrap Modals Stacking Context Issue
    // Moves all modals to the top level body to prevent dark unclickable overlay bugs
    document.querySelectorAll('.modal').forEach(function(modal) {
        document.body.appendChild(modal);
    });

    // Initialize Notifications
    fetchNotifications();
    setInterval(fetchNotifications, 20000); // Poll every 20 seconds

    // Setup Notification Mark as Read Click Handlers
    const notifBtn = document.getElementById('mark-all-read');
    if (notifBtn) {
        notifBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markNotificationsRead();
        });
    }

    // Toggle Sidebar on Mobile
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const desktopSidebarCollapse = document.getElementById('desktopSidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    
    if (sidebarCollapse && sidebar) {
        sidebarCollapse.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
        });

        if (desktopSidebarCollapse) {
            desktopSidebarCollapse.addEventListener('click', function(e) {
                e.stopPropagation();
                document.documentElement.classList.toggle('sidebar-collapsed');
                
                if (document.documentElement.classList.contains('sidebar-collapsed')) {
                    localStorage.setItem('sidebar_collapsed', 'true');
                } else {
                    localStorage.setItem('sidebar_collapsed', 'false');
                }
            });
        }

        if (content) {
            content.addEventListener('click', function() {
                if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        }

        sidebar.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        });
    }

    // Setup Client-side table filter/search helper (Search + Select Filters)
    function filterTable(tableId) {
        const table = document.getElementById(tableId);
        const mobileContainer = document.getElementById(tableId + '-mobile');
        
        if (!table && !mobileContainer) return;

        const searchInput = document.querySelector(`.table-search[data-target="${tableId}"]`);
        const filters = document.querySelectorAll(`.table-filter[data-target="${tableId}"]`);
        
        const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
        
        const activeFilters = [];
        filters.forEach(select => {
            const column = select.dataset.column;
            const value = select.value;
            if (value && value !== 'all') {
                activeFilters.push({ column, value });
            }
        });

        // 1. Filter Desktop Table Rows if table exists
        if (table) {
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                // Text Search Check
                const textMatch = searchValue === '' || row.textContent.toLowerCase().indexOf(searchValue) > -1;
                
                // Select Filters Check
                let filterMatch = true;
                for (let i = 0; i < activeFilters.length; i++) {
                    const f = activeFilters[i];
                    const rowVal = row.dataset[f.column];
                    if (rowVal !== f.value) {
                        filterMatch = false;
                        break;
                    }
                }

                if (textMatch && filterMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // 2. Filter Mobile Cards if container exists
        if (mobileContainer) {
            const cards = mobileContainer.querySelectorAll('.card, [class*="-card"]');
            cards.forEach(card => {
                // Text Search Check
                const textMatch = searchValue === '' || card.textContent.toLowerCase().indexOf(searchValue) > -1;
                
                // Select Filters Check
                let filterMatch = true;
                for (let i = 0; i < activeFilters.length; i++) {
                    const f = activeFilters[i];
                    const cardVal = card.dataset[f.column];
                    if (cardVal !== f.value) {
                        filterMatch = false;
                        break;
                    }
                }

                if (textMatch && filterMatch) {
                    card.style.removeProperty('display');
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            });
        }
    }

    // Attach event listeners to all search fields
    document.querySelectorAll('.table-search').forEach(input => {
        const target = input.dataset.target;
        input.addEventListener('input', () => filterTable(target));
        input.addEventListener('keyup', () => filterTable(target));
        input.addEventListener('search', () => filterTable(target));
    });

    // Attach event listeners to all dropdown filters
    document.querySelectorAll('.table-filter').forEach(select => {
        const target = select.dataset.target;
        select.addEventListener('change', () => filterTable(target));
    });

    // Dark Theme Toggle Logic
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeSunIcon = document.getElementById('theme-sun');
    const themeMoonIcon = document.getElementById('theme-moon');

    if (themeToggleBtn) {
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark-theme');
            if (themeSunIcon) themeSunIcon.classList.remove('d-none');
            if (themeMoonIcon) themeMoonIcon.classList.add('d-none');
        } else {
            document.documentElement.classList.remove('dark-theme');
            if (themeSunIcon) themeSunIcon.classList.add('d-none');
            if (themeMoonIcon) themeMoonIcon.classList.remove('d-none');
        }

        themeToggleBtn.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark-theme');
            
            let theme = 'light';
            if (document.documentElement.classList.contains('dark-theme')) {
                theme = 'dark';
                if (themeSunIcon) themeSunIcon.classList.remove('d-none');
                if (themeMoonIcon) themeMoonIcon.classList.add('d-none');
            } else {
                if (themeSunIcon) themeSunIcon.classList.add('d-none');
                if (themeMoonIcon) themeMoonIcon.classList.remove('d-none');
            }
            localStorage.setItem('theme', theme);
        });
    }
});

// Helper to resolve the correct basePath for AJAX requests
function getBasePath() {
    if (typeof window.appBasePath !== 'undefined') {
        return window.appBasePath;
    }
    const idx = window.location.pathname.indexOf('/public');
    if (idx > -1) {
        return window.location.pathname.substring(0, idx + 7);
    }
    return '';
}

// Fetch Notifications from API
function fetchNotifications() {
    const listContainer = document.getElementById('notification-list');
    const badge = document.getElementById('notification-badge');
    if (!listContainer) return;

    // Get Base path to build absolute API endpoints
    const basePath = getBasePath();
    
    fetch(`${basePath}/api/notifications`)
        .then(response => response.json())
        .then(data => {
            if (data.error) return;

            // Update Badge
            if (data.unreadCount > 0) {
                badge.textContent = data.unreadCount;
                badge.classList.remove('d-none');
                badge.classList.add('d-flex');
            } else {
                badge.classList.remove('d-flex');
                badge.classList.add('d-none');
            }

            // Build List
            listContainer.innerHTML = '';
            if (data.notifications.length === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="mb-3" style="font-size: 2.5rem; color: var(--border-color);">
                            <i class="bi bi-bell-slash"></i>
                        </div>
                        <h6 class="fw-bold" style="color: var(--text-primary); letter-spacing: -0.02em;">All caught up!</h6>
                        <p class="small text-muted mb-0">You have no new notifications.</p>
                    </div>
                `;
                return;
            }

            data.notifications.forEach(notif => {
                const isUnread = notif.is_read == 0;
                const href = notif.redirect_url ? `${basePath}${notif.redirect_url}` : '#';
                const target = notif.redirect_url ? 'target="_blank"' : '';
                
                let iconClass = 'bi-bell-fill';
                let iconColor = '#3b82f6';
                let iconBg = 'rgba(59,130,246,0.15)';
                
                const titleLower = notif.title.toLowerCase();
                if (titleLower.includes('success') || titleLower.includes('approved')) {
                    iconClass = 'bi-check-circle-fill';
                    iconColor = '#059669';
                    iconBg = 'rgba(5,150,105,0.15)';
                } else if (titleLower.includes('warning') || titleLower.includes('rejected')) {
                    iconClass = 'bi-exclamation-triangle-fill';
                    iconColor = '#dc2626';
                    iconBg = 'rgba(239,68,68,0.15)';
                } else if (titleLower.includes('group') || titleLower.includes('member') || titleLower.includes('student')) {
                    iconClass = 'bi-people-fill';
                    iconColor = '#8b5cf6';
                    iconBg = 'rgba(139,92,246,0.15)';
                } else if (titleLower.includes('grade') || titleLower.includes('score')) {
                    iconClass = 'bi-star-fill';
                    iconColor = '#f59e0b';
                    iconBg = 'rgba(245,158,11,0.15)';
                }

                const item = document.createElement('li');
                item.className = 'px-1 py-0';
                item.innerHTML = `
                    <div class="dropdown-item d-flex align-items-start gap-2 p-2 rounded-3 position-relative" style="background: ${isUnread ? 'var(--form-bg)' : 'transparent'}; transition: all 0.2s ease; margin-bottom: 2px;">
                        ${isUnread ? '<div style="position: absolute; left: 4px; top: 50%; transform: translateY(-50%); width: 5px; height: 5px; background: #3b82f6; border-radius: 50%; box-shadow: 0 0 4px rgba(59,130,246,0.6);"></div>' : ''}
                        
                        <div style="width: 30px; height: 30px; background: ${iconBg}; color: ${iconColor}; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem; ${isUnread ? 'margin-left: 6px;' : ''}">
                            <i class="bi ${iconClass}"></i>
                        </div>
                        
                        <a href="${href}" ${target} onclick="markNotificationSingle(${notif.id})" class="text-decoration-none flex-grow-1 pe-4" style="color: inherit; white-space: normal;">
                            <div class="fw-bold mb-1" style="font-size: 0.78rem; color: var(--text-primary); letter-spacing: -0.01em; line-height: 1.2;">${escapeHtml(notif.title)}</div>
                            <div class="text-secondary mb-1" style="font-size: 0.7rem; line-height: 1.3;">${escapeHtml(notif.message)}</div>
                            <div class="d-flex align-items-center gap-1" style="font-size: 0.6rem; color: #94a3b8; font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">
                                <i class="bi bi-clock"></i> ${formatDate(notif.created_at)}
                            </div>
                        </a>
                        
                        <button type="button" class="btn p-0 position-absolute d-flex align-items-center justify-content-center" style="top: 8px; right: 8px; width: 20px; height: 20px; z-index: 10; border: none; background: transparent; color: #94a3b8; transition: color 0.2s ease;" onclick="event.stopPropagation(); deleteNotification(${notif.id})" title="Dismiss" onmouseover="this.style.color='#ef4444';" onmouseout="this.style.color='#94a3b8';">
                            <i class="bi bi-x" style="font-size: 1.3rem;"></i>
                        </button>
                    </div>
                `;
                listContainer.appendChild(item);
            });
        })
        .catch(err => console.log('Error fetching notifications:', err));
}

// Mark All Notifications as Read
function markNotificationsRead() {
    const basePath = getBasePath();
    
    fetch(`${basePath}/api/notifications/read`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            fetchNotifications();
        }
    });
}

// Mark Single Notification as Read
function markNotificationSingle(id) {
    const basePath = getBasePath();
    
    fetch(`${basePath}/api/notifications/read`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            fetchNotifications();
        }
    });
}

// Delete Notification
window.deleteNotification = function(id) {
    const basePath = getBasePath();
    if (!confirm('Are you sure you want to delete this notification?')) return;
    
    fetch(`${basePath}/api/notifications/delete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            fetchNotifications();
        } else {
            alert('Error: ' + (data.error || 'Could not delete notification'));
        }
    })
    .catch(err => console.log('Error deleting notification:', err));
};

// Helper: Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Helper: Format Date String
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}
