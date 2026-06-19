// Frontend JavaScript for FYP Management System

document.addEventListener('DOMContentLoaded', function() {
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
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    
    if (sidebarCollapse && sidebar) {
        sidebarCollapse.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
        });

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
            const cards = mobileContainer.querySelectorAll('.card');
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
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }

            // Build List
            listContainer.innerHTML = '';
            if (data.notifications.length === 0) {
                listContainer.innerHTML = '<li><a class="dropdown-item text-muted text-center py-2" href="#">No notifications</a></li>';
                return;
            }

            data.notifications.forEach(notif => {
                const isUnread = notif.is_read == 0 ? 'bg-light font-weight-bold' : '';
                const item = document.createElement('li');
                item.innerHTML = `
                    <a class="dropdown-item py-2 border-bottom ${isUnread}" href="#" onclick="markNotificationSingle(${notif.id})">
                        <div class="small text-primary">${escapeHtml(notif.title)}</div>
                        <div class="text-wrap small text-dark">${escapeHtml(notif.message)}</div>
                        <div class="x-small text-muted" style="font-size: 0.7rem;">${formatDate(notif.created_at)}</div>
                    </a>
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
