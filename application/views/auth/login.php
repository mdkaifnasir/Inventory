<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - Hardware &amp; Electronics Inventory</title>
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
            <!-- Decorative background pattern/image -->
            <div class="absolute inset-0 opacity-20 bg-cover bg-center"
                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDnY04H1spp3wlG9ae4B8sb8N-Ei_RXvudR3RpxzoPlHLbp4Ec2tO0bziywg-VkcUj-nJ4XAOAWTe-qXHPThzoWjFi9MvD_XpORM5H7gzN8j3RHnjL1gkgjKhlogQ7Z2nbBDzPCMhRcuAouwDi2WHxDM_sslW16t4RQzdgH-HzSCEMKhU_e3scz3wPFrljH02Pcw-D5mAA06FdnsGhFxvYushaLTT6dGEecWsldz0FJFEO0w4sDN2qdlttS5mKG5KYiWQaWJ6KBxRs");'>
            </div>
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-blue-900 opacity-80"></div>
            <div class="relative z-10 max-w-lg text-white">
                <div class="mb-8 flex items-center gap-3">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <span class="material-symbols-outlined text-4xl">memory</span>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight">TechInventory</h2>
                </div>
                <h1 class="text-5xl font-black leading-tight mb-6">Hardware &amp; Electronics Management</h1>
                <p class="text-xl text-blue-100 font-light leading-relaxed">
                    A dedicated portal for college departments to track, manage, and distribute technical components and
                    lab equipment.
                </p>
                <div class="mt-12 grid grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <span class="text-3xl font-bold">5,000+</span>
                        <span class="text-sm text-blue-200 uppercase tracking-widest">Active Assets</span>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="text-3xl font-bold">12+</span>
                        <span class="text-sm text-blue-200 uppercase tracking-widest">Departments</span>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-8 left-12 text-blue-200 text-sm">
                © 2024 College Department of Engineering
            </div>
        </div>
        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white dark:bg-background-dark">
            <div class="w-full max-w-[440px] flex flex-col">
                <!-- Mobile Logo (visible only on small screens) -->
                <div class="lg:hidden mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-3xl">memory</span>
                    <span class="text-xl font-bold text-[#111418] dark:text-white">TechInventory</span>
                </div>
                <div class="mb-10">
                    <h1 class="text-[#111418] dark:text-white text-[32px] font-bold leading-tight pb-2">Welcome Back
                    </h1>
                    <p class="text-[#617589] dark:text-gray-400 text-base">Please enter your details to sign in.</p>
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm text-center">
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm text-center">
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php echo form_open('auth/login', ['class' => 'space-y-5']); ?>
                <!-- College Email or Login ID -->
                <div class="flex flex-col gap-2">
                    <label class="text-[#111418] dark:text-gray-200 text-sm font-semibold leading-normal">College Email
                        or Login ID</label>
                    <div class="relative">
                        <input name="email"
                            class="form-input flex w-full rounded-lg text-[#111418] dark:text-white border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary h-14 placeholder:text-[#617589] px-4 text-base transition-all"
                            placeholder="e.g. student@college.edu or college_id" required="" type="text"
                            value="<?php echo set_value('email'); ?>" />
                    </div>
                    <div class="text-red-500 text-xs"><?php echo form_error('email'); ?></div>
                </div>
                <!-- Password -->
                <div class="flex flex-col gap-2">
                    <label
                        class="text-[#111418] dark:text-gray-200 text-sm font-semibold leading-normal">Password</label>
                    <div class="relative flex items-stretch">
                        <input name="password" id="password"
                            class="form-input flex w-full rounded-lg text-[#111418] dark:text-white border border-[#dbe0e6] dark:border-gray-700 bg-white dark:bg-gray-800 focus:outline-0 focus:ring-2 focus:ring-primary/20 focus:border-primary h-14 placeholder:text-[#617589] px-4 text-base transition-all"
                            placeholder="Enter your password" required="" type="password" />
                        <button
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#617589] hover:text-primary transition-colors"
                            type="button" onclick="togglePassword()">
                            <span class="material-symbols-outlined" id="eye-icon">visibility</span>
                        </button>
                    </div>
                    <div class="text-red-500 text-xs"><?php echo form_error('password'); ?></div>
                </div>
                <!-- Utilities: Remember Me & Forgot Password -->
                <div class="flex items-center justify-between py-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input name="remember"
                            class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary transition-all"
                            type="checkbox" />
                        <span
                            class="text-sm text-[#617589] dark:text-gray-400 group-hover:text-[#111418] dark:group-hover:text-white transition-colors">Remember
                            me</span>
                    </label>
                    <a class="text-sm font-semibold text-primary hover:text-blue-700 transition-colors" href="#">Forgot
                        Password?</a>
                </div>
                <!-- Login Button -->
                <button
                    class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-lg transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                    type="submit">
                    <span>Login</span>
                    <span class="material-symbols-outlined text-xl">login</span>
                </button>
                <?php echo form_close(); ?>

                <!-- SSO Options -->
                <div class="mt-8">
                    <div class="relative flex items-center py-4">
                        <div class="flex-grow border-t border-[#dbe0e6] dark:border-gray-800"></div>
                        <span class="flex-shrink mx-4 text-xs text-[#617589] uppercase tracking-widest">Or continue
                            with</span>
                        <div class="flex-grow border-t border-[#dbe0e6] dark:border-gray-800"></div>
                    </div>
                    <button
                        class="w-full flex items-center justify-center gap-3 border border-[#dbe0e6] dark:border-gray-700 py-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all text-[#111418] dark:text-white font-medium">
                        <svg class="w-5 h-5" viewbox="0 0 24 24">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                fill="#4285F4"></path>
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853"></path>
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                fill="#FBBC05"></path>
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335"></path>
                        </svg>
                        College Single Sign-On
                    </button>
                </div>
                <!-- Footer CTA -->
                <div class="mt-10 text-center">
                    <p class="text-[#617589] dark:text-gray-400">
                        Don't have an account?
                        <a class="text-primary font-bold hover:underline ml-1"
                            href="<?php echo site_url('auth/register'); ?>">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
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