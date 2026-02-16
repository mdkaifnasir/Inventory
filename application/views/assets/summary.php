<div class="space-y-6 pb-20">
    <div class="flex flex-col gap-1">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Staff Possession Overview</h1>
        <p class="text-gray-500 font-medium">Global distribution of assets across all institutions and staff members.
        </p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <form method="GET" action="<?php echo site_url('assets/summary'); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Search
                        Keywords</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                        <input type="text" name="search"
                            value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                            placeholder="Name, Tag, SN, or Model..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                    </div>
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Category</label>
                    <select name="category_id"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all appearance-none cursor-pointer">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat->id; ?>" <?php echo ($filters['category_id'] == $cat->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Institution -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Allocated
                        College</label>
                    <select name="college_id"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all appearance-none cursor-pointer">
                        <option value="">All Institutions</option>
                        <?php foreach ($colleges as $col): ?>
                            <option value="<?php echo $col->id; ?>" <?php echo ($filters['college_id'] == $col->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($col->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Status</label>
                    <select name="status"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all appearance-none cursor-pointer">
                        <option value="">Any Status</option>
                        <option value="In Stock" <?php echo ($filters['status'] == 'In Stock') ? 'selected' : ''; ?>>In
                            Stock</option>
                        <option value="Deployed" <?php echo ($filters['status'] == 'Deployed') ? 'selected' : ''; ?>>
                            Deployed</option>
                        <option value="Maintenance" <?php echo ($filters['status'] == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                        <option value="Broken" <?php echo ($filters['status'] == 'Broken') ? 'selected' : ''; ?>>Broken
                        </option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <div class="w-full md:w-1/4">
                    <select name="condition"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all appearance-none cursor-pointer">
                        <option value="">Any Condition</option>
                        <option value="Working" <?php echo ($filters['condition'] == 'Working') ? 'selected' : ''; ?>>
                            Working</option>
                        <option value="Non-Working" <?php echo ($filters['condition'] == 'Non-Working') ? 'selected' : ''; ?>>Non-Working</option>
                        <option value="Defective" <?php echo ($filters['condition'] == 'Defective') ? 'selected' : ''; ?>>
                            Defective</option>
                    </select>
                </div>
                <button type="submit"
                    class="flex items-center gap-2 px-8 py-3 bg-primary-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                    <span class="material-symbols-outlined text-xl">filter_list</span>
                    Filter
                </button>
                <a href="<?php echo site_url('assets/summary'); ?>"
                    class="p-3 bg-gray-50 text-gray-400 hover:text-gray-600 rounded-2xl transition-all">
                    <span class="material-symbols-outlined text-2xl">restart_alt</span>
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Deployed Units Card -->
        <?php
        $total_deployed = 0;
        foreach ($summary as $row)
            $total_deployed += $row->total_units;
        ?>
        <div class="bg-amber-50 p-6 rounded-3xl border border-amber-100 shadow-sm">
            <div class="flex items-center gap-4 mb-3">
                <div
                    class="size-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                    <span class="material-symbols-outlined text-2xl">person_check</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest leading-none mb-1">Total
                        Assigned</p>
                    <h3 class="text-3xl font-black text-amber-900 tracking-tighter">
                        <?php echo number_format($total_deployed); ?>
                    </h3>
                </div>
            </div>
            <p class="text-xs font-bold text-amber-700/70 uppercase tracking-widest">Units with Staff/Institutions</p>
        </div>

        <!-- Total Institutions Card -->
        <div class="bg-primary-50 p-6 rounded-3xl border border-primary-100 shadow-sm">
            <div class="flex items-center gap-4 mb-3">
                <div
                    class="size-12 rounded-2xl bg-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-200">
                    <span class="material-symbols-outlined text-2xl">account_balance</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-primary-600 uppercase tracking-widest leading-none mb-1">
                        Institutions</p>
                    <h3 class="text-3xl font-black text-primary-900 tracking-tighter">
                        <?php
                        $inst_count = 0;
                        foreach ($summary as $s)
                            if ($s->record_count > 0)
                                $inst_count++;
                        echo $inst_count;
                        ?>
                    </h3>
                </div>
            </div>
            <p class="text-xs font-bold text-primary-700/70 uppercase tracking-widest">Active Possession Centers</p>
        </div>
    </div>

    <!-- Distribution Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Possession Breakdown</h3>
                <p class="text-sm text-gray-500 font-medium">Summary of assets held by each institution</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                        <th class="px-8 py-5">Institution Name</th>
                        <th class="px-8 py-5">Code</th>
                        <th class="px-8 py-5 text-center">Batch Records</th>
                        <th class="px-8 py-5 text-right">Total Units Possession</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($summary as $row): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-gray-900">
                                    <?php echo $row->name; ?>
                                </p>
                            </td>
                            <td class="px-8 py-6">
                                <span
                                    class="px-3 py-1 text-[10px] font-black rounded-lg bg-primary-50 text-primary-600 border border-primary-100 uppercase">
                                    <?php echo $row->code; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-sm font-bold text-gray-600">
                                    <?php echo number_format($row->record_count); ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <span class="text-xl font-black text-gray-900">
                                        <?php echo number_format($row->total_units); ?>
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Units</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="<?php echo site_url($row->college_id); ?>"
                                    class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-primary-600 hover:bg-primary-600 hover:text-white transition-all shadow-sm">
                                    View Full Profile
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($summary)): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-gray-400 italic">No distribution data
                                available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>