<?php require_once '../app/views/layout/header.php'; ?>
<div class="max-w-4xl mx-auto bg-white p-6 sm:p-10 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100">

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-semibold text-slate-800 mb-1">Upravit záznam</h2>
        <p class="text-slate-500 text-sm">Upravujete data pro titul: <strong class="text-slate-700"><?= htmlspecialchars($book['title']) ?></strong></p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=book/update/<?= htmlspecialchars($book['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label for="title" class="block text-sm font-semibold text-slate-600 mb-1">Název titulu <span class="text-teal-500">*</span></label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
            </div>

            <div class="sm:col-span-2">
                <label for="author" class="block text-sm font-semibold text-slate-600 mb-1">Autor / Vývojář <span class="text-teal-500">*</span></label>
                <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
            </div>

            <div>
                <label for="category" class="block text-sm font-semibold text-slate-600 mb-1">Kategorie <span class="text-teal-500">*</span></label>
                <select id="category" name="category" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    <option value="">-- Vyberte kategorii --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>" <?= ($book['category'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="subcategory" class="block text-sm font-semibold text-slate-600 mb-1">Subkategorie</label>
                <select id="subcategory" name="subcategory" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    <option value="">-- Vyberte subkategorii --</option>
                    <?php foreach ($subcategories as $sub): ?>
                        <option value="<?= htmlspecialchars($sub['id']) ?>" <?= ($book['subcategory'] == $sub['id']) ? 'selected' : '' ?>><?= htmlspecialchars($sub['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="year" class="block text-sm font-semibold text-slate-600 mb-1">Rok vydání <span class="text-teal-500">*</span></label>
                <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year']) ?>" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="price" class="block text-sm font-semibold text-slate-600 mb-1">Cena (Kč)</label>
                <input type="number" id="price" name="price" step="0.5" value="<?= htmlspecialchars($book['price'] ?? '') ?>" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="isbn" class="block text-sm font-semibold text-slate-600 mb-1">ID / Kód platformy (ISBN) <span class="text-teal-500">*</span></label>
                <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="link" class="block text-sm font-semibold text-slate-600 mb-1">Odkaz na web</label>
                <input type="text" id="link" name="link" value="<?= htmlspecialchars($book['link'] ?? '') ?>" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-semibold text-slate-600 mb-1">Popis záznamu</label>
            <textarea name="description" id="description" rows="3" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all resize-y"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
        </div>

        <?php $existingImages = json_decode($book['images'] ?? '[]', true); ?>
        <?php if (!empty($existingImages)): ?>
        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-1">Aktuální obrázky v databázi:</label>
            <div class="flex flex-wrap gap-2 p-2 bg-slate-50 border border-slate-200 rounded-xl">
                <?php foreach ($existingImages as $img): ?>
                    <div class="w-16 h-16 overflow-hidden rounded-lg border border-slate-300 shadow-sm relative">
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" alt="Obrázek" class="w-full h-full object-cover">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-1">Nahrát nové obrázky (staré se přemáznou)</label>
            <div class="w-full">
                <label for="images" class="flex flex-col items-center justify-center w-full h-20 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-100 hover:border-teal-400 transition-colors">
                    <div class="flex flex-col items-center justify-center pt-2 pb-3">
                        <span id="file-title" class="text-sm text-slate-500 font-semibold">Klikni pro výběr nových souborů</span>
                    </div>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                </label>
            </div>
        </div>

        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-4">
            <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>" class="text-slate-400 hover:text-slate-700 font-medium px-2 py-2 transition-colors">Zrušit změny</a>
            <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-10 py-3 rounded-2xl shadow-lg shadow-teal-600/30 hover:-translate-y-0.5 transition-all">
                Uložit změny
            </button>
        </div>
    </form>
</div>

<script>
    const fileInput = document.getElementById('images');
    const fileTitle = document.getElementById('file-title');
    fileInput.addEventListener('change', function(event) {
        const files = event.target.files;
        if (files.length === 0) {
            fileTitle.textContent = 'Klikni pro výběr nových souborů';
        } else {
            fileTitle.textContent = 'Vybráno celkem: ' + files.length;
        }
    });
</script>
<?php require_once '../app/views/layout/footer.php'; ?>