<?php require_once '../app/views/layout/header.php'; ?>
<div class="max-w-4xl mx-auto bg-white p-6 sm:p-10 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100">

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-semibold text-slate-800 mb-1">Přidat záznam</h2>
        <p class="text-slate-500 text-sm">Vyplňte pečlivě všechny detaily pro komunitní databázi.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=book/store" method="post" enctype="multipart/form-data" class="space-y-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label for="title" class="block text-sm font-semibold text-slate-600 mb-1">Název titulu <span class="text-teal-500">*</span></label>
                <input type="text" id="title" name="title" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
            </div>

            <div class="sm:col-span-2">
                <label for="author" class="block text-sm font-semibold text-slate-600 mb-1">Autor / Vývojář <span class="text-teal-500">*</span></label>
                <input type="text" id="author" name="author" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
            </div>

            <div>
                <label for="isbn" class="block text-sm font-semibold text-slate-600 mb-1">ID / Kód platformy <span class="text-teal-500">*</span></label>
                <input type="text" id="isbn" name="isbn" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="year" class="block text-sm font-semibold text-slate-600 mb-1">Rok vydání <span class="text-teal-500">*</span></label>
                <input type="number" id="year" name="year" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="category" class="block text-sm font-semibold text-slate-600 mb-1">Kategorie <span class="text-teal-500">*</span></label>
                <select id="category" name="category" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    <option value="">-- Vyberte kategorii --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="subcategory" class="block text-sm font-semibold text-slate-600 mb-1">Subkategorie</label>
                <select id="subcategory" name="subcategory" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    <option value="">-- Vyberte subkategorii --</option>
                    <?php foreach ($subcategories as $sub): ?>
                        <option value="<?= htmlspecialchars($sub['id']) ?>"><?= htmlspecialchars($sub['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="price" class="block text-sm font-semibold text-slate-600 mb-1">Cena (Kč)</label>
                <input type="number" id="price" name="price" step="0.5" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="link" class="block text-sm font-semibold text-slate-600 mb-1">Odkaz na web</label>
                <input type="text" id="link" name="link" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-semibold text-slate-600 mb-1">Popis záznamu</label>
            <textarea name="description" id="description" rows="3" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-2.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all resize-y"></textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-1">Obrázky (můžete nahrát více)</label>
            <div class="w-full">
                <label for="images" class="flex flex-col items-center justify-center w-full h-20 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-100 hover:border-teal-400 transition-colors">
                    <div class="flex flex-col items-center justify-center pt-2 pb-3">
                        <span id="file-title" class="text-sm text-slate-500 font-semibold">Klikni pro výběr souborů</span>
                        <span id="file-info" class="text-xs text-slate-400 mt-1">JPG, PNG, WebP</span>
                    </div>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                </label>
            </div>
        </div>

        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-4">
            <a href="<?= BASE_URL ?>/index.php" class="text-slate-400 hover:text-slate-700 font-medium px-2 py-2 transition-colors">Zrušit</a>
            <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-10 py-3 rounded-2xl shadow-lg shadow-teal-600/30 hover:-translate-y-0.5 transition-all">
                Uložit do databáze
            </button>
        </div>
    </form>
</div>

<script>
    const fileInput = document.getElementById('images');
    const fileTitle = document.getElementById('file-title');
    const fileInfo = document.getElementById('file-info');
    fileInput.addEventListener('change', function(event) {
        const files = event.target.files;
        if (files.length === 0) {
            fileTitle.textContent = 'Klikni pro výběr souborů';
            fileInfo.textContent = 'JPG, PNG, WebP';
        } else {
            fileTitle.textContent = 'Soubory připraveny';
            fileInfo.textContent = 'Vybráno celkem: ' + files.length;
        }
    });
</script>
<?php require_once '../app/views/layout/footer.php'; ?>