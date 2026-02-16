<div class="max-w-[800px] mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Bulk Import Assets</h1>
        <p class="text-gray-500">Upload a CSV file to register multiple hardware items at once.</p>
    </div>

    <!-- Instructions Card -->
    <div class="bg-primary-50 border border-primary-100 p-6 rounded-2xl flex gap-6 items-start">
        <div class="bg-primary-600 p-3 rounded-xl text-white">
            <span class="material-symbols-outlined text-2xl">lightbulb</span>
        </div>
        <div class="space-y-3">
            <h3 class="font-bold text-primary-900">Important Instructions</h3>
            <ul class="text-primary-800 text-sm space-y-1 list-disc pl-4 opacity-80">
                <li>Use a comma-separated CSV file.</li>
                <li><strong>Category Name</strong> must match exactly with those in System Settings.</li>
                <li><strong>Asset Tag</strong> must be unique; duplicates will be skipped.</li>
                <li>Date format should be <code>YYYY-MM-DD</code>.</li>
            </ul>
            <a href="<?php echo site_url('assets/download_template'); ?>"
                class="inline-flex items-center gap-2 text-primary-700 font-bold text-sm hover:underline mt-2">
                <span class="material-symbols-outlined text-lg">download</span>
                Download CSV Template
            </a>
        </div>
    </div>

    <!-- Upload Form -->
    <?php echo form_open_multipart('assets/process_import', ['class' => 'space-y-6']); ?>

    <!-- College Selection for Direct Allocation -->
    <div class="space-y-2">
        <label for="college_id" class="block text-sm font-bold text-gray-700">Assign to College (Optional)</label>
        <select name="college_id" id="college_id"
            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all font-medium text-gray-700">
            <option value="">-- Central Storage (In Stock) --</option>
            <?php foreach ($colleges as $college): ?>
                <option value="<?php echo $college->id; ?>">
                    <?php echo $college->name; ?> (<?php echo $college->code; ?>)
                </option>
            <?php endforeach; ?>
        </select>
        <p class="text-xs text-gray-500">Leave blank to keep items in "In Stock" status.</p>
    </div>

    <div id="drop-zone"
        class="relative bg-white border-2 border-dashed border-gray-200 rounded-2xl p-12 flex flex-col items-center justify-center transition-all hover:border-primary-400 hover:bg-primary-50/10 group cursor-pointer">
        <input type="file" name="csv_file" id="file-input" class="absolute inset-0 opacity-0 cursor-pointer"
            accept=".csv, .xlsx" required>

        <div
            class="size-16 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center group-hover:bg-primary-100 group-hover:text-primary-600 transition-all mb-4">
            <span class="material-symbols-outlined text-4xl">cloud_upload</span>
        </div>

        <div class="text-center">
            <p class="text-lg font-bold text-gray-900">Click or drag file to upload</p>
            <p class="text-sm text-gray-500 mt-1">Accepts .CSV and .XLSX files (Max 5MB)</p>
        </div>

        <!-- Selected File Info (Dynamic) -->
        <div id="file-info"
            class="hidden mt-6 p-3 bg-white border border-gray-100 rounded-xl flex items-center gap-3 animate-fade-in">
            <span class="material-symbols-outlined text-emerald-500">description</span>
            <div>
                <p id="filename" class="text-sm font-bold text-gray-900"></p>
                <p id="filesize" class="text-[10px] text-gray-400 uppercase font-bold tracking-widest"></p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="<?php echo site_url('assets'); ?>" class="px-6 py-3 text-gray-500 font-bold text-sm">Cancel</a>
        <button type="submit"
            class="px-10 py-3 bg-primary-600 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20 hover:bg-primary-700 hover:-translate-y-0.5 transition-all">
            Start Import Process
        </button>
    </div>
    <?php echo form_close(); ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="p-6 bg-red-50 border border-red-200 rounded-2xl space-y-2">
            <div class="flex items-center gap-2 text-red-700 font-bold mb-2">
                <span class="material-symbols-outlined">error</span>
                Error Report
            </div>
            <div class="text-sm text-red-600 leading-relaxed font-medium">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    const fileInput = document.getElementById('file-input');
    const dropZone = document.getElementById('drop-zone');
    const fileInfo = document.getElementById('file-info');
    const filename = document.getElementById('filename');
    const filesize = document.getElementById('filesize');

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            fileInfo.classList.remove('hidden');
            filename.textContent = file.name;
            filesize.textContent = (file.size / 1024).toFixed(1) + ' KB';
            dropZone.classList.add('border-emerald-300', 'bg-emerald-50/10');
        }
    });

    // Simple Drag & Drop highlight
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('border-primary-400', 'bg-primary-50/10'), false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('border-primary-400', 'bg-primary-50/10'), false);
    });
</script>