<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Institutions & Colleges</h1>
            <p class="text-gray-500 mt-1">Manage departmental hubs for asset allocation and tracking.</p>
        </div>
        <button onclick="toggleCollegeModal()"
            class="flex items-center gap-2 bg-primary-600 px-4 py-2 rounded-lg text-sm font-semibold text-white hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
            <span class="material-symbols-outlined text-xl">add_business</span> Add College
        </button>
    </div>

    <!-- Alerts -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <span class="text-sm font-medium">
                <?php echo $this->session->flashdata('success'); ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">error</span>
            <span class="text-sm font-medium">
                <?php echo $this->session->flashdata('error'); ?>
            </span>
        </div>
    <?php endif; ?>

    <!-- Colleges Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($colleges as $college): ?>
            <div
                class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:border-primary-300 transition-all group relative overflow-hidden">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="p-3 bg-primary-50 text-primary-600 rounded-xl group-hover:bg-primary-600 group-hover:text-white transition-all">
                        <span class="material-symbols-outlined text-2xl">account_balance</span>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick="editCollege(<?php echo $college->id; ?>)"
                            class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </button>
                        <a href="<?php echo site_url('colleges/delete/' . $college->id); ?>"
                            onclick="return confirm('Are you sure? This will unassign all assets currently linked to this college.')"
                            class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-gray-900 text-lg">
                            <?php echo $college->name; ?>
                        </h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-black rounded uppercase">
                            <?php echo $college->code; ?>
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2 min-h-[40px]">
                        <?php echo $college->description ? $college->description : 'No description provided.'; ?>
                    </p>
                </div>
                <div
                    class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between text-[10px] font-black text-gray-400 uppercase tracking-[0.1em]">
                    <span>Registered
                        <?php echo date('M Y', strtotime($college->created_at)); ?>
                    </span>
                    <span class="text-primary-600/30">AZAM #
                        <?php echo $college->id; ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($colleges)): ?>
            <div class="col-span-full py-20 text-center bg-white rounded-xl border-2 border-dashed border-gray-200">
                <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">corporate_fare</span>
                <p class="text-gray-400 font-medium">No colleges registered yet. Start by adding one!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- College Modal -->
<div id="collegeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleCollegeModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl rounded-2xl bg-white p-8 shadow-2xl transition-all transform scale-95 opacity-0 flex flex-col gap-6"
            id="collegeModalContent">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Add College</h3>
                <button onclick="toggleCollegeModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="collegeForm" action="<?php echo site_url('colleges/store'); ?>" method="POST" class="space-y-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">College Name</label>
                    <input type="text" name="name" id="collName"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="e.g. College of Engineering" required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Short Code</label>
                    <input type="text" name="code" id="collCode"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="e.g. COE, CAS, CBA" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Principal Name</label>
                        <input type="text" name="principal_name" id="collPrincipal"
                            class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                            placeholder="Dr. Name Surname">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Email</label>
                        <input type="email" name="email" id="collEmail"
                            class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                            placeholder="college@example.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Phone</label>
                        <input type="text" name="phone" id="collPhone"
                            class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                            placeholder="Landline No.">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Mobile</label>
                        <input type="text" name="mobile" id="collMobile"
                            class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                            placeholder="Mobile No.">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Fax</label>
                        <input type="text" name="fax" id="collFax"
                            class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                            placeholder="Fax No.">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Website</label>
                        <input type="text" name="website" id="collWebsite"
                            class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                            placeholder="www.college.edu">
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Description</label>
                    <textarea name="description" id="collDesc" rows="2"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2"
                        placeholder="Brief details about this institution..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-primary-600 text-white font-bold py-3 rounded-lg hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                        Save Institution
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleCollegeModal(title = "Add College", action = "<?php echo site_url('colleges/store'); ?>") {
        const modal = document.getElementById('collegeModal');
        const content = document.getElementById('collegeModalContent');
        const form = document.getElementById('collegeForm');
        const titleEl = document.getElementById('modalTitle');

        if (modal.classList.contains('hidden')) {
            titleEl.textContent = title;
            form.action = action;

            if (title === "Add College") {
                document.getElementById('collName').value = "";
                document.getElementById('collCode').value = "";
                document.getElementById('collDesc').value = "";
                document.getElementById('collPrincipal').value = "";
                document.getElementById('collEmail').value = "";
                document.getElementById('collPhone').value = "";
                document.getElementById('collMobile').value = "";
                document.getElementById('collFax').value = "";
                document.getElementById('collWebsite').value = "";
            }

            modal.classList.remove('hidden');
            requestAnimationFrame(() => content.classList.remove('scale-95', 'opacity-0'));
        } else {
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }
    }

    function editCollege(id) {
        fetch(`<?php echo site_url('colleges/get_college_json/'); ?>` + id)
            .then(res => res.json())
            .then(data => {
                document.getElementById('collName').value = data.name;
                document.getElementById('collCode').value = data.code;
                document.getElementById('collDesc').value = data.description;
                document.getElementById('collPrincipal').value = data.principal_name;
                document.getElementById('collEmail').value = data.email;
                document.getElementById('collPhone').value = data.phone;
                document.getElementById('collMobile').value = data.mobile;
                document.getElementById('collFax').value = data.fax;
                document.getElementById('collWebsite').value = data.website;

                toggleCollegeModal("Edit College", `<?php echo site_url('colleges/update/'); ?>` + id);
            });
    }
</script>