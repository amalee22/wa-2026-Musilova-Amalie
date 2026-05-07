<?php require_once '../app/views/layout/header.php'; ?>
<div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100">

    <div class="mb-10 text-center">
        <h2 class="text-3xl font-semibold text-slate-800 mb-2">Přidat nový recept</h2>
        <p class="text-slate-500">Vyplňte všechny detaily, aby si ostatní mohli upéct tuto dobrotu.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=recipe/store" method="post" enctype="multipart/form-data" class="space-y-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label for="title" class="block text-sm font-semibold text-slate-600 mb-2">Název receptu <span class="text-amber-500">*</span></label>
                <input type="text" id="title" name="title" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all">
            </div>

            <div>
                <label for="category" class="block text-sm font-semibold text-slate-600 mb-2">Kategorie <span class="text-amber-500">*</span></label>
                <select id="category" name="category" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-amber-500 transition-all">
                    <option value="">-- Vyberte kategorii --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="prep_time" class="block text-sm font-semibold text-slate-600 mb-2">Čas přípravy (v minutách) <span class="text-amber-500">*</span></label>
                <input type="number" id="prep_time" name="prep_time" required min="1" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-amber-500 transition-all">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-semibold text-slate-600 mb-2">Krátký popis / Úvod</label>
            <textarea name="description" id="description" rows="3" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-amber-500 transition-all resize-y"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="ingredients" class="block text-sm font-semibold text-slate-600 mb-2">Ingredience <span class="text-amber-500">*</span></label>
                <textarea name="ingredients" id="ingredients" rows="8" required placeholder="Např.:&#10;200g hladké mouky&#10;2 vejce&#10;100ml mléka" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-amber-500 transition-all resize-y"></textarea>
            </div>

            <div>
                <label for="instructions" class="block text-sm font-semibold text-slate-600 mb-2">Postup přípravy <span class="text-amber-500">*</span></label>
                <textarea name="instructions" id="instructions" rows="8" required placeholder="1. Smícháme suché suroviny...&#10;2. Přidáme vejce...&#10;3. Pečeme na 180°C." class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-amber-500 transition-all resize-y"></textarea>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-600 mb-2">Obrázky k receptu (můžete nahrát více fotek)</label>
            <div class="w-full">
                <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-100 hover:border-amber-400 transition-colors">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                        <span id="file-title" class="text-sm text-slate-500 font-semibold">Klikni pro výběr fotek</span>
                        <span id="file-info" class="text-xs text-slate-400 mt-1 text-center px-4">JPG, PNG, WebP</span>
                    </div>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                </label>
            </div>
        </div>

        <div class="pt-8 mt-8 border-t border-slate-100 flex items-center justify-between gap-4">
            <a href="<?= BASE_URL ?>/index.php" class="text-slate-400 hover:text-slate-700 font-medium px-2 py-2 transition-colors">Zrušit</a>
            <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white font-semibold px-10 py-3.5 rounded-2xl shadow-lg shadow-amber-600/30 hover:-translate-y-0.5 transition-all">
                Uložit recept
            </button>
        </div>
    </form>
</div>

<script>
    // Skript pro zobrazení počtu vybraných souborů
    const fileInput = document.getElementById('images');
    const fileTitle = document.getElementById('file-title');
    const fileInfo = document.getElementById('file-info');
    fileInput.addEventListener('change', function(event) {
        const files = event.target.files;
        if (files.length === 0) {
            fileTitle.textContent = 'Klikni pro výběr fotek';
            fileInfo.textContent = 'JPG, PNG, WebP';
        } else {
            fileTitle.textContent = 'Fotky jsou připraveny';
            fileInfo.textContent = 'Vybráno celkem: ' + files.length;
        }
    });
</script>
<?php require_once '../app/views/layout/footer.php'; ?>