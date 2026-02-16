<div class="space-y-8 pb-10">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="<?php echo site_url('assets/summary'); ?>"
                class="p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-primary-600 hover:border-primary-200 transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <div
                        class="size-12 rounded-2xl bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-200">
                        <span class="material-symbols-outlined text-2xl">account_balance</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                            <?php echo $college->name; ?>
                        </h1>
                        <p class="text-xs font-bold text-amber-600 uppercase tracking-widest">
                            <?php echo $college->code; ?> • Institution Profile
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Items
                        Held</p>
                    <p class="text-2xl font-black text-gray-900">
                        <?php echo count($assets); ?>
                    </p>
                </div>
                <div class="size-px h-8 bg-gray-100"></div>
                <div class="text-right">
                    <?php
                    $total_qty = 0;
                    foreach ($assets as $a)
                        $total_qty += $a->quantity;
                    ?>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Total
                        Units</p>
                    <p class="text-2xl font-black text-gray-900">
                        <?php echo number_format($total_qty); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($college->description || $college->principal_name || $college->email): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Description -->
            <div class="md:col-span-2 bg-amber-50 border border-amber-100 p-6 rounded-3xl">
                <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">info</span> Institutional Information
                </h4>
                <p class="text-sm text-amber-900 font-medium leading-relaxed italic opacity-80">
                    "<?php echo $college->description ? $college->description : 'No description provided.'; ?>"
                </p>
            </div>

            <!-- Contact Details -->
            <div class="bg-white border border-gray-100 p-6 rounded-3xl shadow-sm">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">contact_page</span> Contact Details
                </h4>
                <div class="space-y-3">
                    <?php if ($college->principal_name): ?>
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase font-bold text-gray-400">Principal</span>
                            <span class="text-sm font-bold text-gray-900"><?php echo $college->principal_name; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($college->email): ?>
                        <?php
                        $emails = explode('/', $college->email); // Handle multiple emails separated by slash
                        foreach ($emails as $email):
                            ?>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold text-gray-400">Email</span>
                                <a href="mailto:<?php echo trim($email); ?>"
                                    class="text-sm font-medium text-primary-600 hover:underline overflow-hidden text-ellipsis"><?php echo trim($email); ?></a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ($college->mobile): ?>
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase font-bold text-gray-400">Mobile</span>
                            <span class="text-sm font-mono text-gray-700"><?php echo $college->mobile; ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filter Bar -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <form method="GET" action="<?php echo current_url(); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Search
                        Assets</label>
                    <div class="relative">
                        <span
                            class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">search</span>
                        <input type="text" name="search"
                            value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                            placeholder="Name, Tag, or SN..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all">
                    </div>
                </div>

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
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-1">Condition</label>
                    <select name="condition"
                        class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary-500 transition-all appearance-none cursor-pointer">
                        <option value="">Any Condition</option>
                        <option value="Working" <?php echo ($filters['condition'] == 'Working') ? 'selected' : ''; ?>>
                            Working</option>
                        <option value="Non-Working" <?php echo ($filters['condition'] == 'Non-Working') ? 'selected' : ''; ?>>Non-Working</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="flex items-center gap-2 px-8 py-3 bg-primary-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                    <span class="material-symbols-outlined text-xl">filter_list</span>
                    Filter Possession
                </button>
                <a href="<?php echo site_url($college->id); ?>"
                    class="p-3 bg-gray-50 text-gray-400 hover:text-gray-600 rounded-2xl transition-all">
                    <span class="material-symbols-outlined text-2xl">restart_alt</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Detailed Inventory Statistics ("The Detail Way") -->
    <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-2xl overflow-hidden relative">
        <div class="absolute top-0 right-0 p-12 opacity-10 pointer-events-none">
            <span class="material-symbols-outlined text-[120px]">leaderboard</span>
        </div>

        <div class="relative z-10">
            <div class="mb-8">
                <h3 class="text-xl font-black tracking-tight">Detailed Inventory Breakdown</h3>
                <p class="text-gray-400 text-sm font-medium">Categorized summary of items held by
                    <?php echo $college->name; ?></p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                <?php
                $cat_summary = [];
                foreach ($assets as $a) {
                    if (!isset($cat_summary[$a->category_name])) {
                        $cat_summary[$a->category_name] = ['count' => 0, 'units' => 0];
                    }
                    $cat_summary[$a->category_name]['count']++;
                    $cat_summary[$a->category_name]['units'] += $a->quantity;
                }
                ksort($cat_summary);

                foreach ($cat_summary as $name => $stats):
                    ?>
                    <div class="bg-white/5 border border-white/10 p-5 rounded-3xl hover:bg-white/10 transition-all group">
                        <p class="text-[10px] font-black text-primary-400 uppercase tracking-widest mb-3">
                            <?php echo $name; ?></p>
                        <div class="flex items-baseline gap-2">
                            <span
                                class="text-2xl font-black tracking-tighter"><?php echo number_format($stats['units']); ?></span>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Units</span>
                        </div>
                        <p class="text-[10px] font-medium text-gray-500 mt-1"><?php echo $stats['count']; ?> Batch Records
                        </p>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($cat_summary)): ?>
                    <div class="col-span-full py-10 text-center text-gray-600 italic">No items matching current filters.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Assets Possession List -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <div>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Active Possession Log</h3>
                <p class="text-sm text-gray-500 font-medium">Detailed breakdown of assets currently held by this staff
                    member/unit</p>
            </div>
            <a href="<?php echo site_url('colleges/print_qrs/' . $college->id); ?>" target="_blank"
                class="flex items-center gap-2 bg-gray-900 px-5 py-2.5 rounded-xl text-xs font-black text-white hover:bg-black transition-all shadow-xl shadow-gray-200">
                <span class="material-symbols-outlined text-lg">print</span> Print All QR Codes
            </a>
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

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                        <th class="px-8 py-5 w-12">
                            <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                <input type="checkbox" id="selectAll"
                                    class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-all cursor-pointer">
                            <?php endif; ?>
                        </th>
                        <th class="px-8 py-5">Asset Information</th>
                        <th class="px-8 py-5">Category</th>
                        <th class="px-8 py-5 text-center">Batch Qty</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Identifier</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($assets as $asset): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                    <input type="checkbox" name="selected_assets[]" value="<?php echo $asset->id; ?>"
                                        class="asset-checkbox w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-all cursor-pointer">
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="size-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                                        <span class="material-symbols-outlined">devices</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-gray-900">
                                            <?php echo $asset->name; ?>
                                        </p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                            <?php echo $asset->brand_model; ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span
                                    class="px-3 py-1 text-[10px] font-black rounded-lg bg-primary-50 text-primary-600 border border-primary-100 uppercase">
                                    <?php echo $asset->category_name; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="text-sm font-bold text-gray-900">
                                    <?php echo $asset->quantity; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="size-1.5 rounded-full <?php echo ($asset->status == 'On Loan') ? 'bg-orange-500 shadow-[0_0_5px_rgba(249,115,22,0.5)]' : 'bg-amber-500 shadow-[0_0_5px_rgba(245,158,11,0.5)]'; ?>"></span>
                                        <span
                                            class="text-[11px] font-black <?php echo ($asset->status == 'On Loan') ? 'text-orange-600' : 'text-amber-600'; ?> uppercase tracking-tighter">
                                            <?php echo $asset->status; ?>
                                        </span>
                                    </div>
                                    <?php if ($asset->return_date): ?>
                                        <p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest pl-3">Due:
                                            <?php echo date('M d, Y', strtotime($asset->return_date)); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <p
                                    class="text-xs font-mono font-bold text-gray-600 bg-gray-50 px-3 py-1 rounded-lg border border-gray-100 inline-block">
                                    <?php echo $asset->asset_tag; ?>
                                </p>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="viewAssetDetails(<?php echo htmlspecialchars(json_encode($asset)); ?>)"
                                        class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-all"
                                        title="View QR & Technical Info">
                                        <span class="material-symbols-outlined text-xl">visibility</span>
                                    </button>
                                    <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                        <a href="<?php echo site_url('assets/delete/' . $asset->id); ?>"
                                            onclick="return confirm('Move this asset to trash?')"
                                            class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                            title="Move to Trash">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($assets)): ?>
                        <tr>
                            <td colspan="7" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 opacity-20">
                                    <span class="material-symbols-outlined text-6xl">inventory</span>
                                    <p class="text-sm font-bold uppercase tracking-[0.2em]">Zero Items under Possession</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reusable Asset Details Modal (Duplicate logic for seamless experience) -->
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
                <div>
                    <label
                        class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-2">Condition</label>
                    <div id="m_condition"
                        class="inline-flex px-4 py-2 rounded-xl bg-orange-50 text-orange-600 text-xs font-black uppercase tracking-tighter">
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center justify-center bg-gray-50 rounded-3xl p-6 border border-gray-100">
                <div id="m_qrcode" class="mb-4 bg-white p-3 rounded-2xl shadow-sm"></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Institutional ID
                    Code</p>
            </div>
        </div>
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex justify-end">
            <button onclick="closeAssetModal()"
                class="px-8 py-3 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-xl shadow-gray-200">Dismiss</button>
        </div>
    </div>
</div>

<script>
    function viewAssetDetails(asset) {
        document.getElementById('m_asset_name').textContent = asset.name;
        document.getElementById('m_asset_tag').textContent = asset.asset_tag;
        document.getElementById('m_cat').textContent = asset.category_name;
        document.getElementById('m_brand').textContent = asset.brand_model || 'N/A';
        document.getElementById('m_sn').textContent = asset.serial_number || 'N/A';
        document.getElementById('m_condition').textContent = asset.asset_condition || 'New';

        const container = document.getElementById('m_qrcode');
        container.innerHTML = "";
        if (typeof QRCode !== 'undefined') {
            new QRCode(container, {
                text: `<?php echo site_url('assets/view_details/'); ?>${asset.asset_tag}`,
                width: 140,
                height: 140,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }
        document.getElementById('assetModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAssetModal() {
        document.getElementById('assetModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Bulk Selection Logic
    const selectAll = document.getElementById('selectAll');
    const assetCheckboxes = document.querySelectorAll('.asset-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkTrashIdsContainer = document.getElementById('bulkTrashIds');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.asset-checkbox:checked');
        const count = checked.length;
        if (selectedCountSpan) selectedCountSpan.textContent = count;

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

        if (bulkActionsBar) {
            if (count > 0) {
                bulkActionsBar.classList.remove('hidden');
            } else {
                bulkActionsBar.classList.add('hidden');
            }
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            const isChecked = this.checked;
            assetCheckboxes.forEach(cb => {
                if (!cb.disabled) cb.checked = isChecked;
            });
            updateBulkBar();
        });
    }

    assetCheckboxes.forEach(cb => cb.addEventListener('change', updateBulkBar));
</script>