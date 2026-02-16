<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="<?php echo site_url('reports'); ?>" class="text-gray-500 hover:text-gray-700">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Asset Reports</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1 ml-8">Filter and export asset inventory data.</p>
        </div>

        <a href="<?php echo site_url('reports/export_assets?' . http_build_query($filters)); ?>" target="_blank"
            class="flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[18px]">download</span>
            Export CSV
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <form action="<?php echo site_url('reports/assets'); ?>" method="get"
            class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" id="category_id"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo ($this->input->get('category_id') == $category->id) ? 'selected' : ''; ?>>
                            <?php echo $category->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                    <option value="">All Statuses</option>
                    <option value="In Stock" <?php echo ($this->input->get('status') == 'In Stock') ? 'selected' : ''; ?>>In Stock</option>
                    <option value="Deployed" <?php echo ($this->input->get('status') == 'Deployed') ? 'selected' : ''; ?>>Deployed</option>
                    <option value="On Loan" <?php echo ($this->input->get('status') == 'On Loan') ? 'selected' : ''; ?>>On Loan</option>
                    <option value="Maintenance" <?php echo ($this->input->get('status') == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                    <option value="Retired" <?php echo ($this->input->get('status') == 'Retired') ? 'selected' : ''; ?>>Retired</option>
                </select>
            </div>

            <!-- College -->
            <div>
                <label for="college_id" class="block text-xs font-medium text-gray-700 mb-1">College/Institute</label>
                <select name="college_id" id="college_id"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                    <option value="">All Institutes</option>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?php echo $college->id; ?>" <?php echo ($this->input->get('college_id') == $college->id) ? 'selected' : ''; ?>>
                            <?php echo $college->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit"
                    class="w-full px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                        <th class="px-6 py-4">Asset Name</th>
                        <th class="px-6 py-4">Tag</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Institute</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Added Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($assets)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
                                No assets found matching criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($assets as $asset): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-primary-50 flex items-center justify-center text-primary-600 font-bold text-xs">
                                            <?php echo substr($asset->name, 0, 1); ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                <?php echo $asset->name; ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?php echo $asset->asset_condition; ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-xs font-mono text-gray-700">
                                        <?php echo $asset->asset_tag; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo $asset->category_name; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo $asset->college_name; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $status_colors = [
                                        'In Stock' => 'bg-green-100 text-green-700',
                                        'Deployed' => 'bg-blue-100 text-blue-700',
                                        'On Loan' => 'bg-orange-100 text-orange-700',
                                        'Maintenance' => 'bg-red-100 text-red-700',
                                        'Retired' => 'bg-gray-100 text-gray-700'
                                    ];
                                    $color = isset($status_colors[$asset->status]) ? $status_colors[$asset->status] : 'bg-gray-100 text-gray-700';
                                    ?>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo $color; ?>">
                                        <?php echo $asset->status; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo date('M d, Y', strtotime($asset->created_at)); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>