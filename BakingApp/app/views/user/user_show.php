<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-5xl mx-auto space-y-10">
    
    <div class="bg-white p-10 rounded-[2rem] shadow-xl shadow-bake-brown/5 border border-bake-cream flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="w-32 h-32 bg-bake-blue rounded-full flex items-center justify-center shrink-0 border-4 border-bake-cream shadow-lg">
                <span class="text-5xl font-bold text-bake-brown"><?= strtoupper(substr(htmlspecialchars($user['nickname'] ?: $user['username']), 0, 1)) ?></span>
            </div>
            <div>
                <h2 class="text-4xl font-bold text-bake-brown mb-2"><?= htmlspecialchars($user['nickname'] ?: $user['username']) ?></h2>
                <p class="text-slate-400 mb-4 font-medium"><i class="far fa-calendar-alt mr-1"></i> Peče s námi od <?= date('Y', strtotime($user['created_at'])) ?></p>
                <?php if(!empty($user['bio'])): ?>
                    <p class="text-slate-600 font-serif italic max-w-xl bg-bake-cream/30 p-4 rounded-xl border-l-4 border-bake-blue">"<?= nl2br(htmlspecialchars($user['bio'])) ?>"</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1 && $user['id'] != $_SESSION['user_id']): ?>
            <div class="shrink-0 md:pl-8 md:border-l border-slate-100">
                <a href="<?= BASE_URL ?>/index.php?url=user/delete/<?= $user['id'] ?>" onclick="return confirm('Tato akce trvale smaže uživatele a všechny jeho recepty i komentáře. Opravdu pokračovat?')" class="inline-flex items-center gap-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white border border-red-200 px-6 py-3 rounded-xl font-bold transition-colors">
                    <i class="fas fa-trash"></i> Smazat účet (Admin)
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <h3 class="text-2xl font-bold text-bake-brown mb-6 px-4"><i class="fas fa-book-open mr-2 text-bake-blue"></i> Recepty od tohoto pekaře</h3>
        <?php if (!empty($userRecipes)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($userRecipes as $recipe): ?>
                    <?php $images = json_decode($recipe['images'] ?? '[]', true); $coverImage = !empty($images) ? $images[0] : null; ?>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>" class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-lg transition-all group flex items-center gap-4">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-bake-cream shrink-0">
                            <?php if ($coverImage): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($coverImage) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-bake-blue"><i class="fas fa-cookie-bite text-xl"></i></div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-bake-blue uppercase tracking-wider block mb-1"><?= htmlspecialchars($recipe['category_name'] ?? 'Jiné') ?></span>
                            <h4 class="font-bold text-bake-brown truncate group-hover:text-bake-blue transition-colors"><?= htmlspecialchars($recipe['title']) ?></h4>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-slate-500 italic px-4 text-center py-10 bg-white rounded-2xl border border-dashed border-slate-200">Tento pekař se s námi o žádné recepty zatím nepodělil.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>