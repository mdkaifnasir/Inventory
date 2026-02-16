<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">System Configuration</h1>
            <p class="text-gray-500 mt-1">Manage general system parameters and organization details.</p>
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

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="<?php echo site_url('settings/update'); ?>" method="POST">
            <div class="p-8 space-y-8">

                <!-- Organization Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <span class="material-symbols-outlined text-primary-600">domain</span> Organization Profile
                        </h3>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">System /
                                School Name</label>
                            <input type="text" name="system_name"
                                value="<?php echo isset($settings['system_name']) ? $settings['system_name'] : ''; ?>"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all font-medium text-gray-900">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Organization
                                Code</label>
                            <input type="text" name="org_code"
                                value="<?php echo isset($settings['org_code']) ? $settings['org_code'] : ''; ?>"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all font-medium text-gray-900">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3
                            class="text-lg font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <span class="material-symbols-outlined text-primary-600">contact_support</span> Contact
                            Information
                        </h3>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Admin Contact
                                Email</label>
                            <input type="email" name="contact_email"
                                value="<?php echo isset($settings['contact_email']) ? $settings['contact_email'] : ''; ?>"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all font-medium text-gray-900">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Contact
                                Phone</label>
                            <input type="text" name="contact_phone"
                                value="<?php echo isset($settings['contact_phone']) ? $settings['contact_phone'] : ''; ?>"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all font-medium text-gray-900">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <div class="space-y-6 max-w-2xl">
                        <h3
                            class="text-lg font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-2">
                            <span class="material-symbols-outlined text-primary-600">location_on</span> Physical Address
                        </h3>

                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Address</label>
                            <textarea name="address" rows="3"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all font-medium text-gray-900"><?php echo isset($settings['address']) ? $settings['address'] : ''; ?></textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end">
                <button type="submit"
                    class="bg-gray-900 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-gray-200 hover:bg-black hover:-translate-y-0.5 transition-all text-sm uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span> Save Configuration
                </button>
            </div>
        </form>
    </div>
</div>