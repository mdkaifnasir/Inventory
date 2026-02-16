<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="<?php echo site_url('reports'); ?>" class="text-gray-500 hover:text-gray-700">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Issue Reports</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1 ml-8">Filter and export maintenance issue logs.</p>
        </div>

        <a href="<?php echo site_url('reports/export_issues?' . http_build_query($filters)); ?>" target="_blank"
            class="flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[18px]">download</span>
            Export CSV
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <form action="<?php echo site_url('reports/issues'); ?>" method="get"
            class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                    <option value="">All Statuses</option>
                    <option value="Pending" <?php echo ($this->input->get('status') == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="In Progress" <?php echo ($this->input->get('status') == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="Resolved" <?php echo ($this->input->get('status') == 'Resolved') ? 'selected' : ''; ?>>Resolved</option>
                </select>
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="block text-xs font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" id="priority"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                    <option value="">All Priorities</option>
                    <option value="Low" <?php echo ($this->input->get('priority') == 'Low') ? 'selected' : ''; ?>>Low
                    </option>
                    <option value="Medium" <?php echo ($this->input->get('priority') == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                    <option value="High" <?php echo ($this->input->get('priority') == 'High') ? 'selected' : ''; ?>>High
                    </option>
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" id="start_date"
                    value="<?php echo $this->input->get('start_date'); ?>"
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
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
                        <th class="px-6 py-4">Asset</th>
                        <th class="px-6 py-4">Reported By</th>
                        <th class="px-6 py-4">Technician</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Priority</th>
                        <th class="px-6 py-4">Reported Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($issues)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm">
                                No issues found matching criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($issues as $issue): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                <?php echo $issue['asset_name']; ?>
                                            </p>
                                            <p class="text-xs text-gray-500 font-mono">
                                                <?php echo $issue['asset_tag']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo $issue['reported_by'] ?? $issue['staff_name'] ?? 'Unknown'; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo $issue['technician_name'] ?? '-'; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $status_colors = [
                                        'Pending' => 'bg-orange-100 text-orange-700',
                                        'In Progress' => 'bg-blue-100 text-blue-700',
                                        'Resolved' => 'bg-green-100 text-green-700'
                                    ];
                                    $color = isset($status_colors[$issue['status']]) ? $status_colors[$issue['status']] : 'bg-gray-100 text-gray-700';
                                    ?>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?php echo $color; ?>">
                                        <?php echo $issue['status']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $priority_colors = [
                                        'Low' => 'text-gray-500',
                                        'Medium' => 'text-orange-500',
                                        'High' => 'text-red-500 font-bold'
                                    ];
                                    $p_color = isset($priority_colors[$issue['priority']]) ? $priority_colors[$issue['priority']] : 'text-gray-500';
                                    ?>
                                    <span class="text-xs <?php echo $p_color; ?>">
                                        <?php echo $issue['priority']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?php echo date('M d, Y', strtotime($issue['created_at'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>