<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Reports & Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Generate insights on inventory and maintenance.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Assets -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <span class="material-symbols-outlined text-2xl">inventory_2</span>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Assets</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        <?php echo number_format($stats['total_assets']); ?>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Total Issues -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                    <span class="material-symbols-outlined text-2xl">report_problem</span>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Issues</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        <?php echo number_format($stats['total_issues']); ?>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Open Issues -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-orange-50 text-orange-600 rounded-xl">
                    <span class="material-symbols-outlined text-2xl">pending</span>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Issues</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        <?php echo number_format($stats['open_issues']); ?>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Asset Value -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                    <span class="material-symbols-outlined text-2xl">attach_money</span>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</p>
                    <h3 class="text-2xl font-bold text-gray-900">$
                        <?php echo number_format($stats['assets_value'], 2); ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Types -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Asset Reports -->
        <a href="<?php echo site_url('reports/assets'); ?>"
            class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div class="flex gap-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl">dataset</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Asset
                            Inventory Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Detailed reports on assets, filtered by category, status,
                            or college.</p>
                    </div>
                </div>
                <span
                    class="material-symbols-outlined text-gray-300 group-hover:text-purple-600 transition-colors">arrow_forward</span>
            </div>
        </a>

        <!-- Issue Reports -->
        <a href="<?php echo site_url('reports/issues'); ?>"
            class="group bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div class="flex gap-4">
                    <div class="p-3 bg-pink-50 text-pink-600 rounded-xl group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl">bug_report</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-pink-600 transition-colors">
                            Maintenance & Issue Reports</h3>
                        <p class="text-sm text-gray-500 mt-1">Track maintenance history, issue resolution times, and
                            recurring problems.</p>
                    </div>
                </div>
                <span
                    class="material-symbols-outlined text-gray-300 group-hover:text-pink-600 transition-colors">arrow_forward</span>
            </div>
        </a>
    </div>
</div>