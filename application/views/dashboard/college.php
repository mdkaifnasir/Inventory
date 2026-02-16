<div class="space-y-8 pb-10">
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">College Dashboard</h1>
            <p class="text-gray-500 mt-1 font-medium">Overview of assets assigned to <span
                    class="text-primary-600 font-bold">
                    <?php echo $college_name; ?>
                </span></p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo site_url('assets'); ?>"
                class="flex items-center gap-2 bg-primary-600 px-5 py-2.5 rounded-xl text-sm font-bold text-white hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                <span class="material-symbols-outlined text-xl">inventory_2</span> My Assets
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Assets -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-2xl">laptop_mac</span>
                </div>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter">
                <?php echo number_format($counts['total_assets']); ?>
            </h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Total Items in Possession</p>
        </div>

        <!-- In Good Condition -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-2xl">check_circle</span>
                </div>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter">
                <?php echo number_format($counts['good_condition']); ?>
            </h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Functional Equipment</p>
        </div>

        <!-- Due for Return (Loans) -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-2xl">event_busy</span>
                </div>
            </div>
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter">
                <?php echo number_format($counts['due_soon']); ?>
            </h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-2">Loans Due Soon</p>
        </div>
    </div>

    <!-- Detailed Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Category Breakdown -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-black text-gray-900 tracking-tight mb-6">Inventory by Category</h3>
            <div class="space-y-4">
                <?php foreach ($categories as $cat): ?>
                    <?php if ($cat->count > 0): ?>
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div
                                    class="size-8 rounded-lg bg-white border border-gray-100 flex items-center justify-center text-primary-600">
                                    <span class="material-symbols-outlined text-lg">category</span>
                                </div>
                                <span class="text-sm font-bold text-gray-700">
                                    <?php echo $cat->category; ?>
                                </span>
                            </div>
                            <span class="text-sm font-black text-gray-900">
                                <?php echo $cat->count; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (empty($categories)): ?>
                    <p class="text-gray-400 text-sm text-center">No categories found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Arrivals -->
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-lg font-black text-gray-900 tracking-tight mb-6">Recent Allocations</h3>
            <div class="space-y-4">
                <?php foreach ($recent_assets as $asset): ?>
                    <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div
                            class="size-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600 shrink-0">
                            <span class="material-symbols-outlined">inventory_2</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">
                                <?php echo $asset->name; ?>
                            </p>
                            <p class="text-xs text-gray-500 font-mono">
                                <?php echo $asset->asset_tag; ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-2 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded uppercase">Received</span>
                            <p class="text-[10px] text-gray-400 mt-1">
                                <?php echo date('M d', strtotime($asset->updated_at)); ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($recent_assets)): ?>
                    <div class="text-center py-8 text-gray-400">
                        <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                        <p class="text-sm">No recent items received.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>