<?php require_once '../app/views/layout/header.php'; ?>
<?php /** @var array $categories */ ?>

<div class="max-w-4xl mx-auto space-y-5 mb-20 mt-6">

    <div class="bg-white border border-bake-brown/10 rounded-[1.75rem] shadow-lg shadow-bake-brown/5 p-8 sm:p-10">
        
        <div class="flex items-center gap-5 mb-8 pb-7 border-b border-bake-brown/10">
            <div class="w-14 h-14 rounded-full bg-bake-blue/20 border border-bake-blue/40 flex items-center justify-center shrink-0">
                <i class="fas fa-cookie-bite text-xl text-bake-brown"></i>
            </div>
            <div>
                <h2 class="font-display text-2xl font-bold text-bake-brown leading-tight">Přidat nový recept</h2>
                <p class="text-xs text-slate-400 font-light mt-0.5 tracking-wide">
                    Podělte se o své pekařské tajemství s komunitou.
                </p>
            </div>
        </div>

        <form action="<?= BASE_URL ?>/index.php?url=recipe/store" method="post" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="md:col-span-2 flex flex-col gap-1.5">
                <label for="title" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Název receptu <span class="text-bake-blue">*</span></label>
                <input type="text" id="title" name="title" required placeholder="Např. Nadýchaná tvarohová bábovka"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/30 placeholder:font-light">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="category" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Kategorie <span class="text-bake-blue">*</span></label>
                <select id="category" name="category" required class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all">
                    <option value="">-- Vyberte kategorii --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="prep_time" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Čas přípravy (v min) <span class="text-bake-blue">*</span></label>
                <input type="number" id="prep_time" name="prep_time" required min="1" placeholder="45"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/30 placeholder:font-light">
            </div>

            <div class="md:col-span-2 flex flex-col gap-1.5">
                <label for="description" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Krátký popis / Úvod</label>
                <textarea name="description" id="description" rows="2" placeholder="Napište pár slov o tom, proč je tento recept skvělý..."
                          class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all resize-none placeholder:text-bake-brown/30 placeholder:font-light"></textarea>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="ingredients" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Ingredience <span class="text-bake-blue">*</span></label>
                <textarea name="ingredients" id="ingredients" rows="7" required placeholder="Např.:&#10;250 g hladké mouky&#10;4 vejce&#10;150 ml mléka"
                          class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all resize-y placeholder:text-bake-brown/30 placeholder:font-light"></textarea>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="instructions" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Postup přípravy <span class="text-bake-blue">*</span></label>
                <textarea name="instructions" id="instructions" rows="7" required placeholder="1. Nejprve oddělíme žloutky od bílků...&#10;2. Ušleháme sníh...&#10;3. Pečeme na 180°C."
                          class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all resize-y placeholder:text-bake-brown/30 placeholder:font-light"></textarea>
            </div>

            <div class="md:col-span-2 flex flex-col gap-1.5 mt-2">
                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Obrázky k receptu</label>
                <div class="w-full">
                    <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-bake-brown/20 border-dashed rounded-xl cursor-pointer bg-bake-cream/10 hover:bg-bake-cream/30 hover:border-bake-blue/50 transition-all">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-camera text-2xl text-bake-brown/30 mb-2 group-hover:text-bake-blue transition-colors"></i>
                            <span id="file-title" class="text-sm text-bake-brown font-medium">Klikni pro výběr fotek</span>
                            <span id="file-info" class="text-[10px] font-semibold text-slate-400 mt-1 uppercase tracking-wider">JPG, PNG, WebP</span>
                        </div>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>
            </div>

            <div class="md:col-span-2 flex items-center justify-between pt-6 mt-2 border-t border-bake-brown/10">
                <a href="<?= BASE_URL ?>/index.php" class="text-[13px] text-slate-400 hover:text-bake-brown font-medium px-2 py-2 transition-colors">
                    Zrušit
                </a>
                <button type="submit" class="bg-bake-brown text-bake-cream px-8 py-3 rounded-xl text-sm font-medium tracking-wide shadow-md shadow-bake-brown/20 hover:bg-opacity-90 hover:-translate-y-0.5 transition-all">
                    Uložit recept
                </button>
            </div>
            
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('images');
    const fileTitle = document.getElementById('file-title');
    const fileInfo = document.getElementById('file-info');
    
    fileInput.addEventListener('change', function(event) {
        const files = event.target.files;
        if (files.length === 0) {
            fileTitle.textContent = 'Klikni pro výběr fotek';
            fileInfo.textContent = 'JPG, PNG, WebP';
        } else {
            fileTitle.textContent = 'Fotky připraveny k nahrání';
            fileInfo.textContent = 'Vybráno celkem: ' + files.length;
        }
    });
</script>

<?php require_once '../app/views/layout/footer.php'; ?>