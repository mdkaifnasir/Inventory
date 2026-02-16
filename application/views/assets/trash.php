<div class="space-y-6 pb-20">
    <?php if ($this->session->flashdata('success')): ?>
        <div
            class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3 animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-green-500">check_circle</span>
            <p class="text-sm font-bold">
                <?php echo $this->session->flashdata('success'); ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div
            class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3 animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-red-500">error</span>
            <p class="text-sm font-bold">
                <?php echo $this->session->flashdata('error'); ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div class="flex flex-col gap-1 w-full">
            <h1 class="text-[#111418] text-3xl md:text-4xl font-black leading-tight tracking-tight text-orange-600">
                Inventory Trash</h1>
            <p class="text-[#617589] text-base font-normal leading-normal">Recover or permanently remove items from the
                system</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo site_url('assets'); ?>"
                class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-6 bg-white border border-[#dbe0e6] text-[#111418] text-sm font-bold leading-normal hover:bg-gray-50 transition-colors">
                <span class="material-symbols-outlined mr-2 text-xl">arrow_back</span>
                <span class="truncate">Back to Inventory</span>
            </a>
        </div>
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
            <form id="bulkRestoreForm" action="<?php echo site_url('assets/bulk_restore'); ?>" method="POST"
                onsubmit="return confirm('Restore selected items to active inventory?')">
                <div id="bulkIdsContainerRestore"></div>
                <button type="submit"
                    class="flex items-center gap-2 hover:text-emerald-400 transition-colors font-bold text-sm uppercase tracking-wider">
                    <span class="material-symbols-outlined">restore_from_trash</span> Restore Selected
                </button>
            </form>
            <?php if ($this->session->userdata('role_id') == 1): ?>
                <form id="bulkDeleteForm" action="<?php echo site_url('assets/bulk_permanent_delete'); ?>" method="POST"
                    onsubmit="return confirm('PERMANENTLY DELETE selected items? This cannot be undone!')">
                    <div id="bulkIdsContainerDelete"></div>
                    <button type="submit"
                        class="flex items-center gap-2 hover:text-red-400 transition-colors font-bold text-sm uppercase tracking-wider">
                        <span class="material-symbols-outlined">delete_forever</span> Delete Permanently
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Trashed Table -->
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
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Deleted At
                        </th>
                        <th
                            class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (!empty($assets)): ?>
                        <?php foreach ($assets as $asset): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <?php if (in_array($this->session->userdata('role_id'), [1, 2])): ?>
                                        <input type="checkbox" name="selected_assets[]" value="<?php echo $asset->id; ?>"
                                            class="asset-checkbox w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500 transition-all cursor-pointer">
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-400">
                                            <span class="material-symbols-outlined">delete_sweep</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">
                                                <?php echo $asset->name; ?>
                                            </p>
                                            <p class="text-[11px] text-gray-400">
                                                <?php echo $asset->brand_model; ?>
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
                                    <p class="text-xs font-medium text-gray-600">
                                        <?php
                                        echo ($asset->deleted_at && $asset->deleted_at != '0000-00-00 00:00:00')
                                            ? date('M d, Y H:i', strtotime($asset->deleted_at))
                                            : '<span class="text-gray-400 italic">No date</span>';
                                        ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?php echo site_url('assets/restore/' . $asset->id); ?>"
                                            onclick="return confirm('Restore this item to active inventory?')"
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg font-black text-[10px] uppercase tracking-tight hover:bg-emerald-100 transition-all">
                                            <span class="material-symbols-outlined text-sm">restore_from_trash</span> Restore
                                        </a>
                                        <?php if ($this->session->userdata('role_id') == 1): ?>
                                            <a href="<?php echo site_url('assets/permanent_delete/' . $asset->id); ?>"
                                                onclick="return confirm('PERMANENTLY DELETE this item? This cannot be undone!')"
                                                class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 border border-red-100 rounded-lg font-black text-[10px] uppercase tracking-tight hover:bg-red-100 transition-all">
                                                <span class="material-symbols-outlined text-sm">delete_forever</span> Delete
                                                Permanently
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 opacity-20">
                                    <span class="material-symbols-outlined text-6xl text-gray-200">delete_sweep</span>
                                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-gray-400">Trash is empty.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const selectAll = document.getElementById('selectAll');
    const assetCheckboxes = document.querySelectorAll('.asset-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedCountSpan = document.getElementById('selectedCount');
    const bulkRestoreIdsContainer = document.getElementById('bulkIdsContainerRestore');
    const bulkDeleteIdsContainer = document.getElementById('bulkIdsContainerDelete');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.asset-checkbox:checked');
        const count = checked.length;
        if (selectedCountSpan) selectedCountSpan.textContent = count;

        if (bulkRestoreIdsContainer) bulkRestoreIdsContainer.innerHTML = '';
        if (bulkDeleteIdsContainer) bulkDeleteIdsContainer.innerHTML = '';

        checked.forEach(cb => {
            const id = cb.value;

            if (bulkRestoreIdsContainer) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'asset_ids[]';
                input.value = id;
                bulkRestoreIdsContainer.appendChild(input);
            }

            if (bulkDeleteIdsContainer) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'asset_ids[]';
                input.value = id;
                bulkDeleteIdsContainer.appendChild(input);
            }
        });

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
</div>