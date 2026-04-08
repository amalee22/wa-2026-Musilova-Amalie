<!DOCTYPE html>
<html lang='cs'>
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail - <?= htmlspecialchars($book['title']) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <div class="max-w-3xl mx-auto w-full px-6 pt-12 pb-6">
        <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center gap-2 text-slate-400 hover:text-teal-600 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Zpět do katalogu
        </a>
    </div>

    <main class="flex-1 w-full max-w-3xl mx-auto px-6 pb-12">
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-slate-200/50">
            
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-10 sm:p-12 text-center">
                <span class="inline-block px-4 py-1.5 bg-teal-500/20 text-teal-300 rounded-full text-xs font-bold tracking-widest uppercase mb-6 border border-teal-500/20">
                    <?= htmlspecialchars($book['category'] ?? 'Neznámá kategorie') ?>
                </span>
                <h2 class="text-3xl sm:text-4xl font-semibold text-white mb-3 tracking-tight">
                    <?= htmlspecialchars($book['title']) ?>
                </h2>
                <p class="text-slate-300 text-lg font-light">
                    od <strong class="text-white font-medium"><?= htmlspecialchars($book['author']) ?></strong>
                </p>
            </div>

            <div class="p-10 sm:p-12">
                <div class="grid grid-cols-2 gap-y-8 gap-x-12 mb-10">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1.5">Rok vydání</p>
                        <p class="text-slate-700 font-medium text-lg"><?= htmlspecialchars($book['year']) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1.5">Platforma / ISBN</p>
                        <p class="text-slate-700 font-medium text-lg"><?= htmlspecialchars($book['isbn'] ?? 'Není uvedeno') ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1.5">Žánr / Subkat.</p>
                        <p class="text-slate-700 font-medium text-lg"><?= htmlspecialchars($book['subcategory'] ?? 'Není uvedeno') ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1.5">Cena</p>
                        <p class="text-teal-600 font-bold text-xl"><?= htmlspecialchars($book['price'] ?? '0') ?> Kč</p>
                    </div>
                </div>

                <div class="mb-12">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-3">Popis / Anotace</p>
                    <p class="text-slate-600 leading-relaxed text-lg font-serif">
                        <?= nl2br(htmlspecialchars($book['description'] ?? 'Zatím nebyl přidán žádný popis.')) ?>
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-slate-100">
                    <?php if (!empty($book['link'])): ?>
                        <a href="<?= htmlspecialchars($book['link']) ?>" target="_blank" class="text-teal-600 hover:text-teal-700 font-semibold flex items-center gap-2 transition-colors">
                            Odkaz na obchod <span class="text-xl leading-none">↗</span>
                        </a>
                    <?php else: ?>
                        <span class="text-slate-300 text-sm font-medium">Bez externího odkazu</span>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="bg-teal-600 hover:bg-teal-500 text-white px-8 py-3.5 rounded-2xl shadow-lg shadow-teal-600/30 hover:shadow-teal-600/40 hover:-translate-y-0.5 transition-all font-semibold w-full sm:w-auto text-center">
                        Upravit záznam
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>