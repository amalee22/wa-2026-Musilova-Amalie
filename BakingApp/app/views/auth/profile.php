<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-4xl mx-auto space-y-5 mb-20">

    <!-- ── PROFILE FORM ─────────────────────────────────────────────── -->
    <div class="bg-white border border-bake-brown/10 rounded-[1.75rem] shadow-lg shadow-bake-brown/5 p-8 sm:p-10">

        <!-- Header -->
        <div class="flex items-center gap-5 mb-8 pb-7 border-b border-bake-brown/10">
            <div class="w-14 h-14 rounded-full bg-bake-blue/20 border border-bake-blue/40 flex items-center justify-center shrink-0">
                <span class="font-display text-xl font-bold text-bake-brown">
                    <?= strtoupper(mb_substr($user['nickname'] ?? $user['username'] ?? 'P', 0, 1)) ?>
                </span>
            </div>
            <div>
                <h2 class="font-display text-xl font-bold text-bake-brown leading-tight">Můj profil</h2>
                <p class="text-xs text-slate-400 font-light mt-0.5 tracking-wide">
                    <?= htmlspecialchars($user['email']) ?>
                </p>
            </div>
        </div>

        <form action="<?= BASE_URL ?>/index.php?url=auth/updateProfile" method="post"
              class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="flex flex-col gap-1.5">
                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Přezdívka</label>
                <input type="text" name="nickname" value="<?= htmlspecialchars($user['nickname'] ?? '') ?>"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">E-mail</label>
                <input type="text" disabled value="<?= htmlspecialchars($user['email']) ?>"
                       class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 text-slate-300 text-sm ring-1 ring-inset ring-bake-brown/10 cursor-not-allowed">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Křestní jméno</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Příjmení</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

           <div class="flex flex-col gap-1.5">
                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    Kraj <span class="text-bake-blue">*</span>
                </label>
                <select name="region" required
                        class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all">
                    <option value="">-- Vyberte kraj --</option>
                    <option value="Hlavní město Praha" <?= ($user['region'] ?? '') === 'Hlavní město Praha' ? 'selected' : '' ?>>Hlavní město Praha</option>
                    <option value="Středočeský kraj" <?= ($user['region'] ?? '') === 'Středočeský kraj' ? 'selected' : '' ?>>Středočeský kraj</option>
                    <option value="Jihočeský kraj" <?= ($user['region'] ?? '') === 'Jihočeský kraj' ? 'selected' : '' ?>>Jihočeský kraj</option>
                    <option value="Plzeňský kraj" <?= ($user['region'] ?? '') === 'Plzeňský kraj' ? 'selected' : '' ?>>Plzeňský kraj</option>
                    <option value="Karlovarský kraj" <?= ($user['region'] ?? '') === 'Karlovarský kraj' ? 'selected' : '' ?>>Karlovarský kraj</option>
                    <option value="Ústecký kraj" <?= ($user['region'] ?? '') === 'Ústecký kraj' ? 'selected' : '' ?>>Ústecký kraj</option>
                    <option value="Liberecký kraj" <?= ($user['region'] ?? '') === 'Liberecký kraj' ? 'selected' : '' ?>>Liberecký kraj</option>
                    <option value="Královéhradecký kraj" <?= ($user['region'] ?? '') === 'Královéhradecký kraj' ? 'selected' : '' ?>>Královéhradecký kraj</option>
                    <option value="Pardubický kraj" <?= ($user['region'] ?? '') === 'Pardubický kraj' ? 'selected' : '' ?>>Pardubický kraj</option>
                    <option value="Kraj Vysočina" <?= ($user['region'] ?? '') === 'Kraj Vysočina' ? 'selected' : '' ?>>Kraj Vysočina</option>
                    <option value="Jihomoravský kraj" <?= ($user['region'] ?? '') === 'Jihomoravský kraj' ? 'selected' : '' ?>>Jihomoravský kraj</option>
                    <option value="Olomoucký kraj" <?= ($user['region'] ?? '') === 'Olomoucký kraj' ? 'selected' : '' ?>>Olomoucký kraj</option>
                    <option value="Zlínský kraj" <?= ($user['region'] ?? '') === 'Zlínský kraj' ? 'selected' : '' ?>>Zlínský kraj</option>
                    <option value="Moravskoslezský kraj" <?= ($user['region'] ?? '') === 'Moravskoslezský kraj' ? 'selected' : '' ?>>Moravskoslezský kraj</option>
                </select>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    Město
                </label>
                <input type="text" name="city" placeholder="Např. Liberec" value="<?= htmlspecialchars($user['city'] ?? '') ?>"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div class="md:col-span-2 flex gap-3 pt-2">
                <button type="submit"
                        class="bg-bake-brown text-bake-cream px-7 py-2.5 rounded-xl text-sm font-medium tracking-wide shadow-md shadow-bake-brown/20 hover:bg-opacity-90 hover:-translate-y-0.5 transition-all">
                    Uložit změny
                </button>
                <a href="<?= BASE_URL ?>/index.php"
                   class="border border-bake-brown/20 text-slate-400 px-7 py-2.5 rounded-xl text-sm font-medium hover:bg-bake-cream/50 transition-all">
                    Zrušit
                </a>
            </div>
        </form>
    </div>

    <!-- ── MY RECIPES ────────────────────────────────────────────────── -->
    <div class="bg-white border border-bake-brown/10 rounded-[1.75rem] shadow-lg shadow-bake-brown/5 p-8 sm:p-10">

        <div class="flex items-center justify-between mb-6">
            <h3 class="font-display text-xl font-bold text-bake-brown">Moje recepty</h3>
            <span class="text-xs bg-bake-blue/20 text-bake-blue font-medium px-3 py-1 rounded-full">
                <?= count($myRecipes) ?>
            </span>
        </div>

        <?php if (!empty($myRecipes)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($myRecipes as $recipe): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>"
                       class="group bg-bake-cream/40 border border-bake-brown/10 rounded-2xl p-4 hover:border-bake-blue/40 hover:bg-white transition-all">
                        <p class="text-[11px] font-medium text-bake-blue uppercase tracking-[0.05em] mb-1.5">
                            <?= htmlspecialchars($recipe['category_name']) ?>
                        </p>
                        <p class="font-medium text-bake-brown text-sm truncate mb-3">
                            <?= htmlspecialchars($recipe['title']) ?>
                        </p>
                        <span class="text-xs text-slate-400 group-hover:text-bake-brown transition-colors">
                            <i class="fas fa-arrow-right text-[10px] mr-1.5"></i>Zobrazit
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="font-display italic text-slate-300 text-center py-10 text-lg">
                Zatím jste nevydali žádný recept.
            </p>
        <?php endif; ?>
    </div>

    <!-- ── LIKED RECIPES ─────────────────────────────────────────────── -->
    <div class="bg-white border border-bake-brown/10 rounded-[1.75rem] shadow-lg shadow-bake-brown/5 p-8 sm:p-10">

        <div class="flex items-center justify-between mb-6">
            <h3 class="font-display text-xl font-bold text-bake-brown">
                Moje srdíčka <span class="font-display italic font-normal">❤</span>
            </h3>
            <span class="text-xs bg-rose-50 text-rose-400 font-medium px-3 py-1 rounded-full">
                <?= count($likedRecipes) ?>
            </span>
        </div>

        <?php if (!empty($likedRecipes)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($likedRecipes as $recipe): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>"
                       class="group bg-bake-cream/40 border border-bake-brown/10 rounded-2xl p-4 hover:border-rose-200 hover:bg-white transition-all">
                        <p class="text-[11px] font-medium text-rose-400 uppercase tracking-[0.05em] mb-1.5">
                            Oblíbené
                        </p>
                        <p class="font-medium text-bake-brown text-sm truncate mb-3">
                            <?= htmlspecialchars($recipe['title']) ?>
                        </p>
                        <span class="text-xs text-slate-400 group-hover:text-bake-brown transition-colors">
                            <i class="fas fa-arrow-right text-[10px] mr-1.5"></i>K receptu
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="font-display italic text-slate-300 text-center py-10 text-lg">
                Zatím nemáte žádné oblíbené recepty.
            </p>
        <?php endif; ?>
    </div>

</div>

<?php require_once '../app/views/layout/footer.php'; ?>