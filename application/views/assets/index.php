<div class="space-y-6 pb-20">
    <?php if ($this->session->flashdata('success')): ?>
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3 animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-green-500">check_circle</span>
            <p class="text-sm font-bold"><?php echo $this->session->flashdata('success'); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div
            class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3 animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-red-500">error</span>
            <p class="text-sm font-bold"><?php echo $this->session->flashdata('error'); ?></p>
        </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div class="flex flex-col gap-1 w-full">
            <h1 class="text-[#111418] text-3xl md:text-4xl font-black leading-tight tracking-tight">Master Inventory
            </h1>
            <div class="flex flex-wrap items-center gap-2">
                <span
                    class="px-3 py-1 bg-primary-50 text-primary-700 text-[11px] md:text-sm font-black rounded-xl border border-primary-100 shadow-sm animate-fade-in">
                    <?php echo count($assets); ?> <?php echo count($assets) === 1 ? 'Item' : 'Items'; ?> Found
                </span>
                <?php
                $total_units = 0;
                $in_stock_units = 0;
                $deployed_units = 0;
                foreach ($assets as $a) {
                    $total_units += $a->quantity;
                    if ($a->status === 'In Stock')
                        $in_stock_units += $a->quantity;
                    if ($a->status === 'Deployed' || $a->status === 'On Loan')
                        $deployed_units += $a->quantity;
                }
                ?>
                <span
                    class="px-3 py-1 bg-gray-50 text-gray-700 text-[11px] md:text-sm font-black rounded-xl border border-gray-100 shadow-sm animate-fade-in"
                    title="Total physically counted units across filtered items">
                    <?php echo number_format($total_units); ?> Total Units
                </span>
                <span
                    class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[11px] md:text-sm font-black rounded-xl border border-emerald-100 shadow-sm animate-fade-in"
                    title="Units currently in Central Storage / Lab">
                    <?php echo number_format($in_stock_units); ?> In Stock
                </span>
                <span
                    class="px-3 py-1 bg-amber-50 text-amber-700 text-[11px] md:text-sm font-black rounded-xl border border-amber-100 shadow-sm animate-fade-in"
                    title="Units assigned to Institutions or on loan">
                    <?php echo number_format($deployed_units); ?> Deployed/Loan
                </span>
            </div>
            <p class="text-[#617589] text-base font-normal leading-normal">Deep search and departmental asset management
            </p>
        </div>
        <div class="flex gap-3">
            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                <a href="<?php echo site_url('assets/export_csv'); ?>"
                    class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-6 bg-white border border-[#dbe0e6] text-[#111418] text-sm font-bold leading-normal hover:bg-gray-50 transition-colors">
                    <span class="material-symbols-outlined mr-2 text-xl">download</span>
                    <span class="truncate">Export CSV</span>
                </a>
                <a href="<?php echo site_url('assets/add'); ?>"
                    class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-6 bg-white border border-[#dbe0e6] text-[#111418] text-sm font-bold leading-normal hover:bg-gray-50 transition-colors">
                    <span class="truncate">Switch to Full Form</span>
                </a>
                <a href="<?php echo site_url('assets/import'); ?>"
                    class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-6 bg-primary-600 text-white text-sm font-bold leading-normal shadow-sm hover:bg-primary/90 transition-colors">
                    <span class="material-symbols-outlined mr-2 text-xl">upload</span>
                    <span class="truncate">Bulk Upload</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Analysis Dashboard Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 animate-fade-in mb-10">
        <!-- KPI Cards -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-2">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Items</span>
            <div class="flex items-end justify-between">
                <span
                    class="text-3xl font-black text-gray-900"><?php echo number_format($stats['total_items']); ?></span>
                <span class="material-symbols-outlined text-primary-500 bg-primary-50 p-2 rounded-xl">inventory_2</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-2">
            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Working Items</span>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-black text-gray-900"><?php echo number_format($stats['working']); ?></span>
                <span
                    class="material-symbols-outlined text-emerald-500 bg-emerald-50 p-2 rounded-xl">check_circle</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-2">
            <span class="text-[10px] font-black text-red-500 uppercase tracking-widest">Non-Working</span>
            <div class="flex items-end justify-between">
                <span
                    class="text-3xl font-black text-gray-900"><?php echo number_format($stats['non_working']); ?></span>
                <span class="material-symbols-outlined text-red-500 bg-red-50 p-2 rounded-xl">error</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col gap-2">
            <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest">Trashed Items</span>
            <div class="flex items-end justify-between">
                <span class="text-3xl font-black text-gray-900"><?php echo number_format($stats['trashed']); ?></span>
                <span class="material-symbols-outlined text-orange-500 bg-orange-50 p-2 rounded-xl">delete_sweep</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Category Breakdown</h3>
            <div class="h-64 relative">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Inventory Health</h3>
            <div class="h-64 relative">
                <canvas id="healthChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Category Chart
            const catCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(catCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($stats['categories'], 'name')); ?>,
                    datasets: [{
                        label: 'Items Count',
                        data: <?php echo json_encode(array_column($stats['categories'], 'count')); ?>,
                        backgroundColor: '#0277bd',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
                }
            });

            // Health Chart (Pie)
            const healthCtx = document.getElementById('healthChart').getContext('2d');
            new Chart(healthCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Working', 'Non-Working'],
                    datasets: [{
                        data: [<?php echo $stats['working']; ?>, <?php echo $stats['non_working']; ?>],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>

    <!-- Advanced Search & Filter Bar -->
    <div class="bg-gray-50 p-6 rounded-2xl border border-[#dbe0e6] shadow-inner mb-8">
        <form method="GET" action="<?php echo site_url('assets'); ?>"
            class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
            <div class="md:col-span-4 flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Search
                    Keywords</label>
                <div class="relative group">
                    <span
                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl group-focus-within:text-primary-600 transition-colors">search</span>
                    <input type="text" name="q" value="<?php echo htmlspecialchars($filters['q'] ?? ''); ?>"
                        class="w-full pl-12 pr-4 py-3.5 bg-white border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all placeholder:text-gray-400"
                        placeholder="Name, Tag, SN, or Model...">
                </div>
            </div>

            <div class="md:col-span-2 flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Category</label>
                <select name="category" id="filter_category"
                    class="w-full py-3.5 bg-white border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium text-gray-700">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat->id; ?>" <?php echo ($filters['category'] ?? '') == $cat->id ? 'selected' : ''; ?>>
                            <?php echo $cat->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="md:col-span-3 flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Allocated
                    College</label>
                <select name="college"
                    class="w-full py-3.5 bg-white border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium text-gray-700">
                    <option value="">All Institutions</option>
                    <option value="0" <?php echo (isset($filters['college']) && $filters['college'] === '0') ? 'selected' : ''; ?>>Central Storage (Unallocated)</option>
                    <?php foreach ($colleges as $coll): ?>
                        <option value="<?php echo $coll->id; ?>" <?php echo ($filters['college'] ?? '') == $coll->id ? 'selected' : ''; ?>>
                            <?php echo $coll->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2 flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Status</label>
                <select name="status"
                    class="w-full py-3.5 bg-white border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium text-gray-700">
                    <option value="">Any Status</option>
                    <option value="In Stock" <?php echo ($filters['status'] ?? '') == 'In Stock' ? 'selected' : ''; ?>>In
                        Stock</option>
                    <option value="Deployed" <?php echo ($filters['status'] ?? '') == 'Deployed' ? 'selected' : ''; ?>>
                        Deployed</option>
                    <option value="On Loan" <?php echo ($filters['status'] ?? '') == 'On Loan' ? 'selected' : ''; ?>>On
                        Loan</option>
                </select>
            </div>

            <div class="md:col-span-2 flex flex-col gap-2">
                <label class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] ml-1">Condition</label>
                <select name="condition"
                    class="w-full py-3.5 bg-white border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium text-gray-700">
                    <option value="">Any Condition</option>
                    <option value="New" <?php echo ($filters['condition'] ?? '') == 'New' ? 'selected' : ''; ?>>New
                    </option>
                    <option value="Used (Good)" <?php echo ($filters['condition'] ?? '') == 'Used (Good)' ? 'selected' : ''; ?>>Used (Good)</option>
                    <option value="Refurbished" <?php echo ($filters['condition'] ?? '') == 'Refurbished' ? 'selected' : ''; ?>>Refurbished</option>
                </select>
            </div>

            <div class="md:col-span-3 flex gap-3 mt-4 md:mt-0">
                <button type="submit"
                    class="flex-1 bg-primary-600 text-white font-black py-3.5 rounded-xl text-xs uppercase tracking-widest hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center justify-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-xl">filter_list</span> Filter
                </button>
                <a href="<?php echo site_url('assets'); ?>"
                    class="w-14 h-[52px] bg-white border border-gray-200 text-gray-500 rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center shadow-sm active:scale-95"
                    title="Reset Filters">
                    <span class="material-symbols-outlined text-2xl">restart_alt</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Bar -->
    <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
        <div id="bulkActionsBar"
            class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-6 animate-fade-in-up">
            <div class="flex items-center gap-3">
                <span class="bg-gray-700 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                    id="selectedCount">0</span>
                <span class="font-bold text-sm">Items Selected</span>
            </div>
            <div class="h-8 w-px bg-gray-700"></div>
            <button onclick="openBulkAllocationModal()"
                class="flex items-center gap-2 hover:text-primary-400 transition-colors font-bold text-sm uppercase tracking-wider">
                <span class="material-symbols-outlined">business_center</span> Allocate
            </button>
            <form id="bulkTrashForm" action="<?php echo site_url('assets/bulk_trash'); ?>" method="POST"
                onsubmit="return confirm('Move selected items to trash?')">
                <div id="bulkTrashIds"></div>
                <button type="submit"
                    class="flex items-center gap-2 hover:text-red-400 transition-colors font-bold text-sm uppercase tracking-wider">
                    <span class="material-symbols-outlined">delete_sweep</span> Move to Trash
                </button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Inventory Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 w-12">
                            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                <input type="checkbox" id="selectAll"
                                    class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-all cursor-pointer">
                            <?php endif; ?>
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Asset</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Serial /
                            Tag</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Category
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Qty</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                            Institution</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Condition
                        </th>
                        <th
                            class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">
                            Status / Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (!empty($assets)): ?>
                        <?php foreach ($assets as $asset): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                        <?php if ($asset->status === 'In Stock'): ?>
                                            <input type="checkbox" name="selected_assets[]" value="<?php echo $asset->id; ?>"
                                                class="asset-checkbox w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-all cursor-pointer">
                                        <?php else: ?>
                                            <input type="checkbox" disabled
                                                class="w-5 h-5 rounded border-gray-200 text-gray-300 cursor-not-allowed bg-gray-50">
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                            <span class="material-symbols-outlined">inventory_2</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900"><?php echo $asset->name; ?></p>
                                            <p class="text-[11px] text-gray-400">
                                                <?php echo date('M d, H:i', strtotime($asset->created_at)); ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-900 leading-none mb-1">
                                        <?php echo $asset->asset_tag; ?>
                                    </p>
                                    <p class="text-[10px] font-bold text-primary-600 uppercase tracking-wider">
                                        <?php echo $asset->serial_number ?: '---'; ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 bg-primary-50 text-primary-700 text-[10px] font-black rounded-lg uppercase tracking-tight border border-primary-100">
                                        <?php echo $asset->category_name; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-black text-gray-900"><?php echo $asset->quantity; ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($asset->college_name): ?>
                                        <div class="flex flex-col gap-0.5">
                                            <p class="text-xs font-bold text-gray-900"><?php echo $asset->college_name; ?></p>
                                            <p class="text-[10px] font-bold text-primary-600"><?php echo $asset->college_code; ?>
                                            </p>
                                        </div>
                                    <?php else: ?>
                                        <span
                                            class="text-xs font-bold text-gray-400 uppercase tracking-widest italic opacity-50">Not
                                            Allocated</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-medium text-gray-700"><?php echo $asset->asset_condition; ?></p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg border <?php
                                        echo ($asset->status == 'In Stock') ? 'bg-emerald-50 text-emerald-600 border-emerald-100' :
                                            (($asset->status == 'On Loan') ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-primary-50 text-primary-600 border-primary-100');
                                        ?>">
                                            <span class="w-1 h-1 rounded-full <?php
                                            echo ($asset->status == 'In Stock') ? 'bg-emerald-500' :
                                                (($asset->status == 'On Loan') ? 'bg-orange-500' : 'bg-primary-500');
                                            ?>"></span>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-tight"><?php echo $asset->status; ?></span>
                                        </div>

                                        <div class="flex items-center gap-1 ml-2">
                                            <!-- Consolidate Button -->
                                            <?php
                                            $rid = $asset->parent_id ?: $asset->id;
                                            if ($asset->status === 'In Stock' && ($stock_counts[$rid] ?? 0) > 1):
                                                ?>
                                                <a href="<?php echo site_url('assets/consolidate/' . $asset->id); ?>"
                                                    onclick="return confirm('Do you want to combine all in-stock units of this batch into one row?')"
                                                    class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-all"
                                                    title="Combine In-Stock Rows">
                                                    <span class="material-symbols-outlined text-xl">layers</span>
                                                </a>
                                            <?php endif; ?>

                                            <a href="<?php echo site_url('assets/distribution/' . $asset->id); ?>"
                                                class="p-2 text-gray-400 hover:bg-gray-100 rounded-lg transition-all"
                                                title="View Batch Distribution">
                                                <span class="material-symbols-outlined text-xl">account_tree</span>
                                            </a>

                                            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                                <button
                                                    onclick="openAllocationModal(<?php echo $asset->id; ?>, '<?php echo addslashes($asset->name); ?>', <?php echo $asset->college_id ? $asset->college_id : 'null'; ?>, <?php echo $asset->quantity; ?>)"
                                                    class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all"
                                                    title="Assign to College">
                                                    <span class="material-symbols-outlined text-xl">business_center</span>
                                                </button>
                                                <a href="<?php echo site_url('assets/delete/' . $asset->id); ?>"
                                                    onclick="return confirm('Move this asset to trash?')"
                                                    class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                    title="Move to Trash">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 opacity-20">
                                    <span class="material-symbols-outlined text-6xl text-gray-200">inventory_2</span>
                                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-gray-400">No assets matching
                                        your filters.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Allocation Modal -->
<div id="allocationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeAllocationModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl transition-all transform scale-95 opacity-0 flex flex-col gap-6"
            id="allocationModalContent">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Assign to College</h3>
                    <p id="allocAssetName" class="text-sm text-primary-600 font-medium mt-1">Asset Name</p>
                </div>
                <button onclick="closeAllocationModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="<?php echo site_url('assets/allocate'); ?>" method="POST" class="space-y-6"
                id="allocationForm">
                <input type="hidden" name="asset_id" id="allocAssetId">
                <div id="bulkIdsContainer"></div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 flex flex-col gap-2">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Target
                            Institution</label>
                        <select name="college_id" id="allocCollegeId"
                            class="w-full py-3.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium text-gray-700">
                            <option value="">Central Storage</option>
                            <?php foreach ($colleges as $coll): ?>
                                <option value="<?php echo $coll->id; ?>"><?php echo $coll->name; ?>
                                    (<?php echo $coll->code; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-span-2 flex flex-col gap-2" id="allocQtyContainer">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Qty to
                            Allocate</label>
                        <div class="relative">
                            <input type="number" name="quantity" id="allocQty" min="1"
                                class="w-full py-3.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-bold text-gray-900 pr-12">
                            <span id="allocMaxQty"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400 uppercase">/
                                0</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-1">Return Date
                        (Optional)</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg">calendar_today</span>
                        <input type="date" name="return_date" id="allocReturnDate"
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium text-gray-700">
                    </div>
                    <p class="text-[10px] text-gray-400 ml-1">Leave blank for permanent allocation.</p>
                </div>

                <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl flex gap-3">
                    <span class="material-symbols-outlined text-blue-600">info</span>
                    <p class="text-xs text-blue-700 leading-relaxed font-medium">
                        Allocating assets will move them from Central Storage to the selected Institution.
                        In bulk allocation, all available stock of the selected items will be moved.
                    </p>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-primary-600 text-white font-black py-3.5 rounded-xl text-xs uppercase tracking-widest hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-base">assignment_turned_in</span> Complete
                        Allocation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Bulk Selection Logic
    const selectAllDetails = document.getElementById('selectAll');
    const assetCheckboxes = document.querySelectorAll('.asset-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkTrashIdsContainer = document.getElementById('bulkTrashIds');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.asset-checkbox:checked');
        const count = checked.length;
        selectedCountSpan.textContent = count;

        // Update bulk trash IDs
        if (bulkTrashIdsContainer) {
            bulkTrashIdsContainer.innerHTML = '';
            checked.forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'asset_ids[]';
                input.value = cb.value;
                bulkTrashIdsContainer.appendChild(input);
            });
        }

        if (count > 0) {
            bulkActionsBar.classList.remove('hidden');
        } else {
            bulkActionsBar.classList.add('hidden');
        }
    }

    selectAllDetails.addEventListener('change', function () {
        const isChecked = this.checked;
        assetCheckboxes.forEach(cb => {
            if (!cb.disabled) cb.checked = isChecked;
        });
        updateBulkBar();
    });

    assetCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkBar));

    function openAllocationModal(id, name, collegeId, maxQty) {
        // Individual Mode
        document.getElementById('modalTitle').textContent = 'Assign to College';
        document.getElementById('allocationForm').action = '<?php echo site_url('assets/allocate'); ?>';
        document.getElementById('allocAssetId').value = id;
        document.getElementById('allocAssetName').textContent = name;
        document.getElementById('allocAssetName').classList.remove('hidden');
        document.getElementById('allocCollegeId').value = collegeId || "";

        document.getElementById('allocQtyContainer').classList.remove('hidden');
        document.getElementById('allocQty').required = true;
        const qtyInput = document.getElementById('allocQty');
        qtyInput.value = maxQty;
        qtyInput.max = maxQty;
        document.getElementById('allocMaxQty').textContent = '/ ' + maxQty;

        document.getElementById('bulkIdsContainer').innerHTML = ''; // Clear bulk

        const modal = document.getElementById('allocationModal');
        const content = document.getElementById('allocationModalContent');

        modal.classList.remove('hidden');
        requestAnimationFrame(() => content.classList.remove('scale-95', 'opacity-0'));
    }

    function openBulkAllocationModal() {
        // Bulk Mode
        const checked = document.querySelectorAll('.asset-checkbox:checked');
        if (checked.length === 0) return;

        document.getElementById('modalTitle').textContent = 'Bulk Assign';
        document.getElementById('allocationForm').action = '<?php echo site_url('assets/bulk_allocate'); ?>';
        document.getElementById('allocAssetName').textContent = checked.length + " Items Selected";

        document.getElementById('allocQtyContainer').classList.add('hidden');
        document.getElementById('allocQty').required = false;

        const container = document.getElementById('bulkIdsContainer');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'asset_ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        const modal = document.getElementById('allocationModal');
        const content = document.getElementById('allocationModalContent');

        modal.classList.remove('hidden');
        requestAnimationFrame(() => content.classList.remove('scale-95', 'opacity-0'));
    }

    function closeAllocationModal() {
        const modal = document.getElementById('allocationModal');
        const content = document.getElementById('allocationModalContent');

        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
</script>