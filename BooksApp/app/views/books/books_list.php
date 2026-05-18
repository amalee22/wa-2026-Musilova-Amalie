<?php require_once '../app/views/layout/header.php'; ?>

        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-bold text-slate-800">Katalog titulů</h2>
                <p class="text-slate-500 mt-1">Procházejte tituly přidané komunitou nebo přispějte vlastními objevy.</p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?url=book/create" class="md:hidden bg-teal-600 text-white px-4 py-2 rounded-lg font-medium shadow-sm">Přidat</a>
        </header>

        <?php if (!empty($books)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($books as $book): ?>
                    <?php 
                        // Rozbalíme obrázky a získáme ten první (náhledový)
                        $images = json_decode($book['images'] ?? '[]', true);
                        $coverImage = !empty($images) ? $images[0] : null;
                    ?>
                    
                    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group flex flex-col h-full">
                        
                        <div class="w-full h-48 mb-5 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 relative">
                            <?php if ($coverImage): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($coverImage) ?>" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="flex flex-wrap gap-2 items-center mb-3">
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold uppercase tracking-wider shrink-0">
                                <?= htmlspecialchars($book['year']) ?>
                            </span>
                            <span class="px-3 py-1 bg-teal-50 text-teal-700 rounded-full text-xs font-semibold uppercase tracking-wider truncate max-w-[120px]" title="<?= htmlspecialchars($book['category_name'] ?? 'Nezařazeno') ?>">
                                <?= htmlspecialchars($book['category_name'] ?? 'Nezařazeno') ?>
                            </span>
                            <span class="text-teal-600 font-bold ml-auto shrink-0"><?= htmlspecialchars($book['price']) ?> Kč</span>
                        </div>

                        <h3 class="text-xl font-bold text-slate-800 mb-1 leading-tight group-hover:text-teal-600 transition-colors">
                            <?= htmlspecialchars($book['title']) ?>
                        </h3>
                        <p class="text-slate-500 text-sm mb-6 flex-1">
                            <?= htmlspecialchars($book['author']) ?>
                        </p>

                        <div class="flex gap-2 pt-4 border-t border-slate-50">
                            <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>" class="flex-1 bg-slate-50 hover:bg-teal-50 text-slate-600 hover:text-teal-700 text-center py-2 rounded-xl font-medium text-sm transition-colors">Detail</a>
                            
                            <?php 
                            // Kontrola práv na frontendové zobrazení tlačítek (Zachováno z minulého úkolu)
                            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                            $isAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $book['created_by'];
                            
                            if ($isAuthor || $isAdmin): 
                                // Výchozí barevné styly tlačítek
                                $editBtnStyle = "bg-slate-50 hover:bg-slate-200 text-slate-600 border border-transparent";
                                $deleteBtnStyle = "bg-rose-50 hover:bg-rose-500 text-rose-500 hover:text-white border border-transparent";
                                
                                // Vizuální odlišení cizích knih pro administrátora
                                if ($isAdmin && !$isAuthor) {
                                    $editBtnStyle = "bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/60";
                                    $deleteBtnStyle = "bg-purple-50 hover:bg-purple-600 text-purple-600 hover:text-white border border-purple-200/60";
                                }
                            ?>
                                <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="flex-1 text-center py-2 rounded-xl font-medium text-sm transition-colors <?= $editBtnStyle ?>">Upravit</a>
                                <a href="<?= BASE_URL ?>/index.php?url=book/delete/<?= $book['id'] ?>" onclick="return confirm('Opravdu chcete tuto knihu smazat?')" class="w-10 flex items-center justify-center rounded-xl transition-colors <?= $deleteBtnStyle ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col items-center justify-center h-64 bg-white rounded-3xl border border-dashed border-slate-300">
                <p class="text-slate-400 text-lg mb-4">Zatím tu nic není.</p>
                <a href="<?= BASE_URL ?>/index.php?url=book/create" class="text-teal-600 font-medium hover:underline">Přidejte první záznam do katalogu</a>
            </div>
        <?php endif; ?>

<?php require_once '../app/views/layout/footer.php'; ?>