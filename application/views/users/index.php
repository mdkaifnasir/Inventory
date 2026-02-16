<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Users</h1>
            <p class="text-gray-500 mt-1">Manage departmental staff access and assigned roles.</p>
        </div>
        <button onclick="toggleModal()"
            class="flex items-center gap-2 bg-primary-600 px-4 py-2 rounded-lg text-sm font-semibold text-white hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
            <span class="material-symbols-outlined text-xl">person_add</span> Add New User
        </button>
    </div>

    <!-- Alert System -->
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
            <div class="text-sm font-medium leading-relaxed">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- User Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Employee</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Username / Email
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 uppercase tracking-tight font-medium text-gray-700">
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm">
                                        <?php echo substr($user->full_name, 0, 1); ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">
                                            <?php echo $user->full_name; ?>
                                        </p>
                                        <p class="text-[11px] font-bold text-gray-400">
                                            <?php echo $user->employee_id; ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 text-[11px] font-bold rounded-md bg-gray-100 text-gray-600 border border-gray-200">
                                    <?php echo $user->role_name; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-bold text-gray-800">@
                                    <?php echo $user->username; ?>
                                </p>
                                <p class="text-[11px] text-gray-400 lowercase">
                                    <?php echo $user->email; ?>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($user->status == 'Active'): ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?php echo site_url('users/toggle_status/' . $user->id); ?>"
                                        class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all"
                                        title="Toggle Status">
                                        <span class="material-symbols-outlined text-xl">
                                            <?php echo ($user->status == 'Active') ? 'block' : 'check_circle'; ?>
                                        </span>
                                    </a>
                                    <a href="<?php echo site_url('users/edit/' . $user->id); ?>"
                                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                                        <span class="material-symbols-outlined text-xl">edit</span>
                                    </a>
                                    <?php if ($user->id != 1 && $user->id != $this->session->userdata('user_id')): // Cannot delete root admin or self ?>
                                        <a href="<?php echo site_url('users/delete/' . $user->id); ?>" 
                                           onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');"
                                            class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                            title="Delete User">
                                            <span class="material-symbols-outlined text-xl">delete</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="userModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="toggleModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white p-8 shadow-2xl transition-all transform scale-95 opacity-0 flex flex-col gap-6"
            id="modalContent">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Add System User</h3>
                <button onclick="toggleModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <?php echo form_open('users/store', ['class' => 'space-y-4', 'id' => 'userForm']); ?>

            <!-- Account Type Toggle -->
            <div class="flex p-1 bg-gray-100 rounded-xl mb-4">
                <button type="button" onclick="setAccountType('individual')" id="btn_individual"
                    class="flex-1 py-2 text-[10px] uppercase tracking-widest font-bold rounded-lg transition-all bg-white shadow-sm text-primary-600">
                    Individual User
                </button>
                <button type="button" onclick="setAccountType('college')" id="btn_college"
                    class="flex-1 py-2 text-[10px] uppercase tracking-widest font-bold rounded-lg transition-all text-gray-500 hover:text-gray-700">
                    College Account
                </button>
                <input type="hidden" name="account_type" id="account_type" value="individual">
            </div>

            <!-- Individual Fields Section -->
            <div id="individual_fields" class="space-y-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Full Name</label>
                    <input type="text" name="full_name" id="field_full_name"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="John Doe" required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">College Email</label>
                    <input type="email" name="email" id="field_email"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="email@college.edu" required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Employee ID / Code</label>
                    <input type="text" name="employee_id" id="field_employee_id"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="EMP-XXXX" required>
                </div>
            </div>

            <!-- Common Fields Section -->
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase" id="label_login_id">Username / Login
                        ID</label>
                    <input type="text" name="username" id="user_username"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="username" required>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Role / Department</label>
                    <select name="role_id"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        required>
                        <option value="">Select Role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role->id; ?>">
                                <?php echo $role->role_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-gray-500 uppercase text-primary-600">Assign College</label>
                <select name="college_id" id="college_select" onchange="autoGenerateLoginID()"
                    class="w-full rounded-lg border-primary-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5 bg-primary-50/30">
                    <option value="">No College (Global Admin)</option>
                    <?php foreach ($colleges as $college): ?>
                        <option value="<?php echo $college->id; ?>">
                            <?php echo $college->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-gray-500 uppercase flex justify-between items-center">
                    <span>Password</span>
                    <button type="button" onclick="generatePassword()"
                        class="text-primary-600 hover:text-primary-700 normal-case font-bold text-[10px] flex items-center gap-1 group">
                        <span
                            class="material-symbols-outlined text-[14px] group-hover:rotate-180 transition-transform duration-500">sync</span>
                        Auto-Generate
                    </button>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="user_password"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="Min 8 characters" required>
                    <button type="button" onclick="togglePassVisibility()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <span class="material-symbols-outlined text-sm" id="pass_visibility_icon">visibility</span>
                    </button>
                </div>
            </div>

            <div id="college_copy_section" class="hidden space-y-3">
                <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-lg border border-blue-100">
                    <input type="checkbox" name="send_email" id="send_email" value="1" checked
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="send_email" class="text-xs font-bold text-blue-800">
                        Send Login Credentials to College Email
                    </label>
                </div>

                <button type="button" onclick="copyCollegeCredentials()"
                    class="w-full bg-emerald-50 text-emerald-700 font-bold py-2.5 rounded-lg border border-emerald-200 hover:bg-emerald-100 transition-all flex items-center justify-center gap-2 text-sm group">
                    <span
                        class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">content_copy</span>
                    Copy Login ID & Password
                </button>
                <p id="copy_feedback" class="text-[10px] text-emerald-600 font-bold text-center mt-1 hidden">Message
                    copied to clipboard!</p>
            </div>

            <div class="pt-4 border-t border-gray-100 mt-6 space-y-3">
                <button type="submit"
                    class="w-full bg-primary-600 text-white font-bold py-3 rounded-lg hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-xl">person_add</span> Create Account
                </button>
                <button type="button" onclick="toggleModal()"
                    class="w-full text-gray-500 font-bold py-2 text-sm hover:text-gray-700 transition-colors">
                    Cancel
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    function setAccountType(type) {
        const individualFields = document.getElementById('individual_fields');
        const loginLabel = document.getElementById('label_login_id');
        const accountType = document.getElementById('account_type');
        const btnInd = document.getElementById('btn_individual');
        const btnCol = document.getElementById('btn_college');
        const collegeSelect = document.getElementById('college_select');

        const fName = document.getElementById('field_full_name');
        const fEmail = document.getElementById('field_email');
        const fEmp = document.getElementById('field_employee_id');

        accountType.value = type;

        if (type === 'college') {
            individualFields.classList.add('hidden');
            loginLabel.innerText = 'College Login ID';
            btnCol.classList.add('bg-white', 'shadow-sm', 'text-primary-600');
            btnCol.classList.remove('text-gray-500');
            btnInd.classList.remove('bg-white', 'shadow-sm', 'text-primary-600');
            btnInd.classList.add('text-gray-500');
            collegeSelect.setAttribute('required', 'required');

            fName.removeAttribute('required');
            fEmail.removeAttribute('required');
            fEmp.removeAttribute('required');

            document.getElementById('college_copy_section').classList.remove('hidden');
            autoGenerateLoginID();
        } else {
            individualFields.classList.remove('hidden');
            loginLabel.innerText = 'Username / Login ID';
            btnInd.classList.add('bg-white', 'shadow-sm', 'text-primary-600');
            btnInd.classList.remove('text-gray-500');
            btnCol.classList.remove('bg-white', 'shadow-sm', 'text-primary-600');
            btnCol.classList.add('text-gray-500');
            collegeSelect.removeAttribute('required');

            fName.setAttribute('required', 'required');
            fEmail.setAttribute('required', 'required');
            fEmp.setAttribute('required', 'required');

            document.getElementById('college_copy_section').classList.add('hidden');
        }
    }

    function copyCollegeCredentials() {
        const collegeSelect = document.getElementById('college_select');
        const username = document.getElementById('user_username').value;
        const password = document.getElementById('user_password').value;
        const feedback = document.getElementById('copy_feedback');

        if (!username || !password) {
            alert('Please ensure Login ID and Password are generated first.');
            return;
        }

        const collegeName = collegeSelect.selectedIndex > 0
            ? collegeSelect.options[collegeSelect.selectedIndex].text
            : 'College';

        const message = `*Institution Account Credentials*\n\n` +
            `*Institution:* ${collegeName}\n` +
            `*Login ID:* ${username}\n` +
            `*Password:* ${password}\n\n` +
            `Please keep these credentials secure.`;

        navigator.clipboard.writeText(message).then(() => {
            feedback.classList.remove('hidden');
            setTimeout(() => {
                feedback.classList.add('hidden');
            }, 3000);
        });
    }

    function autoGenerateLoginID() {
        const type = document.getElementById('account_type').value;
        if (type !== 'college') return;

        const collegeSelect = document.getElementById('college_select');
        const usernameInput = document.getElementById('user_username');

        if (collegeSelect.selectedIndex > 0) {
            const collegeName = collegeSelect.options[collegeSelect.selectedIndex].text;
            const id = collegeName.toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '_')
                .substring(0, 20);

            usernameInput.value = id;
        } else {
            usernameInput.value = '';
        }
    }

    function generatePassword() {
        const length = 12;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
        let retVal = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        const passInput = document.getElementById('user_password');
        passInput.value = retVal;
        passInput.type = 'text'; // Show it so they can copy it
        document.getElementById('pass_visibility_icon').innerText = 'visibility_off';
    }

    function togglePassVisibility() {
        const passInput = document.getElementById('user_password');
        const icon = document.getElementById('pass_visibility_icon');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            icon.innerText = 'visibility_off';
        } else {
            passInput.type = 'password';
            icon.innerText = 'visibility';
        }
    }

    function toggleModal() {
        const modal = document.getElementById('userModal');
        const content = document.getElementById('modalContent');

        if (modal.classList.contains('hidden')) {
            // Open Modal
            modal.classList.remove('hidden');
            // Give browser a frame to register removal of 'hidden' before animating
            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
            });
        } else {
            // Close Modal
            content.classList.add('scale-95', 'opacity-0');
            // Wait for transition to finish before hiding
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    }
</script>