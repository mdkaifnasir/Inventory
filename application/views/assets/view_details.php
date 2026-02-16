<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $asset->name; ?> | Asset Details
    </title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col p-4 md:p-8">

    <?php
    // Dynamic Icon Logic
    $device_type = strtolower($asset->name);
    $icon = 'devices';
    if (strpos($device_type, 'laptop') !== false)
        $icon = 'laptop_mac';
    elseif (strpos($device_type, 'desktop') !== false)
        $icon = 'desktop_windows';
    elseif (strpos($device_type, 'printer') !== false)
        $icon = 'print';
    elseif (strpos($device_type, 'scanner') !== false)
        $icon = 'scanner';
    elseif (strpos($device_type, 'monitor') !== false)
        $icon = 'monitor';
    elseif (strpos($device_type, 'ups') !== false)
        $icon = 'battery_charging_full';
    elseif (strpos($device_type, 'keyboard') !== false)
        $icon = 'keyboard';
    elseif (strpos($device_type, 'mouse') !== false)
        $icon = 'mouse';
    elseif (strpos($device_type, 'camera') !== false)
        $icon = 'videocam';
    elseif (strpos($device_type, 'phone') !== false)
        $icon = 'smartphone';

    // Placeholder Image Search Query
    $img_query = urlencode($asset->brand_model . ' ' . $asset->name);
    $placeholder_img = "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&q=80&w=800"; // Default Laptop
    if (strpos($device_type, 'desktop') !== false)
        $placeholder_img = "https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=800";
    elseif (strpos($device_type, 'printer') !== false)
        $placeholder_img = "https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&q=80&w=800";
    elseif (strpos($device_type, 'monitor') !== false)
        $placeholder_img = "https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?auto=format&fit=crop&q=80&w=800";

    // Condition Styling
    $condition_class = "bg-gray-500 text-white";
    $condition = strtolower($asset->asset_condition);
    if ($condition == 'new')
        $condition_class = "bg-emerald-600 text-white shadow-lg shadow-emerald-200";
    elseif ($condition == 'working')
        $condition_class = "bg-primary-600 text-white shadow-lg shadow-primary-200";
    elseif ($condition == 'refurbished')
        $condition_class = "bg-amber-600 text-white shadow-lg shadow-amber-200";
    elseif ($condition == 'used' || strpos($condition, 'used') !== false)
        $condition_class = "bg-blue-600 text-white shadow-lg shadow-blue-200";
    elseif ($condition == 'scrap' || $condition == 'broken' || $condition == 'non-working')
        $condition_class = "bg-red-600 text-white shadow-lg shadow-red-200";
    ?>

    <div
        class="max-w-2xl mx-auto w-full bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100">

        <!-- Header Section -->
        <div class="relative overflow-hidden">
            <!-- Dynamic Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="<?php echo $placeholder_img; ?>" alt="Device Image"
                    class="w-full h-full object-cover opacity-20 blur-sm scale-110">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-600/90 to-blue-700/90"></div>
            </div>

            <div class="relative z-10 p-8 text-white">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-4 bg-white rounded-2xl shadow-xl border border-white/20">
                        <span class="material-symbols-outlined text-4xl leading-none text-gray-900">
                            <?php echo $icon; ?>
                        </span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tight leading-tight text-gray-900">
                            <?php echo $asset->name; ?>
                        </h1>
                        <p class="text-white text-[10px] font-bold uppercase tracking-[0.2em] mt-1">
                            <?php echo $asset->asset_tag; ?>
                        </p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <span
                        class="px-3 py-1.5 bg-white rounded-full text-[10px] font-black uppercase tracking-widest text-gray-900">
                        <span class="opacity-60 mr-1">TYPE:</span> <?php echo $asset->category_name; ?>
                    </span>
                    <span
                        class="px-4 py-1.5 <?php echo $condition_class; ?> rounded-full text-[10px] font-black uppercase tracking-widest transition-all">
                        <?php echo $asset->asset_condition; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Specifications Grid -->
        <div class="p-8">
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">settings_input_component</span> Hardware Specifications
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Processor -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Processor</p>
                    <p class="text-sm font-bold text-gray-900">
                        <?php echo $asset->processor ?: 'Not Specified'; ?>
                    </p>
                </div>
                <!-- RAM -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">RAM / Memory</p>
                    <p class="text-sm font-bold text-gray-900">
                        <?php echo $asset->ram ?: 'Not Specified'; ?>
                    </p>
                </div>
                <!-- Storage -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Storage / Disk</p>
                    <p class="text-sm font-bold text-gray-900">
                        <?php echo $asset->hard_disk ?: 'Not Specified'; ?>
                    </p>
                </div>
                <!-- OS -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Operating System</p>
                    <p class="text-sm font-bold text-gray-900">
                        <?php echo $asset->os ?: 'Not Specified'; ?>
                    </p>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="mt-8 space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-50">
                    <span class="text-xs font-medium text-gray-500">Brand / Model</span>
                    <span class="text-xs font-bold text-gray-900">
                        <?php echo $asset->brand_model ?: 'N/A'; ?>
                    </span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-50">
                    <span class="text-xs font-medium text-gray-500">Serial Number</span>
                    <span class="text-xs font-mono font-bold text-gray-900">
                        <?php echo $asset->serial_number ?: 'N/A'; ?>
                    </span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-50">
                    <span class="text-xs font-medium text-gray-500">Institution / Dept</span>
                    <span class="text-xs font-bold text-gray-900">
                        <?php echo $asset->college_name ?: 'Central Inventory'; ?>
                    </span>
                </div>
            </div>

            <?php if ($asset->remarks): ?>
                <div class="mt-8 p-6 bg-amber-50 rounded-3xl border border-amber-100">
                    <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-xs">notes</span> Admin Remarks
                    </p>
                    <p class="text-sm text-amber-900 font-medium italic">"
                        <?php echo $asset->remarks; ?>"
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer Footer -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2 text-gray-400">
                <span class="material-symbols-outlined text-lg">verified</span>
                <span class="text-[10px] font-bold uppercase tracking-widest">Inventory Verified</span>
            </div>
            <p class="text-[10px] text-gray-400 font-medium">Internal Property ID:
                <?php echo $asset->id; ?>
            </p>
        </div>
    </div>

    <!-- System Brand -->
    <div class="mt-8 text-center">
        <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.5em]">AZAM IT</p>
    </div>

</body>

</html>