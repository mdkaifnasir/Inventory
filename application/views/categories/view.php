<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="<?php echo site_url('categories'); ?>"
                class="p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-primary-600 hover:border-primary-200 transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-600">
                        <?php echo $category->icon; ?>
                    </span>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <?php echo $category->name; ?>
                    </h1>
                </div>
                <p class="text-gray-500 mt-1">
                    <?php echo count($assets); ?> items found in this category.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white border border-gray-200 rounded-lg px-4 py-2 flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Category ID:</span>
                <span class="text-sm font-bold text-gray-900">#
                    <?php echo $category->id; ?>
                </span>
            </div>
        </div>
    </div>

    <?php if ($category->description): ?>
        <div class="bg-primary-50 border border-primary-100 p-4 rounded-xl">
            <p class="text-sm text-primary-800 leading-relaxed italic">
                "
                <?php echo $category->description; ?>"
            </p>
        </div>
    <?php endif; ?>

    <!-- Assets List -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Asset
                            Information</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Condition/Status
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Location</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Ownership</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($assets as $asset): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                        <span class="material-symbols-outlined">inventory_2</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">
                                            <?php echo $asset->name; ?>
                                        </p>
                                        <p class="text-[11px] font-bold text-primary-600 uppercase tracking-wider">
                                            <?php echo $asset->asset_tag; ?>
                                        </p>
                                        <p class="text-[11px] text-gray-400">
                                            <?php echo $asset->brand_model; ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border <?php
                                    echo ($asset->asset_condition == 'New') ? 'bg-green-50 text-green-700 border-green-100' : 'bg-orange-50 text-orange-700 border-orange-100';
                                    ?>">
                                        <?php echo $asset->asset_condition; ?>
                                    </span>
                                    <div class="flex items-center gap-1.5 text-[11px] font-medium <?php
                                    echo ($asset->status == 'In Stock') ? 'text-blue-600' : 'text-purple-600';
                                    ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?php
                                        echo ($asset->status == 'In Stock') ? 'bg-blue-500' : 'bg-purple-500';
                                        ?>"></span>
                                        <?php echo $asset->status; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-medium text-gray-700">
                                    <?php echo $asset->location ? $asset->location : 'Not Specified'; ?>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($asset->college_name): ?>
                                    <div class="flex flex-col gap-0.5">
                                        <p class="text-xs font-bold text-gray-900">
                                            <?php echo $asset->college_name; ?>
                                        </p>
                                        <p class="text-[10px] font-bold text-gray-400">
                                            <?php echo $asset->college_code; ?>
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs italic text-gray-400 tracking-tight">Central Storage</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="viewAssetDetails(<?php echo htmlspecialchars(json_encode($asset)); ?>)"
                                    class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-all"
                                    title="View Details">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($assets)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-200">folder_open</span>
                                    <p class="text-sm font-medium text-gray-400">No assets found in this category.</p>
                                    <a href="<?php echo site_url('assets/add'); ?>"
                                        class="text-sm text-primary-600 font-bold hover:underline mt-2">
                                        + Add New Item
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Asset Details Modal -->
<div id="assetModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-md flex items-center justify-center p-4">
    <div
        class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all animate-fade-up">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-4">
                <div
                    class="size-14 rounded-2xl bg-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-200">
                    <span class="material-symbols-outlined text-2xl">barcode_scanner</span>
                </div>
                <div>
                    <h3 id="m_asset_name" class="text-xl font-black text-gray-900 tracking-tight">Asset Details</h3>
                    <p id="m_asset_tag" class="text-xs font-bold text-primary-600 uppercase tracking-widest"></p>
                </div>
            </div>
            <button onclick="closeAssetModal()"
                class="size-10 rounded-xl hover:bg-gray-100 flex items-center justify-center text-gray-400 transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="p-8 grid grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-2">Technical
                        Info</label>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs font-medium text-gray-500">Category</span>
                            <span id="m_cat" class="text-xs font-black text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs font-medium text-gray-500">Brand/Model</span>
                            <span id="m_brand" class="text-xs font-black text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-xs font-medium text-gray-500">Serial No</span>
                            <span id="m_sn" class="text-xs font-mono font-bold text-gray-900"></span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 text-[#101922]">
                    <h3 class="font-bold mb-6">Quick Inventory Check</h3>
                    <div class="space-y-4">
                        <a href="<?php echo site_url('auditlogs'); ?>"
                            class="p-4 bg-gray-50 rounded-lg flex items-center gap-4 border border-gray-100 hover:bg-gray-100 transition-all text-left block">
                            <span class="material-symbols-outlined text-primary-600">history</span>
                            <div>
                                <p class="text-sm font-bold">Audit History</p>
                                <p class="text-xs text-gray-500">Review recent log session</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-2">Asset
                        Condition</label>
                    <div id="m_condition"
                        class="inline-flex px-4 py-2 rounded-xl bg-orange-50 text-orange-600 text-xs font-black uppercase tracking-tighter">
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center bg-gray-50 rounded-3xl p-6 border border-gray-100">
                <div id="m_qrcode" class="mb-4 bg-white p-3 rounded-2xl shadow-sm"></div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Institutional ID
                    Code</p>
                <div class="mt-6 flex gap-3">
                    <button onclick="downloadAssetTag()"
                        class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">download</span>
                        Download Tag
                    </button>
                </div>
            </div>
        </div>

        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex justify-between items-center">
            <p class="text-[10px] font-bold text-gray-400 italic">Registered on <span id="m_date"
                    class="text-gray-600"></span></p>
            <button onclick="closeAssetModal()"
                class="px-8 py-3 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-xl shadow-gray-200">Close
                Panel</button>
        </div>
    </div>
</div>

<script>
    let currentAssetTag = '';

    function viewAssetDetails(asset) {
        currentAssetTag = asset.asset_tag;
        document.getElementById('m_asset_name').textContent = asset.name;
        document.getElementById('m_asset_tag').textContent = asset.asset_tag;
        document.getElementById('m_cat').textContent = "<?php echo $category->name; ?>";
        document.getElementById('m_brand').textContent = asset.brand_model || 'N/A';
        document.getElementById('m_sn').textContent = asset.serial_number || 'N/A';
        document.getElementById('m_condition').textContent = asset.asset_condition || 'New';
        document.getElementById('m_date').textContent = new Date(asset.created_at).toLocaleDateString();

        // Generate QR code
        const qrContent = `Item: ${asset.name}\nTag: ${asset.asset_tag}\nCat: <?php echo $category->name; ?>\nCond: ${asset.asset_condition}`;
        const container = document.getElementById('m_qrcode');
        container.innerHTML = "";

        // Ensure QRCode is loaded
        if (typeof QRCode !== 'undefined') {
            new QRCode(container, {
                text: qrContent,
                width: 140,
                height: 140,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        }

        document.getElementById('assetModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function downloadAssetTag() {
        const qrImg = document.querySelector('#m_qrcode img');
        if (qrImg) {
            const link = document.createElement('a');
            link.href = qrImg.src;
            link.download = `Tag_${currentAssetTag}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            alert('Tag image not ready yet. Please try again.');
        }
    }

    function closeAssetModal() {
        document.getElementById('assetModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>