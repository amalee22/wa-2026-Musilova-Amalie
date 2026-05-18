<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-4xl mx-auto bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-slate-200/50">
    
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
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-1.5">Platforma / Kód</p>
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

        <?php $images = json_decode($book['images'] ?? '[]', true); ?>
        <?php if (!empty($images)): ?>
            <div class="mb-12 pt-8 border-t border-slate-100">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-5">Galerie</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                    <?php foreach ($images as $img): ?>
                        <a href="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" target="_blank" class="flex items-center justify-center p-3 rounded-xl border border-slate-200 shadow-sm hover:shadow-md hover:border-teal-300 transition-all bg-slate-50/80 group">
                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" alt="Náhled" class="max-w-full h-auto max-h-72 object-contain rounded-md group-hover:scale-105 transition-transform duration-500">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-slate-100">
            <?php if (!empty($book['link'])): ?>
                <a href="<?= htmlspecialchars($book['link']) ?>" target="_blank" class="text-teal-600 hover:text-teal-700 font-semibold flex items-center gap-2 transition-colors">
                    Odkaz na obchod <span class="text-xl leading-none">↗</span>
                </a>
            <?php else: ?>
                <span class="text-slate-300 text-sm font-medium">Bez externího odkazu</span>
            <?php endif; ?>
            
            <?php 
                $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                $isAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $book['created_by'];
            ?>
            <?php if ($isAuthor || $isAdmin): ?>
            <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="bg-teal-600 hover:bg-teal-500 text-white px-8 py-3.5 rounded-2xl shadow-lg shadow-teal-600/30 hover:shadow-teal-600/40 hover:-translate-y-0.5 transition-all font-semibold w-full sm:w-auto text-center">
                Upravit záznam
            </a>
            <?php endif; ?>
        </div>
        
        <div class="mt-16 pt-12 border-t border-slate-100">
            <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Komentáře a diskuze
            </h3>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="bg-slate-50 p-6 rounded-2xl mb-8 border border-slate-200">
                    <form action="<?= BASE_URL ?>/index.php?url=book/addComment" method="post">
                        <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['id']) ?>">
                        <textarea name="text" rows="3" required placeholder="Co si o tomto titulu myslíte?" class="w-full bg-white border border-slate-200 rounded-xl px-5 py-4 focus:ring-2 focus:ring-teal-500 outline-none transition-all resize-none mb-4 text-slate-700"></textarea>
                        <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white px-8 py-2.5 rounded-xl font-semibold transition shadow-sm">
                            Přidat komentář
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="bg-slate-50 p-6 rounded-2xl mb-8 text-center border border-slate-200">
                    <p class="text-slate-600">Pro přidání komentáře se musíte <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-teal-600 font-bold hover:underline">přihlásit</a>.</p>
                </div>
            <?php endif; ?>

            <div class="space-y-4">
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-lg shrink-0">
                                <?= strtoupper(substr(htmlspecialchars($comment['nickname'] ?: $comment['username']), 0, 1)) ?>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="font-bold text-slate-800"><?= htmlspecialchars($comment['nickname'] ?: $comment['username']) ?></span>
                                    <span class="text-xs text-slate-400 font-medium"><?= date('d. m. Y', strtotime($comment['created_at'])) ?></span>
                                </div>
                                <p class="text-slate-600 leading-relaxed text-sm"><?= nl2br(htmlspecialchars($comment['text'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-slate-500 font-medium text-sm">Zatím tu nejsou žádné komentáře. Buďte první!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>