<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Register - Hardware &amp; Electronics Inventory</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display">
    <div class="flex min-h-screen">
        <!-- Left Side: Branding & Illustration -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary items-center justify-center p-12">
            <div class="absolute inset-0 opacity-20 bg-cover bg-center"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDnY04H1spp3wlG9ae4B8sb8N-Ei_RXvudR3RpxzoPlHLbp4Ec2tO0bziywg-VkcUj-nJ4XAOAWTe-qXHPThzoWjFi9MvD_XpORM5H7gzN8j3RHnjL1gkgjKhlogQ7Z2nbBDzPCMhRcuAouwDi2WHxDM_sslW16t4RQzdgH-HzSCEMKhU_e3scz3wPFrljH02Pcw-D5mAA06FdnsGhFxvYushaLTT6dGEecWsldz0FJFEO0w4sDN2qdlttS5mKG5KYiWQaWJ6KBxRs");'>
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-blue-900 opacity-80"></div>
            <div class="relative z-10 max-w-lg text-white">
                <div class="mb-8 flex items-center gap-3">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <span class="material-symbols-outlined text-4xl">memory</span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight">TechInventory</h2>
                </div>
                <h1 class="text-5xl font-black leading-tight mb-6 text-white">Department of Engineering & Electronics
                </h1>
                <p class="text-xl text-blue-100 font-light leading-relaxed">
                    Join our dedicated portal to track, manage, and distribute technical components and lab equipment
                    across your department.
                </p>
                <div class="mt-12 flex items-center gap-4">
                    <div class="flex -space-x-3">
                        <div
                            class="w-10 h-10 rounded-full border-2 border-primary bg-blue-500 flex items-center justify-center text-xs font-bold">
                            JD</div>
                        <div
                            class="w-10 h-10 rounded-full border-2 border-primary bg-emerald-500 flex items-center justify-center text-xs font-bold">
                            AS</div>
                        <div
                            class="w-10 h-10 rounded-full border-2 border-primary bg-amber-500 flex items-center justify-center text-xs font-bold">
                            RK</div>
                    </div>
                    <span class="text-sm text-blue-100 font-medium">Joined by 500+ staff members</span>
                </div>
            </div>
            <div class="absolute bottom-8 left-12 text-blue-200 text-sm">
                © 2024 College Department of Engineering
            </div>
        </div>

        <!-- Right Side: Registration Form -->
        <div
            class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white dark:bg-background-dark overflow-y-auto">
            <div class="w-full max-w-[560px] flex flex-col py-12">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-3xl">memory</span>
                    <span class="text-xl font-bold text-[#111418] dark:text-white">TechInventory</span>
                </div>
                <div class="mb-8">
                    <h1 class="text-[#111418] dark:text-white text-[32px] font-bold leading-tight pb-2">Create Account
                    </h1>
                    <p class="text-[#617589] dark:text-gray-400 text-base">Enter your details to register as a
                        department employee.</p>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm text-center">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php echo form_open('auth/register', ['class' => 'space-y-5']); ?>
                <!-- Full Name & College Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-[#111418] dark:text-gray-200 text-sm font-semibold">Full Name</label>
                        <input name="full_name"
                            class="form-input w-full rounded-lg border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white h-12 px-4 focus:ring-primary/20 focus:border-primary transition-all"
                            placeholder="John Doe" required type="text" value="<?php echo set_value('full_name'); ?>" />
                        <div class="text-red-500 text-xs"><?php echo form_error('full_name'); ?></div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-[#111418] dark:text-gray-200 text-sm font-semibold">College Email</label>
                        <input name="email"
                            class="form-input w-full rounded-lg border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white h-12 px-4 focus:ring-primary/20 focus:border-primary transition-all"
                            placeholder="email@college.edu" required type="email"
                            value="<?php echo set_value('email'); ?>" />
                        <div class="text-red-500 text-xs"><?php echo form_error('email'); ?></div>
                    </div>
                </div>

                <!-- Username & Department -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-[#111418] dark:text-gray-200 text-sm font-semibold">Username</label>
                        <input name="username"
                            class="form-input w-full rounded-lg border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white h-12 px-4 focus:ring-primary/20 focus:border-primary transition-all"
                            placeholder="Choose username" required type="text"
                            value="<?php echo set_value('username'); ?>" />
                        <div class="text-red-500 text-xs"><?php echo form_error('username'); ?></div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-[#111418] dark:text-gray-200 text-sm font-semibold">Department / Role</label>
                        <select name="role_id"
                            class="form-select w-full rounded-lg border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white h-12 px-4 focus:ring-primary/20 focus:border-primary transition-all"
                            required>
                            <option value="" disabled selected>Select your department</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role->id; ?>" <?php echo set_select('role_id', $role->id); ?>>
                                    <?php echo $role->role_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="text-red-500 text-xs"><?php echo form_error('role_id'); ?></div>
                    </div>
                </div>

                <!-- Employee ID -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#111418] dark:text-gray-200 text-sm font-semibold">Employee ID</label>
                    <input name="employee_id"
                        class="form-input w-full rounded-lg border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white h-12 px-4 focus:ring-primary/20 focus:border-primary transition-all"
                        placeholder="EMP-XXXXXX" required type="text" value="<?php echo set_value('employee_id'); ?>" />
                    <div class="text-red-500 text-xs"><?php echo form_error('employee_id'); ?></div>
                </div>

                <!-- Passwords -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex flex-col gap-2">
                        <label class="text-[#111418] dark:text-gray-200 text-sm font-semibold">Password</label>
                        <div class="relative flex items-stretch">
                            <input name="password" id="password"
                                class="form-input w-full rounded-lg border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white h-12 px-4 focus:ring-primary/20 focus:border-primary transition-all"
                                placeholder="••••••••" required type="password" />
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#617589] hover:text-primary transition-all"
                                type="button" onclick="togglePassword('password', 'eye-1')">
                                <span class="material-symbols-outlined text-xl" id="eye-1">visibility</span>
                            </button>
                        </div>
                        <div class="text-red-500 text-xs"><?php echo form_error('password'); ?></div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-[#111418] dark:text-gray-200 text-sm font-semibold">Confirm Password</label>
                        <div class="relative flex items-stretch">
                            <input name="confirm_password" id="confirm_password"
                                class="form-input w-full rounded-lg border-[#dbe0e6] dark:border-gray-700 dark:bg-gray-800 dark:text-white h-12 px-4 focus:ring-primary/20 focus:border-primary transition-all"
                                placeholder="••••••••" required type="password" />
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-[#617589] hover:text-primary transition-all"
                                type="button" onclick="togglePassword('confirm_password', 'eye-2')">
                                <span class="material-symbols-outlined text-xl" id="eye-2">visibility</span>
                            </button>
                        </div>
                        <div class="text-red-500 text-xs"><?php echo form_error('confirm_password'); ?></div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-center gap-2 py-2">
                    <input id="terms" required
                        class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary transition-all"
                        type="checkbox" />
                    <label for="terms" class="text-sm text-[#617589] dark:text-gray-400">
                        I agree to the <a href="#" class="text-primary font-semibold hover:underline">Terms of
                            Service</a> and <a href="#" class="text-primary font-semibold hover:underline">Privacy
                            Policy</a>
                    </label>
                </div>

                <!-- Register Button -->
                <button
                    class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-lg transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                    type="submit">
                    <span>Register Account</span>
                    <span class="material-symbols-outlined text-xl">person_add</span>
                </button>
                <?php echo form_close(); ?>

                <!-- Login Link -->
                <div class="mt-8 text-center border-t border-gray-100 dark:border-gray-800 pt-8">
                    <p class="text-[#617589] dark:text-gray-400">
                        Already have an account?
                        <a class="text-primary font-bold hover:underline ml-1"
                            href="<?php echo site_url('auth/login'); ?>">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>
</body>

</html>