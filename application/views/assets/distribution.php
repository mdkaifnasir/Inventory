<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?php echo site_url('assets'); ?>"
            class="p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-primary-600 hover:border-primary-200 transition-all">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Batch Distribution</h1>
            <p class="text-gray-500 font-medium">Tracking lifecycle for: <span class="text-primary-600">
                    <?php echo $root_asset->name; ?>
                </span></p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php
        $total_qty = 0;
        $in_stock = 0;
        $deployed = 0;
        foreach ($distribution as $item) {
            $total_qty += $item->quantity;
            if ($item->status == 'In Stock')
                $in_stock += $item->quantity;
            else
                $deployed += $item->quantity;
        }
        ?>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Batch Quantity</p>
            <p class="text-3xl font-black text-gray-900">
                <?php echo $total_qty; ?>
            </p>
            <div class="mt-2 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary-500" style="width: 100%"></div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Available In Stock</p>
            <p class="text-3xl font-black text-emerald-600">
                <?php echo $in_stock; ?>
            </p>
            <div class="mt-2 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500"
                    style="width: <?php echo ($total_qty > 0) ? ($in_stock / $total_qty) * 100 : 0; ?>%"></div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Currently Deployed</p>
            <p class="text-3xl font-black text-blue-600">
                <?php echo $deployed; ?>
            </p>
            <div class="mt-2 h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500"
                    style="width: <?php echo ($total_qty > 0) ? ($deployed / $total_qty) * 100 : 0; ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Distribution Timeline/List -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/30">
            <h3 class="font-bold text-gray-900">Allocation Breakdown</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6 relative">
                <!-- Vertical Line -->
                <div class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-gray-100"></div>

                <?php foreach ($distribution as $index => $item): ?>
                    <div class="relative pl-12">
                        <!-- Dot -->
                        <div
                            class="absolute left-0 top-1.5 size-10 rounded-full border-4 border-white bg-<?php echo $item->status == 'In Stock' ? 'emerald' : 'blue'; ?>-50 flex items-center justify-center z-10 shadow-sm">
                            <span
                                class="material-symbols-outlined text-<?php echo $item->status == 'In Stock' ? 'emerald' : 'blue'; ?>-600 text-xl">
                                <?php echo $item->status == 'In Stock' ? 'inventory_2' : 'business_center'; ?>
                            </span>
                        </div>

                        <div
                            class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 flex flex-wrap justify-between items-center gap-4 hover:border-primary-200 transition-colors">
                            <div class="flex-1 min-w-[200px]">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-gray-900 uppercase">
                                        <?php echo $item->college_name ? $item->college_name : 'Central Storage'; ?>
                                    </span>
                                    <?php if ($index == 0): ?>
                                        <span
                                            class="text-[9px] px-1.5 py-0.5 bg-primary-100 text-primary-700 font-black rounded uppercase">Source
                                            Batch</span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-black text-gray-900">
                                        <?php echo $item->quantity; ?> Units
                                    </span>
                                    <span class="text-[11px] font-mono text-gray-400">
                                        <?php echo $item->asset_tag; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        Added/Allocated</p>
                                    <p class="text-xs font-bold text-gray-700">
                                        <?php echo date('M d, Y', strtotime($item->created_at)); ?>
                                    </p>
                                </div>
                                <?php if ($item->return_date): ?>
                                    <div class="text-right">
                                        <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Return Due
                                        </p>
                                        <p class="text-xs font-bold text-orange-600">
                                            <?php echo date('M d, Y', strtotime($item->return_date)); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                <div class="flex flex-col items-end gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter <?php
                                        if ($item->status == 'In Stock')
                                            echo 'bg-emerald-100 text-emerald-700';
                                        elseif ($item->status == 'On Loan')
                                            echo 'bg-orange-100 text-orange-700';
                                        else
                                            echo 'bg-blue-100 text-blue-700';
                                        ?>">
                                            <?php echo $item->status; ?>
                                        </span>
                                        <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                            <a href="<?php echo site_url('assets/delete/' . $item->id); ?>"
                                                onclick="return confirm('Move this unit to trash?')"
                                                class="p-1.5 text-red-400 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all"
                                                title="Move to Trash">
                                                <span class="material-symbols-outlined text-base">delete</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($item->status != 'In Stock'): ?>
                                        <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                            <div class="flex items-center gap-2">
                                                <button
                                                    onclick="openPartialModal('<?php echo $item->id; ?>', <?php echo $item->quantity; ?>)"
                                                    class="px-2 py-1 bg-white border border-gray-200 text-gray-600 rounded text-[10px] font-bold hover:bg-gray-50 uppercase tracking-wide">
                                                    Partial
                                                </button>
                                                <button
                                                    onclick="openExtendModal('<?php echo $item->id; ?>', '<?php echo $item->return_date ? date('Y-m-d', strtotime($item->return_date)) : ''; ?>')"
                                                    class="px-2 py-1 bg-white border border-gray-200 text-gray-600 rounded text-[10px] font-bold hover:bg-gray-50 uppercase tracking-wide">
                                                    Extend
                                                </button>
                                                <a href="<?php echo site_url('assets/return_item/' . $item->id); ?>"
                                                    onclick="return confirm('Are you sure you want to return this entire batch to stock?')"
                                                    class="px-2 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded text-[10px] font-bold hover:bg-emerald-100 uppercase tracking-wide flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[10px]">undo</span> Return
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Visual Breakdown by Location -->
    <div class="bg-gray-900 rounded-3xl p-10 text-white overflow-hidden relative">
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1 space-y-4 text-center md:text-left">
                <h2 class="text-3xl font-black tracking-tight leading-tight">Geographic Distribution</h2>
                <p class="text-gray-400 font-medium max-w-md">This view shows the proportional split between internal
                    storage and educational institutions.</p>
            </div>

            <div class="flex gap-4 items-end h-48">
                <?php
                $locations = [];
                foreach ($distribution as $item) {
                    $loc_name = $item->college_code ? $item->college_code : 'STOCK';
                    if (!isset($locations[$loc_name]))
                        $locations[$loc_name] = 0;
                    $locations[$loc_name] += $item->quantity;
                }
                foreach ($locations as $name => $qty):
                    $percent = ($total_qty > 0) ? ($qty / $total_qty) * 100 : 0;
                    ?>
                    <div class="flex flex-col items-center gap-3 group">
                        <div class="w-16 bg-primary-500 rounded-t-xl group-hover:bg-primary-400 transition-all duration-500 relative flex items-end justify-center"
                            style="height: <?php echo max(10, $percent); ?>%">
                            <span
                                class="absolute -top-6 text-[10px] font-black opacity-0 group-hover:opacity-100 transition-opacity">
                                <?php echo $qty; ?>
                            </span>
                        </div>
                        <span class="text-[10px] font-black tracking-widest text-gray-500">
                            <?php echo $name; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Background Decoration -->
        <div class="absolute -right-20 -bottom-20 size-64 bg-primary-500/10 rounded-full blur-3xl"></div>
    </div>
</div>

<!-- Extend Loan Modal -->
<div id="extendModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl transform transition-all">
        <form action="<?php echo site_url('assets/extend_loan'); ?>" method="post">
            <div class="p-6">
                <h3 class="text-lg font-black text-gray-900 mb-2">Extend Loan Duration</h3>
                <p class="text-sm text-gray-500 mb-6">Update the expected return date for this allocation.</p>

                <input type="hidden" name="asset_id" id="extend_asset_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">New Return Date</label>
                        <input type="date" name="return_date" id="extend_date" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-sm font-medium text-gray-900">
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="document.getElementById('extendModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-primary-600 text-white rounded-xl font-bold text-sm hover:bg-primary-500 shadow-lg shadow-primary-500/30 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Partial Return Modal -->
<div id="partialModal"
    class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl transform transition-all">
        <form action="<?php echo site_url('assets/partial_return'); ?>" method="post">
            <div class="p-6">
                <h3 class="text-lg font-black text-gray-900 mb-2">Partial Return</h3>
                <p class="text-sm text-gray-500 mb-6">Specify how many units are being returned to stock.</p>

                <input type="hidden" name="asset_id" id="partial_asset_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Quantity to Return</label>
                        <input type="number" name="quantity" id="partial_qty" required min="1"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all text-sm font-medium text-gray-900">
                        <p class="mt-2 text-xs text-gray-400">Max available: <span id="max_qty_display"></span></p>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button type="button" onclick="document.getElementById('partialModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-500 shadow-lg shadow-emerald-500/30 transition-all">
                    Confirm Return
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openExtendModal(id, current) {
        document.getElementById('extend_asset_id').value = id;
        if (current) document.getElementById('extend_date').value = current;
        document.getElementById('extendModal').classList.remove('hidden');
    }

    function openPartialModal(id, maxQty) {
        document.getElementById('partial_asset_id').value = id;
        document.getElementById('partial_qty').max = maxQty - 1; // Can't return all, use full return for that
        document.getElementById('partial_qty').value = 1;
        document.getElementById('max_qty_display').innerText = maxQty;
        document.getElementById('partialModal').classList.remove('hidden');
    }
</script>