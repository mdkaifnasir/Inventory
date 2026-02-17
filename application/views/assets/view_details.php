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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F9FB;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .status-badge {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .location-badge {
            background: #F3F4F6;
            color: #4B5563;
        }
    </style>
</head>

<body class="safe-area-inset-top pb-10">

    <?php
    // Dynamic Icon Logic
    $device_type = strtolower($asset->name);
    $icon = 'devices';
    if (strpos($device_type, 'laptop') !== false)
        $icon = 'laptop_mac';
    elseif (strpos($device_type, 'desktop') !== false || strpos($device_type, 'cpu') !== false)
        $icon = 'desktop_windows';
    elseif (strpos($device_type, 'printer') !== false)
        $icon = 'print';
    elseif (strpos($device_type, 'scanner') !== false)
        $icon = 'scanner';
    elseif (strpos($device_type, 'monitor') !== false)
        $icon = 'monitor';

    // Placeholder Image Logic
    $placeholder_img = "https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&q=80&w=800"; // Generic Laptop
    if (strpos($device_type, 'desktop') !== false || strpos($device_type, 'cpu') !== false)
        $placeholder_img = "https://images.unsplash.com/photo-1587831990711-23ca6441447b?auto=format&fit=crop&q=80&w=800";

    // Parse Location (Assume "Lab 3, 3rd Floor" or similar)
    $location_main = $asset->location ?: 'Not Assigned';
    $room_no = 'N/A';
    $floor = 'N/A';
    if ($asset->location) {
        if (preg_match('/(?:Lab|Room|No|No\.|#)\s*(\d+)/i', $asset->location, $matches))
            $room_no = $matches[1];
        if (preg_match('/(\d+)(?:st|nd|rd|th)?\s*Floor/i', $asset->location, $matches)) {
            $floor = $matches[1] . (isset($matches[2]) ? $matches[2] : '');
            if (!strpos(strtolower($floor), 'floor'))
                $floor .= ' Floor';
        }
    }

    // Condition Color Logic
    $condition = strtolower($asset->asset_condition);
    $condition_color = "green"; // Default Working
    if ($condition == 'new' || $condition == 'working')
        $condition_color = "green";
    elseif ($condition == 'refurbished' || $condition == 'used (good)')
        $condition_color = "blue";
    else
        $condition_color = "red";
    ?>

    <!-- Top Navigation Bar -->
    <div class="px-6 py-4 flex items-center justify-between sticky top-0 bg-[#F8F9FB]/80 backdrop-blur-md z-50">
        <a href="javascript:history.back()"
            class="p-2 bg-white rounded-full shadow-sm border border-gray-100 flex items-center justify-center">
            <span class="material-symbols-rounded text-gray-700">arrow_back</span>
        </a>
        <div class="flex gap-3">
            <button class="p-2 bg-white rounded-full shadow-sm border border-gray-100 flex items-center justify-center">
                <span class="material-symbols-rounded text-gray-700">edit_square</span>
            </button>
            <button class="p-2 bg-white rounded-full shadow-sm border border-gray-100 flex items-center justify-center">
                <span class="material-symbols-rounded text-gray-700">notifications</span>
            </button>
        </div>
    </div>

    <!-- Hero Section Card -->
    <div class="px-6 mt-2">
        <div class="w-full glass-card rounded-[2.5rem] p-6 relative overflow-hidden shadow-xl shadow-gray-200/40">
            <div class="flex gap-6 relative z-10">
                <!-- Device Image Area -->
                <div
                    class="w-1/3 aspect-square bg-white rounded-[1.8rem] flex items-center justify-center border border-gray-50 overflow-hidden shadow-sm">
                    <img src="<?php echo $placeholder_img; ?>" class="w-full h-full object-cover">
                </div>
                <!-- Basic Info -->
                <div class="flex-1 flex flex-col justify-center">
                    <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">
                        <?php echo $asset->name; ?>
                    </h1>
                    <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider">
                        <?php echo $asset->asset_tag; ?>
                    </p>
                    <div class="mt-2 flex flex-wrap gap-2 text-[11px] font-bold">
                        <span class="text-gray-500 bg-gray-100/80 px-2 py-0.5 rounded-md uppercase">Category:
                            <?php echo $asset->sub_category ?: $asset->category_name; ?></span>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <div
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold <?php echo $condition_color == 'green' ? 'bg-emerald-50 text-emerald-700' : ($condition_color == 'blue' ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700'); ?>">
                            <span
                                class="w-1.5 h-1.5 rounded-full <?php echo $condition_color == 'green' ? 'bg-emerald-500' : ($condition_color == 'blue' ? 'bg-blue-500' : 'bg-red-500'); ?>"></span>
                            <?php echo $asset->asset_condition; ?>
                        </div>
                        <div
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-600">
                            <span class="material-symbols-rounded text-sm">location_on</span>
                            <?php echo $location_main; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-gray-50 rounded-full opacity-50 z-0"></div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="px-6 mt-6 grid grid-cols-3 gap-3">
        <button
            class="bg-white py-3.5 px-2 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center gap-1">
            <span class="material-symbols-rounded text-blue-600 text-xl">edit</span>
            <span class="text-[10px] font-bold text-gray-800">Edit</span>
        </button>
        <button
            class="bg-white py-3.5 px-2 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center gap-1">
            <span class="material-symbols-rounded text-red-600 text-xl">delete</span>
            <span class="text-[10px] font-bold text-gray-800">Delete</span>
        </button>
        <button
            class="bg-white py-3.5 px-2 rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center gap-1">
            <span class="material-symbols-rounded text-orange-400 text-xl font-light">history</span>
            <div class="flex flex-col items-center -mt-1">
                <span class="text-[9px] font-bold text-gray-800 leading-tight">Mark as Not Working</span>
                <span class="text-[7px] font-medium text-gray-400">View History</span>
            </div>
        </button>
    </div>

    <!-- Hardware Specifications Section -->
    <div class="px-6 mt-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-[0.15em]">Hardware Specifications
            </h2>
            <span class="material-symbols-rounded text-gray-300">more_horiz</span>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <!-- Proc -->
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-1">
                <div class="flex items-center gap-2 text-gray-400">
                    <span class="material-symbols-rounded text-lg">memory</span>
                    <span class="text-[9px] font-extrabold uppercase">Processor</span>
                </div>
                <p class="text-[13px] font-bold text-gray-900 mt-1"><?php echo $asset->processor ?: 'Not Specified'; ?>
                </p>
            </div>
            <!-- RAM -->
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-1">
                <div class="flex items-center gap-2 text-gray-400">
                    <span class="material-symbols-rounded text-lg">analytics</span>
                    <span class="text-[9px] font-extrabold uppercase">RAM</span>
                </div>
                <p class="text-[13px] font-bold text-gray-900 mt-1"><?php echo $asset->ram ?: 'Not Specified'; ?></p>
            </div>
            <!-- Storage -->
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-1">
                <div class="flex items-center gap-2 text-gray-400">
                    <span class="material-symbols-rounded text-lg">storage</span>
                    <span class="text-[9px] font-extrabold uppercase">Storage</span>
                </div>
                <p class="text-[13px] font-bold text-gray-900 mt-1"><?php echo $asset->hard_disk ?: 'Not Specified'; ?>
                </p>
            </div>
            <!-- OS -->
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-1">
                <div class="flex items-center gap-2 text-gray-400">
                    <span class="material-symbols-rounded text-lg">grid_view</span>
                    <span class="text-[9px] font-extrabold uppercase">Operating System</span>
                </div>
                <p class="text-[13px] font-bold text-gray-900 mt-1"><?php echo $asset->os ?: 'Not Specified'; ?></p>
            </div>
        </div>
    </div>

    <!-- Device Information -->
    <div class="mx-6 mt-8 p-6 bg-white rounded-[2rem] border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-[0.15em]">Device Information</h2>
            <span class="material-symbols-rounded text-gray-300">more_horiz</span>
        </div>
        <div class="space-y-5">
            <!-- Row 1 -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <span class="material-symbols-rounded text-indigo-500 text-lg">computer</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-extrabold text-gray-400 uppercase">Brand / Model</p>
                        <p class="text-xs font-bold text-gray-900"><?php echo $asset->brand_model ?: 'N/A'; ?></p>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <p class="text-[9px] font-extrabold text-gray-400 uppercase text-right">Serial Number</p>
                    <p class="text-xs font-bold text-gray-900 text-right"><?php echo $asset->serial_number ?: 'N/A'; ?>
                    </p>
                </div>
            </div>
            <!-- Row 2 -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                        <span class="material-symbols-rounded text-amber-500 text-lg">calendar_month</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-extrabold text-gray-400 uppercase">Purchase Date</p>
                        <p class="text-xs font-bold text-gray-900">
                            <?php echo $asset->purchase_date ? date('F d, Y', strtotime($asset->purchase_date)) : 'N/A'; ?>
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <p class="text-[9px] font-extrabold text-gray-400 uppercase text-right">Warranty Expiry</p>
                    <p class="text-xs font-bold text-gray-900 text-right">
                        <?php echo $asset->warranty_expiry ? date('F d, Y', strtotime($asset->warranty_expiry)) : 'N/A'; ?>
                    </p>
                </div>
            </div>
            <!-- Row 3 -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                        <span class="material-symbols-rounded text-teal-500 text-lg">storefront</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-extrabold text-gray-400 uppercase">Vendor Name</p>
                        <p class="text-xs font-bold text-gray-900"><?php echo $asset->vendor ?: 'N/A'; ?></p>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <p class="text-[9px] font-extrabold text-gray-400 uppercase text-right">Property ID</p>
                    <p class="text-xs font-bold text-gray-900 text-right">#<?php echo $asset->id; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Institutional Details -->
    <div class="mx-6 mt-8 p-6 bg-white rounded-[2rem] border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-[11px] font-extrabold text-gray-400 uppercase tracking-[0.15em]">Institutional Details</h2>
            <span class="material-symbols-rounded text-gray-300">more_horiz</span>
        </div>
        <div class="grid grid-cols-2 gap-y-6">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center mt-0.5">
                    <span class="material-symbols-rounded text-orange-500 text-lg">corporate_fare</span>
                </div>
                <div>
                    <p class="text-[9px] font-extrabold text-gray-400 uppercase">Department</p>
                    <p class="text-xs font-bold text-gray-900 leading-tight pr-2">
                        <?php echo $asset->college_name ?: 'N/A'; ?>
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mt-0.5">
                    <span class="material-symbols-rounded text-blue-500 text-lg">door_open</span>
                </div>
                <div>
                    <p class="text-[9px] font-extrabold text-gray-400 uppercase">Room No.</p>
                    <p class="text-xs font-bold text-gray-900"><?php echo $room_no; ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center mt-0.5">
                    <span class="material-symbols-rounded text-purple-500 text-lg">engineering</span>
                </div>
                <div>
                    <p class="text-[9px] font-extrabold text-gray-400 uppercase">Assigned Staff</p>
                    <p class="text-xs font-bold text-gray-900 leading-tight"><?php echo $asset->assigned_to ?: 'N/A'; ?>
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center mt-0.5">
                    <span class="material-symbols-rounded text-emerald-500 text-lg">check_circle</span>
                </div>
                <div>
                    <p class="text-[9px] font-extrabold text-gray-400 uppercase">Last Inspection</p>
                    <p class="text-xs font-bold text-gray-900"><?php echo date('M d, Y'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Branding -->
    <div class="mt-12 mb-8 flex flex-col items-center opacity-30">
        <div class="text-[12px] font-black tracking-[0.4em] text-gray-900">AZAM IT</div>
        <div class="text-[8px] font-bold text-gray-400 mt-1 uppercase tracking-[0.2em]">Inventory Management System
        </div>
    </div>

</body>

</html>