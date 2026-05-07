<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-6xl mx-auto space-y-10 mb-20">
    
    <div class="bg-white p-8 sm:p-12 rounded-[2rem] shadow-xl shadow-bake-brown/5 border border-bake-cream">
        <h2 class="text-3xl font-bold text-bake-brown mb-8"><i class="fas fa-user-circle mr-3 text-bake-blue"></i>Můj profil</h2>
        
        <form action="<?= BASE_URL ?>/index.php?url=auth/updateProfile" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="block text-sm font-bold text-bake-brown mb-2">Přezdívka</label>
                <input type="text" name="nickname" value="<?= htmlspecialchars($user['nickname'] ?? '') ?>" class="w-full bg-bake-cream/20 border-0 rounded-xl px-4 py-3 focus:ring-2 focus:ring-bake-blue transition-all">
            </div>
            <div>
                <label class="block text-sm font-bold text-bake-brown mb-2">E-mail (nelze měnit)</label>
                <input type="text" disabled value="<?= htmlspecialchars($user['email']) ?>" class="w-full bg-slate-100 border-0 rounded-xl px-4 py-3 text-slate-400 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-sm font-bold text-bake-brown mb-2">Křestní jméno</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" class="w-full bg-bake-cream/20 border-0 rounded-xl px-4 py-3 focus:ring-2 focus:ring-bake-blue transition-all">
            </div>
            <div>
                <label class="block text-sm font-bold text-bake-brown mb-2">Příjmení</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" class="w-full bg-bake-cream/20 border-0 rounded-xl px-4 py-3 focus:ring-2 focus:ring-bake-blue transition-all">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-bake-brown mb-2">Bio / O mně</label>
                <textarea name="bio" rows="3" class="w-full bg-bake-cream/20 border-0 rounded-xl px-4 py-3 focus:ring-2 focus:ring-bake-blue transition-all resize-none" placeholder="Napište něco o sobě..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            </div>
            <div class="md:col-span-2 pt-4">
                <button type="submit" class="bg-bake-brown text-bake-cream px-8 py-3 rounded-xl font-bold hover:bg-opacity-90 transition shadow-md">Uložit změny</button>
            </div>
        </form>
    </div>

    <div>
        <h3 class="text-2xl font-bold text-bake-brown mb-6 px-4">Moje vydané recepty (<?= count($myRecipes) ?>)</h3>
        <?php if (!empty($myRecipes)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($myRecipes as $recipe): ?>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                        <h4 class="font-bold text-bake-brown truncate mb-2"><?= htmlspecialchars($recipe['title']) ?></h4>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-bake-blue"><?= htmlspecialchars($recipe['category_name']) ?></span>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>" class="font-bold hover:underline">Zobrazit</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-slate-500 italic px-4 text-center py-10 bg-white rounded-2xl border border-dashed">Zatím jste nevydali žádný recept.</p>
        <?php endif; ?>
    </div>

    <div>
        <h3 class="text-2xl font-bold text-bake-brown mb-6 px-4">Moje srdíčka ❤️ (<?= count($likedRecipes) ?>)</h3>
        <?php if (!empty($likedRecipes)): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($likedRecipes as $recipe): ?>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition">
                        <h4 class="font-bold text-bake-brown truncate mb-2"><?= htmlspecialchars($recipe['title']) ?></h4>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-rose-400"><i class="fas fa-heart mr-1"></i>Oblíbené</span>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>" class="font-bold hover:underline">K receptu</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-slate-500 italic px-4 text-center py-10 bg-white rounded-2xl border border-dashed">Zatím nemáte žádné oblíbené recepty.</p>
        <?php endif; ?>
    </div>

    <div class="mt-16 pt-10 border-t border-red-200">
        <h3 class="text-xl font-bold text-red-600 mb-2 px-4"><i class="fas fa-exclamation-triangle mr-2"></i> Nebezpečná zóna</h3>
        <p class="text-slate-500 mb-4 px-4 text-sm">Smazáním účtu přijdete o všechny oblíbené recepty a komentáře. Vaše vydané recepty budou také trvale odstraněny.</p>
        <div class="px-4">
            <a href="<?= BASE_URL ?>/index.php?url=auth/deleteAccount" onclick="return confirm('Opravdu chcete TRVALE smazat svůj účet? Tuto akci nelze vrátit zpět!')" class="inline-block border-2 border-red-500 text-red-600 hover:bg-red-500 hover:text-white font-bold px-6 py-2.5 rounded-xl transition">
                Trvale smazat můj účet
            </a>
        </div>
    </div>

</div>

<?php require_once '../app/views/layout/footer.php'; ?>