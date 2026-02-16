<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-500 mt-1">Manage your account settings and security preferences.</p>
        </div>
    </div>

    <!-- Feedback Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div
            class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <span class="text-sm font-bold">
                <?php echo $this->session->flashdata('success'); ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div
            class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-xl">error</span>
            <span class="text-sm font-bold">
                <?php echo $this->session->flashdata('error'); ?>
            </span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Identifying Card -->
        <div class="lg:col-span-1 space-y-6">
            <div
                class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 text-center relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-primary-600 to-primary-800"></div>
                <div class="relative z-10 pt-12">
                    <div class="size-24 mx-auto rounded-full bg-white p-1.5 shadow-xl mb-4">
                        <div
                            class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center text-primary-600 font-bold text-3xl uppercase">
                            <?php echo substr($user->username, 0, 1); ?>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">
                        <?php echo $user->full_name; ?>
                    </h2>
                    <p class="text-sm text-gray-500 font-mono mb-2">@
                        <?php echo $user->username; ?>
                    </p>

                    <div
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?php echo ($user->role_id == 1) ? 'bg-purple-50 text-purple-700 border border-purple-100' : 'bg-gray-50 text-gray-600 border border-gray-100'; ?>">
                        <?php echo $user->role_name; ?>
                    </div>

                    <div class="mt-8 border-t border-gray-50 pt-6 flex flex-col gap-2 text-left">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Employee ID</span>
                            <span class="font-bold text-gray-900">
                                <?php echo $user->employee_id; ?>
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Joined</span>
                            <span class="font-bold text-gray-900">
                                <?php echo date('M d, Y', strtotime($user->created_at)); ?>
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Status</span>
                            <span class="font-bold text-emerald-600 flex items-center gap-1">
                                <span class="size-2 rounded-full bg-emerald-500"></span> Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Details Form -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-600">badge</span> Profile Details
                    </h3>
                </div>
                <form action="<?php echo site_url('profile/update_details'); ?>" method="POST" class="p-8 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase">Full Name</label>
                            <input type="text" name="full_name" value="<?php echo $user->full_name; ?>" required
                                class="w-full rounded-xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5 bg-gray-50 focus:bg-white transition-colors">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase">Email Address</label>
                            <input type="email" name="email" value="<?php echo $user->email; ?>" required
                                class="w-full rounded-xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5 bg-gray-50 focus:bg-white transition-colors">
                        </div>
                    </div>
                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                            class="bg-gray-900 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-black transition-all shadow-lg shadow-gray-200 text-xs uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">save</span> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Form -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-600">lock_reset</span> Security
                    </h3>
                </div>
                <form action="<?php echo site_url('profile/update_password'); ?>" method="POST" class="p-8 space-y-5">
                    <div
                        class="p-4 bg-orange-50 text-orange-800 rounded-xl text-xs font-medium border border-orange-100 mb-2 flex gap-2">
                        <span class="material-symbols-outlined text-base">warning</span>
                        Note: You will be logged out after changing your password.
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-500 uppercase">Current Password</label>
                        <input type="password" name="current_password" required
                            class="w-full rounded-xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5 bg-gray-50 focus:bg-white transition-colors">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase">New Password</label>
                            <input type="password" name="new_password" required minlength="8"
                                class="w-full rounded-xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5 bg-gray-50 focus:bg-white transition-colors">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-500 uppercase">Confirm Password</label>
                            <input type="password" name="confirm_password" required minlength="8"
                                class="w-full rounded-xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5 bg-gray-50 focus:bg-white transition-colors">
                        </div>
                    </div>
                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                            class="bg-primary-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 text-xs uppercase tracking-widest flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">key</span> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>