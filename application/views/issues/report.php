<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Report an Issue</h1>
            <p class="text-gray-500 mt-1">Scan a QR code or enter details to report a broken item.</p>
        </div>
        <a href="<?php echo site_url('issues'); ?>" class="text-sm text-primary-600 hover:text-primary-700 font-bold">
            View History &rarr;
        </a>
    </div>

    <!-- Feedback Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div
            class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <span class="text-sm font-bold">
                <?php echo $this->session->flashdata('success'); ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 shadow-sm">
            <span class="material-symbols-outlined text-xl">error</span>
            <span class="text-sm font-bold">
                <?php echo $this->session->flashdata('error'); ?>
            </span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Scanner Column -->
        <div class="md:col-span-1 space-y-4">
            <div class="bg-black rounded-2xl overflow-hidden shadow-lg border border-gray-800 relative group">
                <div id="reader" class="w-full h-64 bg-gray-900"></div>
                <!-- Scanner Overlay -->
                <div class="absolute inset-0 pointer-events-none border-2 border-primary-500/50 rounded-2xl"></div>
                <div class="absolute bottom-4 left-0 right-0 text-center pointer-events-none">
                    <span class="bg-black/70 text-white text-xs px-3 py-1 rounded-full backdrop-blur-sm">
                        Point camera at QR Code
                    </span>
                </div>
            </div>
            <button id="startScanBtn" type="button"
                class="w-full py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">qr_code_scanner</span> Start Scanner
            </button>
            <button id="stopScanBtn" type="button" style="display:none;"
                class="w-full py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">stop_circle</span> Stop Scanner
            </button>
        </div>

        <!-- Form Column -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-600">assignment</span> Issue Details
                    </h3>
                </div>

                <?php echo form_open_multipart('issues/store', ['class' => 'p-6 space-y-5']); ?>

                <!-- Asset Details (Auto-filled) -->
                <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100 space-y-4">
                    <h4
                        class="text-xs font-bold text-blue-800 uppercase tracking-widest mb-2 border-b border-blue-200 pb-2">
                        Scanned Details</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Asset Tag (ID)</label>
                            <div class="relative">
                                <input type="text" name="asset_tag" id="asset_tag" required
                                    class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm font-mono font-bold bg-white"
                                    placeholder="Scan or enter tag">
                                <button type="button" id="lookupBtn"
                                    class="absolute right-2 top-1.5 p-1 text-gray-400 hover:text-primary-600">
                                    <span class="material-symbols-outlined text-lg">search</span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Item Name</label>
                            <input type="text" id="asset_name" readonly
                                class="w-full rounded-lg border-gray-200 bg-gray-100/50 text-gray-500 text-sm cursor-not-allowed">
                        </div>

                        <!-- Auto-filled Extended Fields -->
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Category</label>
                            <input type="text" name="category" id="category"
                                class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm bg-white">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Brand / Model</label>
                            <input type="text" name="brand_model" id="brand_model"
                                class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm bg-white">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number"
                                class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm bg-white">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase">Condition</label>
                            <input type="text" name="asset_condition" id="asset_condition"
                                class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm bg-white">
                        </div>
                    </div>
                </div>

                <!-- Staff Info -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Reported By (Staff Name)</label>
                    <input type="text" name="staff_name" required
                        class="w-full rounded-xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="Enter your name">
                </div>

                <!-- Issue Description -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Issue Description</label>
                    <textarea name="issue_description" rows="4" required
                        class="w-full rounded-xl border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm p-3"
                        placeholder="Describe clearly correctly what is wrong with the item..."></textarea>
                </div>

                <!-- Photo Upload -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Photo Evidence</label>
                    <label class="block w-full cursor-pointer group">
                        <input type="file" name="issue_photo" accept="image/*" capture="environment" class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-full file:border-0
                                file:text-xs file:font-semibold
                                file:bg-primary-50 file:text-primary-700
                                hover:file:bg-primary-100 transition-all
                                cursor-pointer border border-dashed border-gray-300 rounded-xl p-2 hover:border-primary-400
                              " />
                    </label>
                    <p class="text-xs text-gray-400">Tap to capture from camera or select from gallery.</p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit"
                        class="bg-red-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-red-700 transition-all shadow-lg shadow-red-200 text-xs uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">warning</span> Submit Report
                    </button>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    const html5QrCode = new Html5Qrcode("reader");
    let isScanning = false;

    // Start Scan
    document.getElementById('startScanBtn').addEventListener('click', () => {
        // Increased size for better usability
        const config = { fps: 10, qrbox: { width: 200, height: 400 } };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .then(() => {
                document.getElementById('startScanBtn').style.display = 'none';
                document.getElementById('stopScanBtn').style.display = 'flex';
                isScanning = true;
            })
            .catch(err => {
                alert("Camera error: " + err);
            });
    });

    // Stop Scan
    document.getElementById('stopScanBtn').addEventListener('click', () => {
        html5QrCode.stop().then(() => {
            document.getElementById('startScanBtn').style.display = 'flex';
            document.getElementById('stopScanBtn').style.display = 'none';
            isScanning = false;
        }).catch(err => console.log(err));
    });

    // Handle Scan Success
    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning after success
        html5QrCode.stop().then(() => {
            document.getElementById('startScanBtn').style.display = 'flex';
            document.getElementById('stopScanBtn').style.display = 'none';
            isScanning = false;
        }).catch(err => console.error(err));

        console.log("Scanned:", decodedText);

        // Parse Asset Tag if it's a URL
        let tag = decodedText;
        if (decodedText.startsWith('http')) {
            // Robust parsing: Look for 'view_details/' marker
            if (decodedText.includes('view_details/')) {
                const parts = decodedText.split('view_details/');
                if (parts.length > 1) {
                    tag = parts[1];
                }
            } else {
                // Fallback: Last segment
                const cleanUrl = decodedText.replace(/\/$/, "");
                tag = cleanUrl.substring(cleanUrl.lastIndexOf('/') + 1);
            }

            // Cleanup: Remove query params and hashes
            tag = tag.split('?')[0].split('#')[0];

            try {
                tag = decodeURIComponent(tag);
            } catch (e) {
                console.warn("Decode failed:", e);
            }
        }

        console.log("Extracted Tag:", tag);

        // Fill Input
        document.getElementById('asset_tag').value = tag;

        // Trigger Lookup
        lookupAsset(tag);
    }

    // Manual Lookup
    document.getElementById('lookupBtn').addEventListener('click', () => {
        const tag = document.getElementById('asset_tag').value;
        if (tag) lookupAsset(tag);
    });

    // Auto-lookup on manual entry (debounced slightly or on blur)
    document.getElementById('asset_tag').addEventListener('change', (e) => {
        if (e.target.value) lookupAsset(e.target.value);
    });

    function lookupAsset(tag) { // Robust lookup function
        // Show loading state
        const assetNameInput = document.getElementById('asset_name');
        assetNameInput.value = "Searching...";
        assetNameInput.classList.remove('bg-red-50', 'text-red-700', 'bg-green-50', 'text-green-700');

        // Reset validation state
        document.getElementById('asset_tag').classList.remove('border-red-500', 'text-red-900');

        // Disable Lookup
        document.getElementById('lookupBtn').disabled = true;

        fetch('<?php echo site_url("issues/get_asset_details/"); ?>' + encodeURIComponent(tag))
            .then(response => {
                if (!response.ok) {
                    throw new Error("HTTP Status: " + response.status);
                }
                return response.text();
            })
            .then(text => {
                console.log("Server Response:", text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    // Check if likely HTML error page
                    if (text.trim().startsWith('<')) {
                        throw new Error("Server returned HTML (check login/errors)");
                    }
                    throw new Error("Invalid JSON response");
                }
            })
            .then(data => {
                document.getElementById('lookupBtn').disabled = false;

                if (data.status === 'success') {
                    // Populate fields
                    assetNameInput.value = data.asset.name;
                    document.getElementById('category').value = data.asset.category_name;
                    document.getElementById('brand_model').value = data.asset.brand_model;
                    document.getElementById('serial_number').value = data.asset.serial_number;
                    document.getElementById('asset_condition').value = data.asset.asset_condition;

                    // Flash success effect
                    assetNameInput.classList.add('bg-green-50', 'text-green-700');
                    setTimeout(() => assetNameInput.classList.remove('bg-green-50', 'text-green-700'), 1000);

                    // Lock the Tag Field (Read-only as per requirement)
                    document.getElementById('asset_tag').readOnly = true;
                    document.getElementById('asset_tag').classList.add('bg-gray-100', 'cursor-not-allowed');
                } else {
                    assetNameInput.value = data.message; // Show error message from server
                    assetNameInput.classList.add('bg-red-50', 'text-red-700');
                    document.getElementById('asset_tag').classList.add('border-red-500', 'text-red-900');

                    // Clear other fields
                    document.getElementById('category').value = '';
                    document.getElementById('brand_model').value = '';
                    document.getElementById('serial_number').value = '';
                    document.getElementById('asset_condition').value = '';
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('lookupBtn').disabled = false;
                assetNameInput.value = "Error: " + err.message;
                assetNameInput.classList.add('bg-red-50', 'text-red-700');
                // Also alert for visibility on mobile
                alert("Fetch Error: " + err.message);
            });
    }
</script>