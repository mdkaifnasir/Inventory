<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Issue Reports</h1>
            <p class="text-gray-500 mt-1">Track and manage reported asset issues.</p>
        </div>
        <a href="<?php echo site_url('issues/report'); ?>"
            class="bg-red-600 text-white font-bold py-2.5 px-6 rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200 text-xs uppercase tracking-widest flex items-center gap-2">
            <span class="material-symbols-outlined text-base">add_alert</span> Report Issue
        </a>
    </div>

    <!-- Mobile Card View (Visible on small screens) -->
    <div class="grid grid-cols-1 gap-4 md:hidden">
        <?php if (empty($issues)): ?>
            <div class="bg-white p-8 rounded-2xl text-center border border-gray-100 shadow-sm">
                <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">assignment_turned_in</span>
                <p class="text-gray-500">No issues reported yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($issues as $issue): ?>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-900 text-lg"><?php echo $issue->asset_name; ?></span>
                            <span
                                class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded-md w-fit mt-1"><?php echo $issue->asset_tag; ?></span>
                        </div>
                        <?php
                        $status_classes = [
                            'Pending' => 'bg-orange-50 text-orange-700 border-orange-100',
                            'In Progress' => 'bg-blue-50 text-blue-700 border-blue-100',
                            'Resolved' => 'bg-green-50 text-green-700 border-green-100',
                            'Closed' => 'bg-gray-50 text-gray-600 border-gray-100',
                        ];
                        $cls = isset($status_classes[$issue->status]) ? $status_classes[$issue->status] : 'bg-gray-100 text-gray-600';
                        ?>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold border <?php echo $cls; ?>">
                            <?php echo $issue->status; ?>
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-700 line-clamp-2"><?php echo $issue->issue_description; ?></p>
                        <?php if ($issue->photo_path): ?>
                            <a href="<?php echo base_url($issue->photo_path); ?>" target="_blank"
                                class="text-xs text-primary-600 hover:underline flex items-center gap-1 mt-2">
                                <span class="material-symbols-outlined text-sm">image</span> View Photo
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-500 border-t border-gray-50 pt-3">
                        <div class="flex flex-col gap-1">
                            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">person</span>
                                <?php echo $issue->staff_name; ?></span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-sm">calendar_today</span>
                                <?php echo date('M d', strtotime($issue->created_at)); ?></span>
                        </div>

                        <?php if ($issue->technician_name): ?>
                            <span class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-lg">
                                <span class="material-symbols-outlined text-sm text-gray-400">engineering</span>
                                <span class="font-medium text-gray-700"><?php echo $issue->technician_name; ?></span>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex gap-2 mt-2">
                        <button onclick='openInfoModal(<?php echo json_encode($issue); ?>)'
                            class="flex-1 bg-gray-50 text-gray-700 py-2 rounded-xl text-sm font-bold hover:bg-gray-100 transition-colors">
                            Details
                        </button>

                        <?php if ($issue->status == 'Pending' && in_array($this->session->userdata('role_id'), [1, 2, 3])): ?>
                            <a href="<?php echo site_url('issues/take_issue/' . $issue->id); ?>"
                                class="flex-1 bg-blue-600 text-white py-2 rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors text-center">
                                Take
                            </a>
                        <?php endif; ?>

                        <?php if ($issue->status == 'In Progress' && ($issue->technician_id == $this->session->userdata('user_id') || in_array($this->session->userdata('role_id'), [1, 2]))): ?>
                            <button onclick="openResolveModal('<?php echo $issue->id; ?>')"
                                class="flex-1 bg-emerald-600 text-white py-2 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-colors">
                                Resolve
                            </button>
                        <?php endif; ?>

                        <?php
                        // Soft Delete Button Logic
                        $user_id = $this->session->userdata('user_id');
                        $role_id = $this->session->userdata('role_id');
                        $can_delete = ($role_id == 1 || $role_id == 2 || $issue->reported_by_user_id == $user_id);
                        ?>
                        <?php if ($can_delete && $issue->status != 'Deleted'): ?>
                            <a href="<?php echo site_url('issues/delete/' . $issue->id); ?>"
                                onclick="return confirm('Are you sure you want to delete this report?')"
                                class="flex-1 bg-red-50 text-red-600 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition-colors text-center">
                                Delete
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Desktop Table View (Hidden on mobile) -->
    <div class="hidden md:block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase text-gray-500 font-bold tracking-wider">
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Asset</th>
                        <th class="px-6 py-4 w-1/4">Issue</th>
                        <th class="px-6 py-4">Reported By</th>
                        <th class="px-6 py-4">Technician</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($issues)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <span
                                    class="material-symbols-outlined text-4xl text-gray-300 mb-2">assignment_turned_in</span>
                                <p>No issues reported yet.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($issues as $issue): ?>
                            <tr
                                class="hover:bg-gray-50/50 transition-colors group <?php echo ($issue->status == 'Deleted') ? 'opacity-50 bg-gray-50' : ''; ?>">
                                <td class="px-6 py-4">
                                    <?php
                                    $status_classes = [
                                        'Pending' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'In Progress' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Resolved' => 'bg-green-50 text-green-700 border-green-100',
                                        'Closed' => 'bg-gray-50 text-gray-600 border-gray-100',
                                        'Deleted' => 'bg-red-50 text-red-600 border-red-100 line-through',
                                    ];
                                    $cls = isset($status_classes[$issue->status]) ? $status_classes[$issue->status] : 'bg-gray-100 text-gray-600';
                                    ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border <?php echo $cls; ?>">
                                        <?php echo $issue->status; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900"><?php echo $issue->asset_name; ?></span>
                                        <span class="text-xs text-gray-500 font-mono"><?php echo $issue->asset_tag; ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 max-w-xs truncate"
                                        title="<?php echo $issue->issue_description; ?>">
                                        <?php echo $issue->issue_description; ?>
                                    </p>
                                    <?php if ($issue->photo_path): ?>
                                        <a href="<?php echo base_url($issue->photo_path); ?>" target="_blank"
                                            class="text-xs text-primary-600 hover:underline flex items-center gap-1 mt-1">
                                            <span class="material-symbols-outlined text-sm">image</span> View Photo
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900 font-medium"><?php echo $issue->staff_name; ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($issue->technician_name): ?>
                                        <div class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-gray-400 text-sm">person</span>
                                            <span
                                                class="text-sm text-gray-700 font-medium"><?php echo $issue->technician_name; ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic">Unassigned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($issue->created_at)); ?></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick='openInfoModal(<?php echo json_encode($issue); ?>)'
                                            class="p-2 text-gray-400 hover:text-primary-600 hover:bg-gray-100 rounded-lg transition-all"
                                            title="View Full Details">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </button>

                                        <?php if ($issue->status == 'Pending' && in_array($this->session->userdata('role_id'), [1, 2, 3])): ?>
                                            <a href="<?php echo site_url('issues/take_issue/' . $issue->id); ?>"
                                                class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition-all flex items-center gap-1 shadow-sm">
                                                <span class="material-symbols-outlined text-sm">front_hand</span> Take
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($issue->status == 'In Progress' && ($issue->technician_id == $this->session->userdata('user_id') || in_array($this->session->userdata('role_id'), [1, 2]))): ?>
                                            <button onclick="openResolveModal('<?php echo $issue->id; ?>')"
                                                class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition-all flex items-center gap-1 shadow-sm">
                                                <span class="material-symbols-outlined text-sm">check_circle</span> Resolve
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($issue->status == 'Resolved'): ?>
                                            <span class="text-xs text-emerald-600 font-bold flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">verified</span> Fixed
                                            </span>
                                        <?php endif; ?>

                                        <?php
                                        // Soft Delete Button Logic
                                        $user_id = $this->session->userdata('user_id');
                                        $role_id = $this->session->userdata('role_id');
                                        $can_delete = ($role_id == 1 || $role_id == 2 || $issue->reported_by_user_id == $user_id);
                                        ?>
                                        <?php if ($can_delete && $issue->status != 'Deleted'): ?>
                                            <a href="<?php echo site_url('issues/delete/' . $issue->id); ?>"
                                                onclick="return confirm('Are you sure you want to delete this report?')"
                                                class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition-all flex items-center gap-1 shadow-sm">
                                                <span class="material-symbols-outlined text-sm">delete</span> Delete
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Resolution Modal -->
<div id="resolveModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">

                <?php echo form_open_multipart('issues/submit_resolution', ['class' => 'p-6 space-y-4']); ?>
                <input type="hidden" name="issue_id" id="modal_issue_id">

                <div class="border-b border-gray-100 pb-4 flex justify-between items-center">
                    <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Resolve Issue</h3>
                    <button type="button" onclick="closeResolveModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Work Description / Action Taken</label>
                        <textarea name="resolution_description" rows="3" required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"
                            placeholder="What did you do to fix it?"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Condition After Fix</label>
                        <select name="condition_after_fix" required
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            <option value="Working">Working / Good</option>
                            <option value="Refurbished">Refurbished</option>
                            <option value="Partially Working">Partially Working (Minor Issues)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700">Proof of Fix (Photo)</label>
                        <div
                            class="mt-1 flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 pt-5 pb-6 bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer group">
                            <div class="space-y-1 text-center">
                                <span
                                    class="material-symbols-outlined text-gray-400 group-hover:text-emerald-500 text-3xl">add_a_photo</span>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="resolution_photo"
                                        class="relative cursor-pointer rounded-md bg-transparent font-medium text-emerald-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-emerald-500 focus-within:ring-offset-2 hover:text-emerald-500">
                                        <span>Upload a file</span>
                                        <input id="resolution_photo" name="resolution_photo" type="file" class="sr-only"
                                            accept="image/*" required>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 flex gap-3">
                    <button type="button" onclick="closeResolveModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-xl bg-emerald-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600 sm:w-auto ml-auto">
                        Mark as Resolved
                    </button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="infoModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold leading-6 text-gray-900">Issue Details</h3>
                    <button type="button" onclick="closeInfoModal()" class="text-gray-400 hover:text-gray-500">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <div class="px-6 py-6 space-y-6">
                    <!-- Asset Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Asset</p>
                            <p class="text-gray-900 font-medium" id="modal_asset_name"></p>
                            <p class="text-sm text-gray-500" id="modal_asset_tag"></p>
                        </div>
                        <div class="text-right">
                            <span id="modal_status_badge"
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border"></span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-2">Issue Description</p>
                        <p class="text-gray-700 bg-red-50 p-3 rounded-xl border border-red-100 text-sm"
                            id="modal_issue_desc"></p>
                        <div id="modal_issue_photo_div" class="mt-2 hidden">
                            <a href="#" id="modal_issue_photo_link" target="_blank"
                                class="text-xs text-blue-600 flex items-center gap-1 hover:underline">
                                <span class="material-symbols-outlined text-sm">image</span> View Issue Photo
                            </a>
                        </div>
                    </div>

                    <!-- Resolution Section (Only if Resolved) -->
                    <div id="modal_resolution_section" class="hidden border-t border-gray-100 pt-4">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-emerald-600">verified</span>
                            <h4 class="text-md font-bold text-gray-900">Resolution Details</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Technician</p>
                                <p class="text-gray-900" id="modal_technician"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Condition After Fix
                                </p>
                                <p class="text-gray-900" id="modal_condition"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Action Taken</p>
                            <p class="text-gray-700 bg-emerald-50 p-3 rounded-xl border border-emerald-100 text-sm"
                                id="modal_resolution_desc"></p>
                        </div>
                        <div id="modal_resolution_photo_div" class="mt-2 hidden">
                            <a href="#" id="modal_resolution_photo_link" target="_blank"
                                class="text-xs text-purple-600 flex items-center gap-1 hover:underline">
                                <span class="material-symbols-outlined text-sm">photo_camera</span> View Proof of Fix
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <button type="button" onclick="closeInfoModal()"
                        class="inline-flex justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openInfoModal(issue) {
        document.getElementById('infoModal').classList.remove('hidden');

        // Populate standard fields
        document.getElementById('modal_asset_name').textContent = issue.asset_name;
        document.getElementById('modal_asset_tag').textContent = issue.asset_tag;
        document.getElementById('modal_issue_desc').textContent = issue.issue_description;

        // Status Badge
        const statusEl = document.getElementById('modal_status_badge');
        statusEl.textContent = issue.status;
        statusEl.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border ';
        if (issue.status === 'Pending') statusEl.classList.add('bg-orange-50', 'text-orange-700', 'border-orange-100');
        else if (issue.status === 'In Progress') statusEl.classList.add('bg-blue-50', 'text-blue-700', 'border-blue-100');
        else if (issue.status === 'Resolved') statusEl.classList.add('bg-green-50', 'text-green-700', 'border-green-100');
        else if (issue.status === 'Deleted') statusEl.classList.add('bg-red-50', 'text-red-600', 'border-red-100', 'line-through');
        else statusEl.classList.add('bg-gray-50', 'text-gray-600', 'border-gray-100');

        // Issue Photo
        if (issue.photo_path) {
            document.getElementById('modal_issue_photo_div').classList.remove('hidden');
            document.getElementById('modal_issue_photo_link').href = '<?php echo base_url(); ?>' + issue.photo_path;
        } else {
            document.getElementById('modal_issue_photo_div').classList.add('hidden');
        }

        // Resolution Details
        if (issue.status === 'Resolved' || issue.status === 'Closed') {
            document.getElementById('modal_resolution_section').classList.remove('hidden');
            document.getElementById('modal_technician').textContent = issue.technician_name || 'Unknown';
            document.getElementById('modal_condition').textContent = issue.condition_after_fix || 'N/A';
            document.getElementById('modal_resolution_desc').textContent = issue.resolution_description || 'No description provided.';

            if (issue.resolution_photo) {
                document.getElementById('modal_resolution_photo_div').classList.remove('hidden');
                document.getElementById('modal_resolution_photo_link').href = '<?php echo base_url(); ?>' + issue.resolution_photo;
            } else {
                document.getElementById('modal_resolution_photo_div').classList.add('hidden');
            }
        } else {
            document.getElementById('modal_resolution_section').classList.add('hidden');
        }
    }

    function closeInfoModal() {
        document.getElementById('infoModal').classList.add('hidden');
    }

    function openResolveModal(id) {
        document.getElementById('modal_issue_id').value = id;
        document.getElementById('resolveModal').classList.remove('hidden');
    }

    function closeResolveModal() {
        document.getElementById('resolveModal').classList.add('hidden');
    }
</script>