<div class="max-w-[1000px] mx-auto">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a class="hover:text-primary-600 flex items-center gap-1" href="<?php echo site_url('dashboard'); ?>">
            <span class="material-symbols-outlined text-base">home</span>
            Dashboard
        </a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a class="hover:text-primary-600" href="<?php echo site_url('assets'); ?>">Inventory</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="text-gray-900 font-bold">Edit Item</span>
    </nav>

    <!-- Page Heading -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <span
                class="px-3 py-1 bg-primary-100 text-primary-700 text-[10px] font-bold uppercase tracking-widest rounded-full">Editing
                Mode</span>
            <span class="text-gray-400 text-sm font-mono">
                <?php echo $asset->asset_tag; ?>
            </span>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Update Asset Details</h1>
        <p class="text-gray-500 mt-1">Modify information for this item in the department database.</p>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?php echo form_open('assets/update', ['class' => 'space-y-6']); ?>
    <input type="hidden" name="id" value="<?php echo $asset->id; ?>">

    <!-- Basic Information Section -->
    <section
        class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-600">info</span>
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Basic Information</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Category Select -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Category</label>
                    <select name="category_id" id="category_select"
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all font-medium"
                        required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat->id; ?>" <?php echo ($asset->category_id == $cat->id) ? 'selected' : ''; ?>>
                                <?php echo $cat->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Item Type Select (Info: Based on Category) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Item Type</label>
                    <div class="relative">
                        <select name="name" id="item_type_select"
                            class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all font-medium appearance-none bg-none">
                            <option value="<?php echo $asset->name; ?>">
                                <?php echo $asset->name; ?>
                            </option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <span class="material-symbols-outlined">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-full grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Brand / Model</label>
                    <input name="brand_model"
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                        placeholder="e.g. Dell / UltraSharp" type="text"
                        value="<?php echo set_value('brand_model', $asset->brand_model); ?>" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Processor</label>
                    <input name="processor"
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                        placeholder="e.g. Core i7-13th Gen" type="text"
                        value="<?php echo set_value('processor', $asset->processor); ?>" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">RAM (With Size)</label>
                    <input name="ram"
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                        placeholder="e.g. 16.0 GB" type="text" value="<?php echo set_value('ram', $asset->ram); ?>" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Hard Disk / SSD (with
                        Size)</label>
                    <input name="hard_disk"
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                        placeholder="e.g. 1 TB / 256 SSD" type="text"
                        value="<?php echo set_value('hard_disk', $asset->hard_disk); ?>" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Operating System</label>
                    <input name="os"
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                        placeholder="e.g. Win 10" type="text" value="<?php echo set_value('os', $asset->os); ?>" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Assigned To (Staff/Lab)</label>
                    <input name="assigned_to"
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                        placeholder="e.g. Registrar Office" type="text"
                        value="<?php echo set_value('assigned_to', $asset->assigned_to); ?>" />
                </div>
            </div>

            <div class="col-span-full">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">AMC Details</label>
                <textarea name="amc_details"
                    class="w-full p-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    rows="2"><?php echo set_value('amc_details', $asset->amc_details); ?></textarea>
            </div>

            <div class="col-span-full">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Remarks</label>
                <textarea name="remarks"
                    class="w-full p-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    rows="2"><?php echo set_value('remarks', $asset->remarks); ?></textarea>
            </div>
            <div class="col-span-full">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Asset
                    Condition</label>
                <input type="hidden" name="asset_condition" id="asset_condition_input"
                    value="<?php echo $asset->asset_condition; ?>">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <?php
                    $conditions = [
                        'New' => 'fiber_new',
                        'Working' => 'check_circle',
                        'Used (Good)' => 'thumb_up',
                        'Refurbished' => 'build'
                    ];
                    foreach ($conditions as $label => $icon):
                        $isActive = ($asset->asset_condition == $label);
                        ?>
                        <button type="button" onclick="setFullCondition('<?php echo $label; ?>', this)"
                            class="condition-btn-full flex items-center justify-center gap-2 h-14 rounded-xl transition-all <?php echo $isActive ? 'border-2 border-primary-600 bg-primary-50 text-primary-600 font-black' : 'border border-gray-200 text-gray-500 font-bold'; ?> text-xs uppercase tracking-widest">
                            <span class="material-symbols-outlined text-lg">
                                <?php echo $icon; ?>
                            </span>
                            <?php echo $label; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Identification Section -->
    <section
        class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-600">qr_code_scanner</span>
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Identification</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <div class="md:col-span-2 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Serial Number</label>
                    <input name="serial_number"
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all font-mono"
                        placeholder="SN-XXXX-XXXX-XXXX" type="text"
                        value="<?php echo set_value('serial_number', $asset->serial_number); ?>" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Asset Tag</label>
                    <div class="flex gap-2">
                        <input id="asset_tag_input" name="asset_tag"
                            class="flex-1 h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all font-mono"
                            placeholder="COL-2024-001" type="text"
                            value="<?php echo set_value('asset_tag', $asset->asset_tag); ?>" required />
                        <button type="button" onclick="generateAssetTag()"
                            class="px-4 bg-gray-100 text-gray-700 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-gray-200 transition-colors">
                            Auto-Gen
                        </button>
                    </div>
                </div>
            </div>
            <div
                class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-100 rounded-xl bg-gray-50/50">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Tag Preview</p>
                <div id="qrcode_add"
                    class="w-32 h-32 bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-4 flex items-center justify-center">
                    <span class="material-symbols-outlined text-gray-200 text-7xl">qr_code_2</span>
                </div>
                <p class="text-[10px] text-gray-400 text-center uppercase font-bold tracking-tighter">Property of
                    Engineering Dept.</p>
                <p id="tag_preview_text" class="text-xs font-mono font-bold text-gray-700 mt-1">
                    <?php echo $asset->asset_tag; ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Purchase & Warranty Section -->
    <section
        class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-600">payments</span>
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Purchase & Warranty</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Purchase Date</label>
                <input name="purchase_date"
                    class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    type="date" value="<?php echo set_value('purchase_date', $asset->purchase_date); ?>" />
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Vendor</label>
                <select name="vendor"
                    class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all font-medium">
                    <option value="">Select Vendor</option>
                    <option value="Dell Business" <?php echo ($asset->vendor == 'Dell Business') ? 'selected' : ''; ?>>Dell Business</option>
                    <option value="Amazon Business" <?php echo ($asset->vendor == 'Amazon Business') ? 'selected' : ''; ?>>Amazon Business</option>
                    <option value="Local Store" <?php echo ($asset->vendor == 'Local Store') ? 'selected' : ''; ?>>Local
                        Store / Institutional Vendor</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Warranty Expiry</label>
                <input name="warranty_expiry"
                    class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    type="date" value="<?php echo set_value('warranty_expiry', $asset->warranty_expiry); ?>" />
            </div>
        </div>
    </section>

    <!-- Deployment Section -->
    <section
        class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-600">location_on</span>
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Deployment & Stock</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Current Location</label>
                <input name="location"
                    class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all"
                    placeholder="e.g. Lab 402, Shelf B" type="text"
                    value="<?php echo set_value('location', $asset->location); ?>" />
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Current Status</label>
                <select name="status"
                    class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all font-medium">
                    <option value="In Stock" <?php echo ($asset->status == 'In Stock') ? 'selected' : ''; ?>>In Stock
                    </option>
                    <option value="Deployed" <?php echo ($asset->status == 'Deployed') ? 'selected' : ''; ?>>Deployed
                    </option>
                    <option value="Reserved" <?php echo ($asset->status == 'Reserved') ? 'selected' : ''; ?>>Reserved
                    </option>
                    <option value="Faulty" <?php echo ($asset->status == 'Faulty') ? 'selected' : ''; ?>>Faulty</option>
                    <option value="In Repair" <?php echo ($asset->status == 'In Repair') ? 'selected' : ''; ?>>In Repair
                    </option>
                    <option value="Scrap" <?php echo ($asset->status == 'Scrap') ? 'selected' : ''; ?>>Scrap</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Quantity</label>
                <input name="quantity"
                    class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-4 focus:ring-primary-500/10 focus:border-primary-600 transition-all bg-gray-50 cursor-not-allowed"
                    min="1" type="number" value="<?php echo $asset->quantity; ?>" readonly />
            </div>
        </div>
    </section>

    <!-- Action Buttons -->
    <div class="flex items-center justify-end gap-4 py-8 border-t border-gray-100 mt-8">
        <a href="<?php echo site_url('assets/view_details/' . $asset->asset_tag); ?>"
            class="px-6 py-3 text-gray-500 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-gray-100 transition-colors">
            Cancel
        </a>
        <button
            class="px-10 py-3 bg-primary-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-primary-500/20 hover:bg-primary-700 hover:-translate-y-0.5 transition-all active:scale-95 flex items-center gap-2"
            type="submit">
            <span class="material-symbols-outlined text-xl">save</span>
            Update Item
        </button>
    </div>
    <?php echo form_close(); ?>
</div>

<script>
    let qrAdd = null;

    // Elements
    const categorySelect = document.getElementById('category_select');
    const itemTypeSelect = document.getElementById('item_type_select');
    const inputNames = ['brand_model', 'serial_number', 'asset_tag', 'location'];

    // Ensure elements exist before adding listeners
    if (categorySelect && itemTypeSelect) {
        // Populate Items with OptGroups (Unified)
        categorySelect.addEventListener('change', function () {
            const catId = this.value;

            // Reset Item Type Select
            itemTypeSelect.innerHTML = '<option value="">Select Item Type</option>';
            itemTypeSelect.disabled = true;

            if (catId) {
                // Use a clean URL construction
                const baseUrl = "<?php echo site_url('assets/get_items_by_category'); ?>";
                const url = `${baseUrl}?category_id=${catId}`;

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        itemTypeSelect.disabled = false;
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item;
                                option.textContent = item;
                                if (item === "<?php echo $asset->name; ?>") {
                                    option.selected = true;
                                }
                                itemTypeSelect.appendChild(option);
                            });
                        } else {
                            itemTypeSelect.innerHTML = '<option value="">No Items Found</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching items:', error);
                        itemTypeSelect.innerHTML = '<option value="">Error loading items</option>';
                    });
            }

            updateFullQRCode();
        });

        itemTypeSelect.addEventListener('change', updateFullQRCode);
    }

    function updateFullQRCode() {
        // Safety check for QRCode library
        if (typeof QRCode === 'undefined') {
            console.warn('QRCode library not loaded.');
            return;
        }

        let name = itemTypeSelect && itemTypeSelect.options[itemTypeSelect.selectedIndex] ? itemTypeSelect.options[itemTypeSelect.selectedIndex].text : 'N/A';
        if (!name || name === 'Select Item Type' || name === 'Select Category First' || name === 'No Items Found' || name === 'Error loading items') name = 'N/A';

        const catSelect = document.querySelector('select[name="category_id"]');
        const cat = catSelect && catSelect.options[catSelect.selectedIndex] ? catSelect.options[catSelect.selectedIndex].text : 'N/A';

        const brandInput = document.querySelector('input[name="brand_model"]');
        const brand = brandInput ? brandInput.value : 'N/A';

        const snInput = document.querySelector('input[name="serial_number"]');
        const sn = snInput ? snInput.value : 'N/A';

        const tagInput = document.getElementById('asset_tag_input');
        const tag = tagInput ? tagInput.value : 'N/A';

        const locInput = document.querySelector('input[name="location"]');
        const loc = locInput ? locInput.value : 'N/A';

        const condInput = document.getElementById('asset_condition_input');
        const cond = condInput ? condInput.value : 'Refurbished';

        const data = `Item: ${name}\nCat: ${cat}\nBrand: ${brand}\nSN: ${sn}\nTag: ${tag}\nLoc: ${loc}\nCond: ${cond}`;

        const container = document.getElementById('qrcode_add');
        if (container) {
            container.innerHTML = "";
            try {
                qrAdd = new QRCode(container, {
                    text: data,
                    width: 120,
                    height: 120,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.M
                });
            } catch (e) {
                console.error("QRCode generation failed:", e);
                container.textContent = "QR Error";
            }
        }

        const previewText = document.getElementById('tag_preview_text');
        if (previewText) {
            previewText.textContent = tag;
        }
    }

    function generateAssetTag() {
        const date = new Date();
        const year = date.getFullYear();
        const rand = Math.floor(1000 + Math.random() * 9000);
        const tag = `COL-${year}-${rand}`;
        const tagInput = document.getElementById('asset_tag_input');
        if (tagInput) {
            tagInput.value = tag;
            updateFullQRCode();
        }
    }

    function setFullCondition(val, btn) {
        const input = document.getElementById('asset_condition_input');
        if (input) input.value = val;

        document.querySelectorAll('.condition-btn-full').forEach(b => {
            b.classList.remove('border-2', 'border-primary-600', 'bg-primary-50', 'text-primary-600', 'font-black');
            b.classList.add('border', 'border-gray-200', 'text-gray-500', 'font-bold');
        });

        if (btn) {
            btn.classList.add('border-2', 'border-primary-600', 'bg-primary-50', 'text-primary-600', 'font-black');
            btn.classList.remove('border', 'border-gray-200', 'text-gray-500', 'font-bold');
        }

        updateFullQRCode();
    }

    // Sync all relevant inputs with preview
    inputNames.forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) el.addEventListener('input', updateFullQRCode);
    });

    // Initialize QR Code on load if data exists
    document.addEventListener('DOMContentLoaded', function () {
        // Trigger category change to load initial item types
        const initialCat = categorySelect.value;
        if (initialCat) {
            categorySelect.dispatchEvent(new Event('change'));
        }

        // Small delay to ensure libraries load
        setTimeout(updateFullQRCode, 500);
    });
</script>