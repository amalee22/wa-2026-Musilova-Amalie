<?php require_once '../app/views/layout/header.php'; ?>

<div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6">
    <div>
        <h2 class="text-3xl font-bold text-bake-brown">Pekařská komunita</h2>
        <p class="text-slate-500 mt-1">Objevte nové tváře a jejich nejlepší výtvory.</p>
    </div>
    
    <form method="GET" action="<?= BASE_URL ?>/index.php" class="w-full md:w-auto flex">
        <input type="hidden" name="url" value="user/index">
        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Hledat pekaře..." class="w-full md:w-72 bg-white border border-slate-200 rounded-l-xl px-4 py-3 focus:ring-2 focus:ring-bake-blue outline-none">
        <button type="submit" class="bg-bake-brown text-bake-cream px-5 rounded-r-xl font-bold hover:bg-opacity-90 transition"><i class="fas fa-search"></i></button>
    </form>
</div>

<?php if (!empty($users)): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach ($users as $u): ?>
            <a href="<?= BASE_URL ?>/index.php?url=user/show/<?= $u['id'] ?>" class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all text-center block group">
                <div class="w-20 h-20 mx-auto bg-bake-blue/20 rounded-full flex items-center justify-center mb-4 group-hover:bg-bake-blue/40 transition">
                    <span class="text-2xl font-bold text-bake-brown"><?= strtoupper(substr(htmlspecialchars($u['nickname'] ?: $u['username']), 0, 1)) ?></span>
                </div>
                <h3 class="text-xl font-bold text-bake-brown"><?= htmlspecialchars($u['nickname'] ?: $u['username']) ?></h3>
                <?php if($u['first_name'] || $u['last_name']): ?>
                    <p class="text-xs text-slate-400 mt-1"><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></p>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300">
        <p class="text-slate-400 text-lg mb-4">Žádný pekař nebyl nalezen.</p>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layout/footer.php'; ?>