<div class="space-y-8 pb-10">
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">Dashboard Overview</h1>
            <p class="text-gray-500 mt-1 font-medium text-sm md:text-base">Monitoring AZAM IT: <span
                    class="text-primary-600"><?php echo $counts['total_assets']; ?></span> active assets across <span
                    class="text-primary-600"><?php echo $counts['total_categories']; ?></span> departments.</p>
        </div>
        <div class="flex flex-wrap gap-2 md:gap-3">
            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                <a href="<?php echo site_url('assets/export_csv'); ?>"
                    class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-6 bg-white border border-[#dbe0e6] text-[#111418] text-sm font-bold leading-normal hover:bg-gray-50 transition-colors">
                    <span class="material-symbols-outlined mr-2 text-xl">download</span>
                    <span class="truncate">Export CSV</span>
                </a>
                <a href="<?php echo site_url('assets/import'); ?>"
                    class="flex items-center gap-2 bg-white border border-gray-200 px-5 py-2.5 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-xl">upload</span> Bulk Import
                </a>
                <a href="<?php echo site_url('assets/add'); ?>"
                    class="flex items-center gap-2 bg-primary-600 px-5 py-2.5 rounded-xl text-sm font-bold text-white hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                    <span class="material-symbols-outlined text-xl">add</span> Add New Asset
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Loan Alerts -->
    <?php if (!empty($due_soon)): ?>
        <div
            class="bg-orange-50 border border-orange-100 rounded-3xl p-4 md:p-6 flex flex-col md:flex-row items-center gap-6 animate-fade-in">
            <div
                class="size-14 rounded-2xl bg-orange-500 flex items-center justify-center text-white shadow-lg shadow-orange-200 shrink-0">
                <span class="material-symbols-outlined text-2xl">alarm</span>
            </div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="text-lg font-black text-orange-900 tracking-tight">Temporary Loans Due Soon</h3>
                <p class="text-orange-700/70 text-xs md:text-sm font-medium leading-relaxed">There are <span
                        class="font-bold underline"><?php echo count($due_soon); ?></span> items that need to be returned
                    within
                    the next 7 days.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-2">
                <?php foreach (array_slice($due_soon, 0, 3) as $loan): ?>
                    <div
                        class="bg-white/80 backdrop-blur-sm px-4 py-2 rounded-xl border border-orange-200 flex items-center gap-3">
                        <span class="text-xs font-black text-orange-800"><?php echo $loan->name; ?></span>
                        <span
                            class="px-2 py-0.5 rounded-lg bg-orange-100 text-orange-600 text-[10px] font-black uppercase"><?php echo date('M d', strtotime($loan->return_date)); ?></span>
                    </div>
                <?php endforeach; ?>
                <?php if (count($due_soon) > 3): ?>
                    <div
                        class="bg-orange-600 px-4 py-2 rounded-xl text-white text-[10px] font-black uppercase tracking-widest flex items-center">
                        + <?php echo count($due_soon) - 3; ?> MORE
                    </div>
                <?php endif; ?>
            </div>
            <a href="<?php echo site_url('assets'); ?>"
                class="px-6 py-3 bg-white text-orange-600 text-xs font-black uppercase tracking-widest rounded-xl border border-orange-200 hover:bg-orange-500 hover:text-white transition-all">Check
                All</a>
        </div>
    <?php endif; ?>

    <!-- Analytics Filter Section -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-4">
            <div>
                <h2 class="text-lg font-black text-gray-900 tracking-tight">Issue Analytics</h2>
                <p class="text-sm text-gray-500">Filter data to analyze trends and performance</p>
            </div>
            <button onclick="toggleFilters()"
                class="text-primary-600 font-bold text-sm hover:underline flex items-center gap-1">
                <span class="material-symbols-outlined">filter_list</span> Toggle Filters
            </button>
        </div>

        <form id="analyticsFilterForm" action="<?php echo site_url('dashboard'); ?>" method="GET"
            class="hidden md:grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">College</label>
                <select name="college_id"
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Colleges</option>
                    <?php if (isset($colleges)):
                        foreach ($colleges as $col): ?>
                            <option value="<?php echo $col->id; ?>" <?php echo ($this->input->get('college_id') == $col->id) ? 'selected' : ''; ?>>
                                <?php echo $col->name; ?>
                            </option>
                        <?php endforeach; endif; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                <select name="status"
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">All Statuses</option>
                    <option value="Pending" <?php echo ($this->input->get('status') == 'Pending') ? 'selected' : ''; ?>>
                        Pending</option>
                    <option value="In Progress" <?php echo ($this->input->get('status') == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Resolved" <?php echo ($this->input->get('status') == 'Resolved') ? 'selected' : ''; ?>>
                        Resolved</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Start Date</label>
                <input type="date" name="start_date" value="<?php echo $this->input->get('start_date'); ?>"
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">End Date</label>
                <input type="date" name="end_date" value="<?php echo $this->input->get('end_date'); ?>"
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="w-full bg-gray-900 text-white py-2 rounded-xl font-bold text-sm hover:bg-black transition-colors">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Analytics Charts Grid -->
    <?php if (isset($analytics)): ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Issues by College -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4">Top Colleges with Issues</h3>
                <div class="h-64">
                    <canvas id="issuesByCollegeChart"></canvas>
                </div>
            </div>

            <!-- Issues Trend -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4">Issue Reporting Trend</h3>
                <div class="h-64">
                    <canvas id="issuesTrendChart"></canvas>
                </div>
            </div>

            <!-- Issues by Status -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4">Current Status Distribution</h3>
                <div class="h-64 flex justify-center">
                    <canvas id="issuesStatusChart"></canvas>
                </div>
            </div>

            <!-- Top Problematic Products -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4">Most Reported Products</h3>
                <div class="space-y-4">
                    <?php foreach ($analytics['by_product'] as $prod): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center font-bold text-xs">
                                    <?php echo $prod->issue_count; ?>
                                </div>
                                <span class="text-sm font-bold text-gray-700"><?php echo $prod->product_name; ?></span>
                            </div>
                            <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full"
                                    style="width: <?php echo min(100, ($prod->issue_count / max(1, $analytics['by_product'][0]->issue_count)) * 100); ?>%">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($analytics['by_product'])): ?>
                        <p class="text-gray-400 text-sm italic text-center py-4">No data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Assets -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-2xl">laptop_mac</span>
                </div>
                <span
                    class="text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-full uppercase tracking-widest">Total</span>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter">
                <?php echo number_format($counts['total_assets']); ?>
            </h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Inventory Items</p>
        </div>

        <!-- In Stock -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-2xl">inventory_2</span>
                </div>
                <span
                    class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full uppercase tracking-widest">Ready</span>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter">
                <?php echo number_format($counts['in_stock']); ?>
            </h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Available in Lab</p>
        </div>

        <!-- Deployed -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-amber-50 text-amber-600 rounded-2xl group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-2xl">person_check</span>
                </div>
                <span
                    class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-full uppercase tracking-widest">In
                    Use</span>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter">
                <?php echo number_format($counts['deployed']); ?>
            </h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Assigned to Staff</p>
        </div>

        <!-- Users -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-purple-50 text-purple-600 rounded-2xl group-hover:bg-purple-600 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-2xl">group</span>
                </div>
                <span
                    class="text-[10px] font-black text-purple-600 bg-purple-50 px-2 py-1 rounded-full uppercase tracking-widest">System</span>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter">
                <?php echo number_format($counts['total_users']); ?>
            </h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Registered Users</p>
        </div>
    </div>

    <!-- Charts & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 md:gap-8">
        <!-- Distribution Chart -->
        <div class="lg:col-span-3 bg-white p-4 md:p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">Asset Distribution</h3>
                    <p class="text-xs md:text-sm text-gray-500 font-medium">Inventory breakdown by category</p>
                </div>
                <div class="size-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                    <span class="material-symbols-outlined">analytics</span>
                </div>
            </div>
            <div class="h-[300px] flex items-center justify-center relative">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- System Logs / Activity Summary -->
        <div class="lg:col-span-2 bg-gray-900 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-2xl">
            <div class="relative z-10">
                <h3 class="text-lg font-black tracking-tight mb-2">Live Activity Feed</h3>
                <p class="text-gray-400 text-sm mb-8 font-medium italic opacity-70">Real-time system event logging</p>

                <div class="space-y-6">
                    <?php if (!empty($recent_logs)): ?>
                        <?php foreach (array_slice($recent_logs, 0, 4) as $log): ?>
                            <div class="flex gap-4 group">
                                <div class="flex flex-col items-center">
                                    <div class="size-2.5 rounded-full bg-primary-500 shadow-[0_0_10px_rgba(19,127,236,0.5)]">
                                    </div>
                                    <div class="w-[2px] h-full bg-gradient-to-b from-primary-500/30 to-transparent mt-2"></div>
                                </div>
                                <div class="flex-1 pb-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="text-[10px] font-bold text-primary-400 uppercase tracking-widest"><?php echo date('H:i', strtotime($log->created_at)); ?></span>
                                        <span
                                            class="text-[10px] text-gray-600 font-black px-1.5 py-0.5 bg-gray-800 rounded uppercase tracking-tighter"><?php echo $log->action; ?></span>
                                    </div>
                                    <p class="text-xs font-bold text-gray-100"><?php echo $log->user_name; ?></p>
                                    <p class="text-[10px] text-gray-500 font-medium truncate w-40 opacity-80">
                                        <?php echo $log->details; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-10 opacity-30">
                            <span class="material-symbols-outlined text-5xl mb-2">noise_control_off</span>
                            <p class="text-sm">No activity recorded</p>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?php echo site_url('auditlogs'); ?>"
                    class="inline-flex items-center gap-2 mt-4 text-xs font-black text-primary-400 uppercase tracking-widest hover:text-white transition-colors group">
                    View Full System Logs
                    <span
                        class="material-symbols-outlined text-base group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
                </a>
            </div>
            <!-- Background watermark -->
            <span
                class="material-symbols-outlined absolute -right-10 -bottom-10 text-[180px] opacity-[0.03] rotate-12 pointer-events-none">history</span>
        </div>
    </div>

    <!-- Recent Assets Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div
            class="p-4 md:p-8 border-b border-gray-50 bg-gray-50/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Recent Arrivals</h3>
                <p class="text-sm text-gray-500 font-medium">New items cataloged recently</p>
            </div>
            <a href="<?php echo site_url('assets'); ?>"
                class="w-full sm:w-auto text-center px-5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all shadow-sm">View
                Catalog</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        <th class="px-8 py-4">Item Identity</th>
                        <th class="px-8 py-4">Categorization</th>
                        <th class="px-8 py-4 text-center">QTY</th>
                        <th class="px-8 py-4">Asset Tag</th>
                        <th class="px-8 py-4 text-right">Filing Status</th>
                        <th class="px-8 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($recent_assets as $asset): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-5 flex items-center gap-4">
                                <div class="size-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                                    <span class="material-symbols-outlined">devices</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900"><?php echo $asset->name; ?></p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">
                                        <?php echo date('M d, Y', strtotime($asset->created_at)); ?>
                                    </p>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span
                                    class="px-3 py-1 text-[10px] font-black rounded-lg bg-primary-50 text-primary-600 border border-primary-100 uppercase tracking-tighter"><?php echo $asset->category_name; ?></span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-xs font-bold text-gray-900"><?php echo $asset->quantity; ?></span>
                            </td>
                            <td class="px-8 py-5">
                                <p
                                    class="text-xs font-mono font-bold text-gray-700 bg-gray-50 px-2.5 py-1 rounded inline-block border border-gray-100">
                                    <?php echo $asset->asset_tag; ?>
                                </p>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="inline-flex items-center gap-2 group">
                                    <span
                                        class="size-1.5 rounded-full bg-emerald-500 shadow-[0_0_5px_rgba(16,185,129,0.5)]"></span>
                                    <span
                                        class="text-[11px] font-black text-gray-500 uppercase tracking-tighter">Verified</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="viewAssetDetails(<?php echo htmlspecialchars(json_encode($asset)); ?>)"
                                        class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-all"
                                        title="View Details">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                    <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                        <a href="<?php echo site_url('assets/delete/' . $asset->id); ?>"
                                            onclick="return confirm('Are you sure you want to delete this asset? This action cannot be undone.')"
                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                            title="Delete Asset">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Asset Details Modal -->
<div id="assetModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-md flex items-center justify-center p-4">
    <div
        class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all animate-fade-up">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-4">
                <div
                    class="size-14 rounded-2xl bg-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-200">
                    <span class="material-symbols-outlined text-2xl">barcode_scanner</span>
                </div>
                <div>
                    <h3 id="m_asset_name" class="text-xl font-black text-gray-900 tracking-tight">Asset Details</h3>
                    <p id="m_asset_tag" class="text-xs font-bold text-primary-600 uppercase tracking-widest"></p>
                </div>
            </div>
            <button onclick="closeAssetModal()"
                class="size-10 rounded-xl hover:bg-gray-100 flex items-center justify-center text-gray-400 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="p-8 grid grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-2">Technical
                        Info</label>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs font-medium text-gray-500">Category</span>
                            <span id="m_cat" class="text-xs font-black text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs font-medium text-gray-500">Brand/Model</span>
                            <span id="m_brand" class="text-xs font-black text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs font-medium text-gray-500">Serial No</span>
                            <span id="m_sn" class="text-xs font-mono font-bold text-gray-900"></span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 text-[#101922]">
                    <h3 class="font-bold mb-6">Quick Inventory Check</h3>
                    <div class="space-y-4">
                        <button onclick="openScanner()"
                            class="w-full p-4 bg-gray-50 rounded-lg flex items-center gap-4 border border-gray-100 hover:bg-gray-100 transition-all text-left">
                            <span class="material-symbols-outlined text-primary-600">qr_code_scanner</span>
                            <div>
                                <p class="text-sm font-bold">Scan Asset Tag</p>
                                <p class="text-xs text-gray-500">Identify items using camera</p>
                            </div>
                        </button>
                        <a href="<?php echo site_url('auditlogs'); ?>"
                            class="p-4 bg-gray-50 rounded-lg flex items-center gap-4 border border-gray-100 hover:bg-gray-100 transition-all text-left block">
                            <span class="material-symbols-outlined text-primary-600">history</span>
                            <div>
                                <p class="text-sm font-bold">Audit History</p>
                                <p class="text-xs text-gray-500">Review recent log session</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-2">Asset
                        Condition</label>
                    <div id="m_condition"
                        class="inline-flex px-4 py-2 rounded-xl bg-orange-50 text-orange-600 text-xs font-black uppercase tracking-tighter">
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center bg-gray-50 rounded-3xl p-6 border border-gray-100">
                <div id="m_qrcode" class="mb-4 bg-white p-3 rounded-2xl shadow-sm"></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Institutional ID
                    Code</p>
                <div class="mt-6 flex gap-3">
                    <button onclick="downloadAssetTag()"
                        class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">download</span>
                        Download Tag
                    </button>
                </div>
            </div>
        </div>

        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex justify-between items-center">
            <p class="text-[10px] font-bold text-gray-400 italic">Registered on <span id="m_date"
                    class="text-gray-600"></span></p>
            <button onclick="closeAssetModal()"
                class="px-8 py-3 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-xl shadow-gray-200">Close
                Panel</button>
        </div>
    </div>
</div>

</div>
<script>
    let currentAssetTag = '';

    function viewAssetDetails(asset) {
        currentAssetTag = asset.asset_tag;
        document.getElementById('m_asset_name').textContent = asset.name;
        document.getElementById('m_asset_tag').textContent = asset.asset_tag;
        document.getElementById('m_cat').textContent = asset.category_name;
        document.getElementById('m_brand').textContent = asset.brand_model || 'N/A';
        document.getElementById('m_sn').textContent = asset.serial_number || 'N/A';
        document.getElementById('m_condition').textContent = asset.asset_condition || 'New';
        document.getElementById('m_date').textContent = new Date(asset.created_at).toLocaleDateString();

        // Generate QR code - Using a URL makes it "usable" by any standard scanner
        const siteUrl = '<?php echo site_url('assets'); ?>';
        const qrContent = `${siteUrl}?q=${asset.asset_tag}`;

        const container = document.getElementById('m_qrcode');
        container.innerHTML = "";

        // Ensure QRCode is loaded
        if (typeof QRCode !== 'undefined') {
            new QRCode(container, {
                text: qrContent,
                width: 160,
                height: 160,
                colorDark: "#101922",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }

        document.getElementById('assetModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function downloadAssetTag() {
        const qrImg = document.querySelector('#m_qrcode img');
        if (qrImg) {
            const link = document.createElement('a');
            link.href = qrImg.src;
            link.download = `Tag_${currentAssetTag}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            alert('Tag image not ready yet. Please try again.');
        }
    }

    function closeAssetModal() {
        document.getElementById('assetModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Scanner Logic
    let html5QrCode = null;

    function openScanner() {
        document.getElementById('scannerModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .catch(err => {
                alert("Camera access failed. Ensure you are on HTTPS or localhost and have granted permissions.");
                closeScanner();
            });
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanner
        closeScanner();

        let tag = "";

        // Improved Parsing Logic
        try {
            // 1. Try to parse as URL
            if (decodedText.includes('://')) {
                const url = new URL(decodedText);
                tag = url.searchParams.get('q');
            }
        } catch (e) { }

        // 2. Try multiline text format (Tag: COL-XXXX)
        if (!tag) {
            const lines = decodedText.split('\n');
            lines.forEach(line => {
                if (line.includes('Tag:')) {
                    tag = line.split('Tag:')[1].trim();
                }
            });
        }

        // 3. Fallback to raw text if it looks like a tag or matches our prefix
        if (!tag) {
            const cleanText = decodedText.trim();
            if (cleanText.startsWith('COL-') || cleanText.length >= 8) {
                tag = cleanText;
            }
        }

        if (tag) {
            fetch(`<?php echo site_url('assets/search_by_tag/'); ?>${tag}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        viewAssetDetails(data.asset);
                    } else {
                        alert("Asset not found in system.");
                    }
                })
                .catch(err => alert("Error searching for asset."));
        } else {
            alert("Invalid QR format detected.");
        }
    }

    function closeScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
            }).catch(() => {
                html5QrCode = null;
            });
        }
        document.getElementById('scannerModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('categoryChart').getContext('2d');

        const categoryData = <?php echo json_encode($categories); ?>;
        const labels = categoryData.map(c => c.category);
        const values = categoryData.map(c => c.count);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#137fec', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444',
                        '#06b6d4', '#f43f5e', '#101922', '#64748b', '#ec4899'
                    ],
                    borderWidth: 8,
                    borderColor: '#ffffff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 10,
                                weight: '800',
                                family: 'Inter'
                            },
                            color: '#64748b'
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#101922',
                        bodyFont: { size: 12, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false
                    }
                }
            }
        });

        // Analytics Charts
        <?php if (isset($analytics)): ?>

            // 1. Issues by College (Bar Chart)
            const collegeCtx = document.getElementById('issuesByCollegeChart').getContext('2d');
            const collegeData = <?php echo json_encode($analytics['by_college']); ?>;
            new Chart(collegeCtx, {
                type: 'bar',
                data: {
                    labels: collegeData.map(d => d.college_name),
                    datasets: [{
                        label: 'Reported Issues',
                        data: collegeData.map(d => d.issue_count),
                        backgroundColor: '#3b82f6',
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false }, ticks: { display: false } } // Hide x labels if too many
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // 2. Trend Chart (Line)
            const trendCtx = document.getElementById('issuesTrendChart').getContext('2d');
            const trendData = <?php echo json_encode($analytics['trend']); ?>;
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.date),
                    datasets: [{
                        label: 'Issues',
                        data: trendData.map(d => d.count),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // 3. Status Chart (Doughnut)
            const statusCtx = document.getElementById('issuesStatusChart').getContext('2d');
            const statusData = <?php echo json_encode($analytics['by_status']); ?>;
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(d => d.status),
                    datasets: [{
                        data: statusData.map(d => d.count),
                        backgroundColor: ['#f97316', '#3b82f6', '#10b981', '#ef4444', '#9ca3af'], // Matches badges
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } }
                    }
                }
            });

        <?php endif; ?>
    });

    function toggleFilters() {
        const form = document.getElementById('analyticsFilterForm');
        form.classList.toggle('hidden');
        form.classList.toggle('grid');
    }
</script>