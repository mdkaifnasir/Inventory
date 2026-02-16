<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Asset Categories</h1>
            <p class="text-gray-500 mt-1">Define and manage classifications for departmental hardware and electronics.
            </p>
        </div>
        <button onclick="toggleCategoryModal()"
            class="flex items-center gap-2 bg-primary-600 px-4 py-2 rounded-lg text-sm font-semibold text-white hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
            <span class="material-symbols-outlined text-xl">add_box</span> Add Category
        </button>
    </div>

    <!-- Alerts -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">check_circle</span>
            <span class="text-sm font-medium">
                <?php echo $this->session->flashdata('success'); ?>
            </span>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <span class="material-symbols-outlined text-xl">error</span>
            <div class="text-sm font-medium">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($categories as $cat): ?>
            <a href="<?php echo site_url('categories/view/' . $cat->id); ?>"
                class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:border-primary-400 hover:shadow-xl hover:shadow-primary-100/50 transition-all group relative overflow-hidden flex flex-col justify-between">

                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="p-4 bg-primary-50 text-primary-600 rounded-2xl group-hover:bg-primary-600 group-hover:text-white transition-all transform group-hover:scale-110 duration-300">
                            <span class="material-symbols-outlined text-3xl">
                                <?php echo $cat->icon; ?>
                            </span>
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity"
                            onclick="event.preventDefault();">
                            <button onclick="editCategory(<?php echo $cat->id; ?>)"
                                class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-colors">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </button>
                            <button
                                onclick="if(confirm('Are you sure? This may affect linked assets.')) window.location.href='<?php echo site_url('categories/delete/' . $cat->id); ?>';"
                                class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mb-3">
                        <span
                            class="px-2 py-1 rounded-md bg-primary-50 text-primary-600 text-[10px] font-bold uppercase tracking-wider group-hover:bg-primary-600 group-hover:text-white transition-colors">
                            <?php echo $cat->total_items; ?> Items
                        </span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-xl group-hover:text-primary-600 transition-colors">
                        <?php echo $cat->name; ?>
                    </h3>
                    <p class="text-sm text-gray-500 mt-2 line-clamp-2 min-h-[40px] leading-relaxed">
                        <?php echo $cat->description ? $cat->description : 'No description provided for this classification.'; ?>
                    </p>
                </div>

                <div
                    class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">calendar_today</span>
                        <?php echo date('M Y', strtotime($cat->created_at)); ?>
                    </div>
                    <span class="flex items-center gap-1 group-hover:text-primary-500 transition-colors">
                        View Details
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </span>
                </div>
            </a>
        <?php endforeach; ?>

        <?php if (empty($categories)): ?>
            <div class="col-span-full py-20 text-center bg-white rounded-xl border-2 border-dashed border-gray-200">
                <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">inventory_2</span>
                <p class="text-gray-400 font-medium">No categories defined yet. Start by adding one!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Category Modal (Add/Edit) -->
<div id="categoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleCategoryModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-2xl bg-white p-8 shadow-2xl transition-all transform scale-95 opacity-0 flex flex-col gap-6"
            id="catModalContent">
            <div class="flex items-center justify-between">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Add Category</h3>
                <button onclick="toggleCategoryModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="categoryForm" action="<?php echo site_url('categories/store'); ?>" method="POST"
                class="space-y-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Category Name</label>
                    <input type="text" name="name" id="catName"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="e.g. Laptops, Lab Equipment" required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Icon Name (Material Symbol)</label>
                    <input type="text" name="icon" id="catIcon"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5"
                        placeholder="e.g. computer, memory, pcb" value="category">
                    <p class="text-[10px] text-gray-400">Use any valid <a href="https://fonts.google.com/icons"
                            target="_blank" class="text-primary-600 underline">Google Icon</a> name.</p>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase">Description</label>
                    <textarea name="description" id="catDesc" rows="3"
                        class="w-full rounded-lg border-gray-200 focus:border-primary-500 focus:ring-primary-500 text-sm py-2"
                        placeholder="Brief details about this classification..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-primary-600 text-white font-bold py-3 rounded-lg hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleCategoryModal(title = "Add Category", action = "<?php echo site_url('categories/store'); ?>") {
        const modal = document.getElementById('categoryModal');
        const content = document.getElementById('catModalContent');
        const form = document.getElementById('categoryForm');
        const titleEl = document.getElementById('modalTitle');

        if (modal.classList.contains('hidden')) {
            titleEl.textContent = title;
            form.action = action;

            // Clear fields if it's Add mode
            if (title === "Add Category") {
                document.getElementById('catName').value = "";
                document.getElementById('catIcon').value = "category";
                document.getElementById('catDesc').value = "";
            }

            modal.classList.remove('hidden');
            requestAnimationFrame(() => content.classList.remove('scale-95', 'opacity-0'));
        } else {
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }
    }

    function editCategory(id) {
        fetch(`<?php echo site_url('categories/get_category_json/'); ?>` + id)
            .then(res => res.json())
            .then(data => {
                document.getElementById('catName').value = data.name;
                document.getElementById('catIcon').value = data.icon;
                document.getElementById('catDesc').value = data.description;

                toggleCategoryModal("Edit Category", `<?php echo site_url('categories/update/'); ?>` + id);
            });
    }
</script>