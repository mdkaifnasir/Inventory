<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true"
            onclick="location.href='<?php echo site_url('users'); ?>'"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                        <span class="material-symbols-outlined text-primary-600">edit</span>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Edit User Details</h3>
                        <div class="mt-2">
                            <?php echo form_open('users/update/' . $user->id, ['class' => 'space-y-4', 'id' => 'editUserForm']); ?>

                            <!-- Account Type Toggle -->
                            <div class="flex p-1 bg-gray-100 rounded-xl mb-4">
                                <button type="button" onclick="setEditAccountType('individual')"
                                    id="edit_btn_individual"
                                    class="flex-1 py-1.5 text-[10px] uppercase tracking-widest font-bold rounded-lg transition-all <?php echo !$user->college_id ? 'bg-white shadow-sm text-primary-600' : 'text-gray-500 hover:text-gray-700'; ?>">
                                    Individual
                                </button>
                                <button type="button" onclick="setEditAccountType('college')" id="edit_btn_college"
                                    class="flex-1 py-1.5 text-[10px] uppercase tracking-widest font-bold rounded-lg transition-all <?php echo $user->college_id ? 'bg-white shadow-sm text-primary-600' : 'text-gray-500 hover:text-gray-700'; ?>">
                                    College Account
                                </button>
                                <input type="hidden" name="account_type" id="edit_account_type"
                                    value="<?php echo $user->college_id ? 'college' : 'individual'; ?>">
                            </div>

                            <div id="edit_individual_block"
                                class="space-y-4 <?php echo $user->college_id ? 'hidden' : ''; ?>">
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase">Full Name</label>
                                    <input type="text" name="full_name" id="edit_field_full_name"
                                        value="<?php echo $user->full_name; ?>"
                                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 text-sm py-2.5"
                                        <?php echo !$user->college_id ? 'required' : ''; ?>>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase">Email Address</label>
                                    <input type="email" name="email" id="edit_field_email"
                                        value="<?php echo $user->email; ?>"
                                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 text-sm py-2.5"
                                        <?php echo !$user->college_id ? 'required' : ''; ?>>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase">Employee ID / Code</label>
                                    <input type="text" name="employee_id" id="edit_field_employee_id"
                                        value="<?php echo $user->employee_id; ?>"
                                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 text-sm py-2.5"
                                        <?php echo !$user->college_id ? 'required' : ''; ?>>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase"
                                        id="edit_label_login_id"><?php echo $user->college_id ? 'College Login ID' : 'Username'; ?></label>
                                    <input type="text" name="username" value="<?php echo $user->username; ?>"
                                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 text-sm py-2.5"
                                        required>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-xs font-bold text-gray-500 uppercase">Role</label>
                                    <select name="role_id"
                                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 text-sm py-2.5"
                                        required>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?php echo $role->id; ?>" <?php echo ($user->role_id == $role->id) ? 'selected' : ''; ?>>
                                                <?php echo $role->role_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase">Assign College</label>
                                <select name="college_id"
                                    class="w-full rounded-lg border-gray-200 focus:border-primary-500 text-sm py-2.5">
                                    <option value="">No College (Global Admin)</option>
                                    <?php foreach ($colleges as $college): ?>
                                        <option value="<?php echo $college->id; ?>" <?php echo ($user->college_id == $college->id) ? 'selected' : ''; ?>>
                                            <?php echo $college->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase">New Password (Optional)</label>
                            <input type="password" name="password"
                                class="w-full rounded-lg border-gray-200 focus:border-primary-500 text-sm py-2.5"
                                placeholder="Leave blank to keep current">
                        </div>
                        <div class="pt-4 flex justify-end gap-3">
                            <a href="<?php echo site_url('users'); ?>"
                                class="bg-white text-gray-700 font-bold py-2.5 px-4 rounded-lg border border-gray-300 hover:bg-gray-50 text-sm">Cancel</a>
                            <button type="submit"
                                class="bg-primary-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-primary-700 shadow-lg text-sm">Update
                                User</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function setEditAccountType(type) {
        const individualBlock = document.getElementById('edit_individual_block');
        const loginLabel = document.getElementById('edit_label_login_id');
        const accountType = document.getElementById('edit_account_type');
        const btnInd = document.getElementById('edit_btn_individual');
        const btnCol = document.getElementById('edit_btn_college');

        const fName = document.getElementById('edit_field_full_name');
        const fEmail = document.getElementById('edit_field_email');
        const fEmp = document.getElementById('edit_field_employee_id');

        accountType.value = type;

        if (type === 'college') {
            individualBlock.classList.add('hidden');
            loginLabel.innerText = 'College Login ID';
            btnCol.classList.add('bg-white', 'shadow-sm', 'text-primary-600');
            btnCol.classList.remove('text-gray-500');
            btnInd.classList.remove('bg-white', 'shadow-sm', 'text-primary-600');
            btnInd.classList.add('text-gray-500');

            if (fName) fName.removeAttribute('required');
            if (fEmail) fEmail.removeAttribute('required');
            if (fEmp) fEmp.removeAttribute('required');
        } else {
            individualBlock.classList.remove('hidden');
            loginLabel.innerText = 'Username';
            btnInd.classList.add('bg-white', 'shadow-sm', 'text-primary-600');
            btnInd.classList.remove('text-gray-500');
            btnCol.classList.remove('bg-white', 'shadow-sm', 'text-primary-600');
            btnCol.classList.add('text-gray-500');

            if (fName) fName.setAttribute('required', 'required');
            if (fEmail) fEmail.setAttribute('required', 'required');
            if (fEmp) fEmp.setAttribute('required', 'required');
        }
    }
</script>