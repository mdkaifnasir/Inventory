<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Activity</h1>
            <p class="text-gray-500 mt-1">Timeline of all administrative actions and system events.</p>
        </div>
        <button onclick="window.print()"
            class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all shadow-sm">
            <span class="material-symbols-outlined text-xl">print</span> Print Log
        </button>
    </div>

    <!-- Activity Timeline / Table -->
</div>

<style>
    @media print {

        /* Hide non-printable elements */
        aside,
        header,
        footer,
        .no-print,
        button,
        .material-symbols-outlined {
            display: none !important;
        }

        /* Reset layout */
        body,
        .content-wrapper,
        .main-content {
            margin: 0;
            padding: 0;
            width: 100%;
            background: white;
        }

        /* Table styling for print */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            color: black !important;
        }

        th {
            background-color: #f3f4f6 !important;
            -webkit-print-color-adjust: exact;
        }

        /* Hide status pill colors, make them plain text */
        span[class*="bg-"] {
            background: none !important;
            border: none !important;
            color: black !important;
            padding: 0;
        }

        /* Ensure text wraps */
        td {
            white-space: normal;
        }
    }
</style>

<!-- Activity Timeline / Table -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden print-border-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 uppercase tracking-widest px-6 py-4">
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400">Timestamp</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400">User</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400">Action</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400">Target</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400">IP Address</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-gray-400 text-right">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($logs as $log): ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">
                                    <?php echo date('M d, Y', strtotime($log->created_at)); ?>
                                </span>
                                <span class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">
                                    <?php echo date('h:i:s A', strtotime($log->created_at)); ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div
                                    class="size-8 rounded-full bg-primary-50 flex items-center justify-center text-primary-600 font-bold text-xs">
                                    <?php echo strtoupper(substr($log->user_name, 0, 1)); ?>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">
                                        <?php echo $log->user_name; ?>
                                    </p>
                                    <p class="text-[10px] text-gray-400">@
                                        <?php echo $log->username; ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase tracking-tighter 
                                <?php
                                if (strpos($log->action, 'Added') !== false)
                                    echo 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                elseif (strpos($log->action, 'Deleted') !== false)
                                    echo 'bg-red-50 text-red-700 border border-red-100';
                                elseif (strpos($log->action, 'Updated') !== false)
                                    echo 'bg-blue-50 text-blue-700 border border-blue-100';
                                else
                                    echo 'bg-gray-50 text-gray-600 border border-gray-100';
                                ?>">
                                <?php echo $log->action; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-medium text-gray-700">
                                <?php echo $log->target_type; ?> #
                                <?php echo $log->target_id; ?>
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="text-xs font-mono text-gray-500 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                                <?php echo $log->ip_address; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($log->details): ?>
                                <button type="button"
                                    class="text-primary-600 hover:text-primary-700 text-xs font-bold uppercase tracking-widest flex items-center gap-1 ml-auto"
                                    onclick="showLogDetails('<?php echo date('M d, H:i', strtotime($log->created_at)); ?>', '<?php echo $log->action; ?>', '<?php echo addslashes(str_replace(array("\r", "\n"), ' ', $log->details)); ?>')">
                                    <span class="material-symbols-outlined text-base">info</span> View
                                </button>
                            <?php else: ?>
                                <span class="text-[10px] text-gray-300 font-bold uppercase tracking-widest">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">history</span>
                            <p class="text-gray-400 font-medium">No system activity logged yet.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Details Modal -->
<div id="logModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all animate-fade-up">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-600">history_edu</span>
                Action Details
            </h3>
            <button onclick="closeLogModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Time &
                    Action</label>
                <p id="modalHeader" class="text-sm font-bold text-gray-800"></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Detailed
                    Log</label>
                <p id="modalDetails" class="text-sm text-gray-600 leading-relaxed font-medium"></p>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button onclick="closeLogModal()"
                class="px-6 py-2 bg-gray-900 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-gray-800 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('logModal');
    const header = document.getElementById('modalHeader');
    const details = document.getElementById('modalDetails');

    function showLogDetails(time, action, text) {
        header.textContent = `${time} - ${action}`;
        details.textContent = text;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLogModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on click outside
    window.onclick = function (event) {
        if (event.target == modal) {
            closeLogModal();
        }
    }
</script>