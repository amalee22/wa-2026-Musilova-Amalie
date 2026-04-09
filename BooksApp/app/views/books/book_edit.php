<?php require_once '../app/views/layout/header.php'; ?>

        <div class="max-w-4xl mx-auto bg-white p-10 sm:p-14 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100">
            
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-semibold text-slate-800 mb-2">Upravit záznam</h2>
                <p class="text-slate-500">Upravujete data pro titul: <strong class="text-slate-700"><?= htmlspecialchars($book['title']) ?></strong></p>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=book/update/<?= htmlspecialchars($book['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-8">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="sm:col-span-2">   
                        <label for="title" class="block text-sm font-semibold text-slate-600 mb-2">Název titulu <span class="text-teal-500">*</span></label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-teal-500 focus:bg-white transition-all">
                    </div>
                    
                    <div class="sm:col-span-2">   
                        <label for="author" class="block text-sm font-semibold text-slate-600 mb-2">Autor / Vývojář <span class="text-teal-500">*</span></label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-teal-500 focus:bg-white transition-all">
                    </div>
                    
                    <div>   
                        <label for="isbn" class="block text-sm font-semibold text-slate-600 mb-2">ID / Kód platformy <span class="text-teal-500">*</span></label>
                        <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn'] ?? '') ?>" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    </div>
                    
                    <div>   
                        <label for="year" class="block text-sm font-semibold text-slate-600 mb-2">Rok vydání <span class="text-teal-500">*</span></label>
                        <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year']) ?>" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    </div>
                    
                    <div>   
                        <label for="category" class="block text-sm font-semibold text-slate-600 mb-2">Kategorie</label>
                        <input type="text" id="category" name="category" value="<?= htmlspecialchars($book['category'] ?? '') ?>" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    </div>
                    
                    <div>   
                        <label for="subcategory" class="block text-sm font-semibold text-slate-600 mb-2">Subkategorie</label>
                        <input type="text" id="subcategory" name="subcategory" value="<?= htmlspecialchars($book['subcategory'] ?? '') ?>" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    </div>
                    
                    <div>   
                        <label for="price" class="block text-sm font-semibold text-slate-600 mb-2">Cena (Kč)</label>
                        <input type="number" id="price" name="price" step="0.5" value="<?= htmlspecialchars($book['price'] ?? '') ?>" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    </div>
                    
                    <div>   
                        <label for="link" class="block text-sm font-semibold text-slate-600 mb-2">Odkaz na web</label>
                        <input type="text" id="link" name="link" value="<?= htmlspecialchars($book['link'] ?? '') ?>" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-600 mb-2">Popis záznamu</label>
                    <textarea name="description" id="description" rows="5" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all resize-y"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
                </div>

                <?php 
                    // Dekódujeme uložené obrázky z DB (protože se ukládají jako JSON text)
                    $existingImages = json_decode($book['images'] ?? '[]', true); 
                ?>
                
                <?php if (!empty($existingImages)): ?>
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-2">Aktuální obrázky v databázi:</label>
                    <div class="flex flex-wrap gap-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                        <?php foreach ($existingImages as $img): ?>
                            <div class="w-24 h-24 overflow-hidden rounded-lg border border-slate-300 shadow-sm relative group">
                              
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Pokud nahrajete nové obrázky, tyto staré budou přepsány. Pokud nenahrajete žádné, staré obrázky zůstanou zachovány.</p>
                </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-2">Nahrát nové obrázky</label>
                    <div class="w-full">
                        <label for="images" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-100 hover:border-teal-400 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                <span id="file-title" class="text-sm text-slate-500 font-semibold">Klikni pro výběr nových souborů</span>
                                <span id="file-info" class="text-xs text-slate-400 mt-1 text-center px-4">JPG, PNG, WebP</span>
                            </div>
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>

                <div class="pt-8 mt-8 border-t border-slate-100 flex items-center justify-between gap-4">
                    <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>" class="text-slate-400 hover:text-slate-700 font-medium px-2 py-2 transition-colors">Zrušit změny</a>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-semibold px-10 py-3.5 rounded-2xl shadow-lg shadow-teal-600/30 hover:-translate-y-0.5 transition-all">
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
                    fileTitle.textContent = 'Klikni pro výběr nových souborů';
                    fileTitle.className = 'text-sm text-slate-500 font-semibold';
                    fileInfo.textContent = 'JPG, PNG, WebP';
                } else if (files.length === 1) {
                    fileTitle.textContent = 'Soubor připraven k nahrání';
                    fileTitle.className = 'text-sm text-teal-600 font-bold';
                    fileInfo.textContent = files[0].name;
                } else {
                    fileTitle.textContent = 'Soubory připraveny k nahrání';
                    fileTitle.className = 'text-sm text-teal-600 font-bold';
                    fileInfo.textContent = 'Vybráno celkem: ' + files.length + ' souborů';
                }
            });
        </script>

<?php require_once '../app/views/layout/footer.php'; ?>