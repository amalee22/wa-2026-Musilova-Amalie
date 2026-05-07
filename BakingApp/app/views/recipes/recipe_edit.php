<?php require_once '../app/views/layout/header.php'; ?>
<?php /** @var array $recipe */ /** @var array $categories */ ?>
<div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 rounded-[2rem] shadow-xl shadow-bake-brown/10 border border-bake-cream">

    <div class="mb-10 text-center">
        <h2 class="text-3xl font-semibold text-bake-brown mb-2">Upravit recept</h2>
        <p class="text-slate-500">Upravujete recept: <strong class="text-bake-blue"><?= htmlspecialchars($recipe['title']) ?></strong></p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=recipe/update/<?= htmlspecialchars($recipe['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-8">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                <label for="title" class="block text-sm font-semibold text-bake-brown mb-2">Název receptu <span class="text-bake-blue">*</span></label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required class="w-full bg-bake-cream/30 border-0 text-slate-900 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div>
                <label for="category" class="block text-sm font-semibold text-bake-brown mb-2">Kategorie <span class="text-bake-blue">*</span></label>
                <select id="category" name="category" required class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
                    <option value="">-- Vyberte kategorii --</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php $isSelected = ($recipe['category_id'] == $cat['id']) ? 'selected' : ''; ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>" <?= $isSelected ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="prep_time" class="block text-sm font-semibold text-bake-brown mb-2">Čas přípravy (v minutách) <span class="text-bake-blue">*</span></label>
                <input type="number" id="prep_time" name="prep_time" value="<?= htmlspecialchars($recipe['prep_time']) ?>" required min="1" class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-semibold text-bake-brown mb-2">Krátký popis / Úvod</label>
            <textarea name="description" id="description" rows="3" class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all resize-y"><?= htmlspecialchars($recipe['description'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="ingredients" class="block text-sm font-semibold text-bake-brown mb-2">Ingredience <span class="text-bake-blue">*</span></label>
                <textarea name="ingredients" id="ingredients" rows="8" required class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all resize-y"><?= htmlspecialchars($recipe['ingredients'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="instructions" class="block text-sm font-semibold text-bake-brown mb-2">Postup přípravy <span class="text-bake-blue">*</span></label>
                <textarea name="instructions" id="instructions" rows="8" required class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all resize-y"><?= htmlspecialchars($recipe['instructions'] ?? '') ?></textarea>
            </div>
        </div>

        <?php $existingImages = json_decode($recipe['images'] ?? '[]', true); ?>
        <?php if (!empty($existingImages)): ?>
        <div>
            <label class="block text-sm font-semibold text-bake-brown mb-2">Aktuální obrázky v databázi:</label>
            <div class="flex flex-wrap gap-4 p-4 bg-bake-cream/20 border border-bake-cream rounded-xl">
                <?php foreach ($existingImages as $img): ?>
                    <div class="w-24 h-24 overflow-hidden rounded-lg border border-bake-cream shadow-sm relative group">
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" alt="Obrázek" class="w-full h-full object-cover">
                    </div>
                <?php endforeach; ?>
            </div>
            <p class="text-xs text-slate-500 mt-2">Pokud nahrajete nové obrázky, tyto staré budou přepsány.</p>
        </div>
        <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold text-bake-brown mb-2">Nahrát nové obrázky</label>
            <div class="w-full">
                <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-bake-cream border-dashed rounded-xl cursor-pointer bg-bake-cream/30 hover:bg-bake-cream hover:border-bake-blue transition-colors">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <i class="fas fa-cloud-upload-alt text-3xl text-bake-blue/50 mb-3"></i>
                        <span id="file-title" class="text-sm text-slate-500 font-semibold">Klikni pro výběr nových fotek</span>
                        <span id="file-info" class="text-xs text-slate-400 mt-1 text-center px-4">JPG, PNG, WebP</span>
                    </div>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                </label>
            </div>
        </div>

        <div class="pt-8 mt-8 border-t border-bake-cream/50 flex items-center justify-between gap-4">
            <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>" class="text-slate-400 hover:text-bake-brown font-medium px-2 py-2 transition-colors">Zrušit změny</a>
            <button type="submit" class="bg-bake-brown hover:bg-opacity-90 text-bake-cream font-semibold px-10 py-3.5 rounded-2xl shadow-lg shadow-bake-brown/30 hover:-translate-y-0.5 transition-all">
                Uložit změny
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
            fileTitle.textContent = 'Klikni pro výběr nových fotek';
            fileInfo.textContent = 'JPG, PNG, WebP';
        } else {
            fileTitle.textContent = 'Fotky připraveny k nahrání';
            fileInfo.textContent = 'Vybráno celkem: ' + files.length;
        }
    });
</script>
<?php require_once '../app/views/layout/footer.php'; ?>