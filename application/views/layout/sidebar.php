<!-- Mobile Sidebar Backdrop -->
<div id="sidebarBackdrop" onclick="toggleSidebar()"
    class="fixed inset-0 bg-black/50 z-20 hidden md:hidden transition-opacity"></div>

<!-- Sidebar -->
<aside id="mainSidebar"
    class="fixed md:static inset-y-0 left-0 w-72 md:w-64 bg-white border-r border-gray-200 flex-shrink-0 z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="h-full flex flex-col">
        <!-- Brand -->
        <div class="p-6 flex items-center justify-between border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="bg-primary-600 p-2 rounded-lg">
                    <span class="material-symbols-outlined text-white">memory</span>
                </div>
                <span class="text-xl font-bold text-gray-900 tracking-tight">AZAM IT INVENTORY</span>
            </div>
            <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="<?php echo site_url('dashboard'); ?>"
                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'dashboard' && !$this->uri->segment(2)) ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <span class="material-symbols-outlined text-xl">grid_view</span>
                Dashboard
            </a>

            <div class="pt-4 pb-2 px-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Management</span>
            </div>

            <?php if ($this->session->userdata('role_id') == 1): ?>
                <a href="<?php echo site_url('users'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'users') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl">group</span>
                    User Management
                </a>
            <?php endif; ?>

            <a href="<?php echo site_url('assets'); ?>"
                class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'assets' && !$this->uri->segment(2)) ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                <span class="material-symbols-outlined text-xl">inventory_2</span>
                Inventory
            </a>

            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                <a href="<?php echo site_url('assets/trash'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'assets' && $this->uri->segment(2) == 'trash') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl text-orange-500">delete_sweep</span>
                    Trash
                </a>
            <?php endif; ?>

            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                <a href="<?php echo site_url('categories'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'categories') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl">category</span>
                    Asset Categories
                </a>
            <?php endif; ?>

            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                <a href="<?php echo site_url('colleges'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'colleges') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl">account_balance</span>
                    Institutions
                </a>
            <?php endif; ?>

            <?php if (in_array($this->session->userdata('role_id'), [1, 2, 3, 4])): // All roles ?>
                <a href="<?php echo site_url('issues'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'issues') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl">report_problem</span>
                    Issue Reporting
                </a>
            <?php endif; ?>

            <?php if (in_array($this->session->userdata('role_id'), [1, 2, 3])): ?>
                <a href="<?php echo site_url('assets/summary'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'assets' && $this->uri->segment(2) == 'summary') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl">assignment_ind</span>
                    Staff Possession
                </a>
            <?php endif; ?>

            <div class="pt-4 pb-2 px-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">System</span>
            </div>

            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                <a href="<?php echo site_url('reports'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'reports') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl">analytics</span>
                    Reports
                </a>
            <?php endif; ?>

            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                <a href="<?php echo site_url('auditlogs'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg <?php echo ($this->uri->segment(1) == 'auditlogs') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl">history</span>
                    Audit Logs
                </a>
            <?php endif; ?>

            <?php if ($this->session->userdata('role_id') == 1): ?>
                <a href="<?php echo site_url('settings'); ?>"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 <?php echo ($this->uri->segment(1) == 'settings') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50'; ?>">
                    <span class="material-symbols-outlined text-xl">settings</span>
                    Settings
                </a>
            <?php endif; ?>
        </nav>

        <!-- User Profile (Bottom) -->
        <div class="p-4 border-t border-gray-100">
            <a href="<?php echo site_url('profile'); ?>"
                class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors group">
                <div
                    class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">
                    <?php echo substr($this->session->userdata('username'), 0, 1); ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">
                        <?php echo $this->session->userdata('username'); ?>
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        <?php echo ($this->session->userdata('role_id') == 1) ? 'System Admin' : 'Staff Member'; ?>
                    </p>
                </div>
            </a>
            <a href="<?php echo site_url('auth/logout'); ?>"
                class="flex items-center gap-3 px-3 py-2 mt-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50">
                <span class="material-symbols-outlined text-xl">logout</span>
                Logout
            </a>
        </div>
    </div>
</aside>

<!-- Main Workspace -->
<main class="flex-1 flex flex-col h-screen overflow-hidden">
    <!-- Topbar -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-8 flex-shrink-0">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()"
                class="md:hidden p-2 -ml-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <h2 class="text-sm md:text-lg font-semibold text-gray-800 truncate max-w-[150px] md:max-w-none">
                <?php echo isset($page_title) ? $page_title : 'Dashboard Overview'; ?>
            </h2>
        </div>

        <div class="flex items-center gap-4">
            <!-- Notification Bell -->
            <div class="relative items-center" id="notificationContainer">
                <button onclick="toggleNotifications()"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full relative transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span id="notifBadge"
                        class="absolute top-1 right-1 size-2.5 bg-red-500 rounded-full border-2 border-white hidden animate-pulse"></span>
                </button>

                <!-- Dropdown -->
                <div id="notifDropdown"
                    class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 hidden z-50 overflow-hidden transform transition-all origin-top-right scale-95 opacity-0">
                    <div class="p-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                        <span class="text-sm font-bold text-gray-900">Notifications</span>
                        <span id="notifCount"
                            class="text-[10px] bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full font-bold">0
                            New</span>
                    </div>
                    <div id="notifList" class="max-h-80 overflow-y-auto">
                        <!-- Items injected via JS -->
                        <div class="p-8 text-center text-gray-400">
                            <div class="animate-spin inline-block size-6 border-2 border-current border-t-transparent text-primary-600 rounded-full"
                                role="status" aria-label="loading"></div>
                        </div>
                    </div>
                    <div class="p-3 border-t border-gray-50 text-center">
                        <a href="<?php echo site_url('assets'); ?>"
                            class="text-[10px] font-bold text-primary-600 uppercase tracking-widest hover:underline">View
                            Inventory</a>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    fetchNotifications();
                    // Poll everyday 60 seconds
                    setInterval(fetchNotifications, 60000);
                });

                function fetchNotifications() {
                    fetch('<?php echo site_url('notifications/fetch'); ?>')
                        .then(res => res.json())
                        .then(data => {
                            const badge = document.getElementById('notifBadge');
                            const countSpan = document.getElementById('notifCount');
                            const list = document.getElementById('notifList');

                            if (data.count > 0) {
                                badge.classList.remove('hidden');
                                countSpan.textContent = `${data.count} New`;
                            } else {
                                badge.classList.add('hidden');
                                countSpan.textContent = 'All caught up';
                            }

                            if (data.alerts.length === 0) {
                                list.innerHTML = `
                                    <div class="flex flex-col items-center justify-center py-8 opacity-50">
                                        <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">notifications_off</span>
                                        <p class="text-xs text-gray-500 font-medium">No new notifications</p>
                                    </div>
                                `;
                            } else {
                                list.innerHTML = data.alerts.map(alert => `
                                    <a href="${alert.link}" class="block p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors group">
                                        <div class="flex gap-3">
                                            <div class="size-8 rounded-full flex items-center justify-center shrink-0 ${alert.color === 'red' ? 'bg-red-50 text-red-500' : 'bg-orange-50 text-orange-500'}">
                                                <span class="material-symbols-outlined text-lg">${alert.icon}</span>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-gray-900 group-hover:text-primary-600 transition-colors">${alert.title}</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">${alert.message}</p>
                                                <p class="text-[10px] text-gray-400 mt-1 font-medium">${alert.time}</p>
                                            </div>
                                        </div>
                                    </a>
                                `).join('');
                            }
                        })
                        .catch(err => console.error('Failed to fetch notifications', err));
                }

                function toggleNotifications() {
                    const dropdown = document.getElementById('notifDropdown');
                    if (dropdown.classList.contains('hidden')) {
                        dropdown.classList.remove('hidden');
                        // Animation
                        requestAnimationFrame(() => {
                            dropdown.classList.remove('scale-95', 'opacity-0');
                        });
                    } else {
                        dropdown.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => dropdown.classList.add('hidden'), 200);
                    }
                }

                // Close when clicking outside
                document.addEventListener('click', (e) => {
                    const container = document.getElementById('notificationContainer');
                    if (!container.contains(e.target)) {
                        const dropdown = document.getElementById('notifDropdown');
                        if (!dropdown.classList.contains('hidden')) {
                            dropdown.classList.add('scale-95', 'opacity-0');
                            setTimeout(() => dropdown.classList.add('hidden'), 200);
                        }
                    }
                });
            </script>
            <div class="hidden md:flex h-8 w-px bg-gray-200"></div>
            <span class="hidden md:inline text-sm text-gray-500">
                <?php echo date('D, d M Y'); ?>
            </span>
        </div>
    </header>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const isOpen = sidebar.classList.contains('translate-x-0');

            if (isOpen) {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('translate-x-0');
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
    </script>

    <!-- Content Area -->
    <div class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">