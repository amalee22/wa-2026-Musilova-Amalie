<?php require_once '../app/views/layout/header.php'; ?>

<div class="page-enter max-w-4xl mx-auto mt-10 mb-20">
    <div class="bg-white rounded-[28px] border border-slate-100 p-10 shadow-xl shadow-bake-brown/10 text-center relative overflow-hidden">
        
        <!-- Background blob decoration -->
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-bake-cream/30 rounded-full blur-3xl z-0"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-bake-blue/10 rounded-full blur-3xl z-0"></div>

        <div class="relative z-10">
            <!-- Avatar -->
            <div class="w-28 h-28 mx-auto bg-bake-blue/20 rounded-full flex items-center justify-center mb-6 border-4 border-bake-cream shadow-sm">
                <span class="font-display text-5xl font-bold text-bake-brown">
                    <?= strtoupper(mb_substr($user['nickname'] ?: $user['username'], 0, 1)) ?>
                </span>
            </div>
            
            <!-- Uživatel info -->
            <h2 class="font-display text-4xl font-black text-bake-brown mb-2">
                <?= htmlspecialchars($user['nickname'] ?: $user['username']) ?>
            </h2>
            
            <?php if (!empty($user['first_name']) || !empty($user['last_name'])): ?>
                <p class="text-slate-400 font-medium text-lg mb-6">
                    <?= htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])) ?>
                </p>
            <?php else: ?>
                <div class="mb-6"></div>
            <?php endif; ?>

            <?php if (!empty($user['bio'])): ?>
                <div class="max-w-2xl mx-auto bg-slate-50/80 rounded-2xl p-6 border border-slate-100 mb-8">
                    <p class="text-slate-600 italic font-serif text-lg leading-relaxed">
                        "<?= nl2br(htmlspecialchars($user['bio'])) ?>"
                    </p>
                </div>
            <?php endif; ?>

            <div class="flex items-center justify-center gap-4 pt-6 border-t border-slate-100">
                <p class="text-sm text-slate-400">
                    <i class="fas fa-calendar-alt mr-2 text-bake-blue"></i>
                    Pekařem od: <span class="font-semibold text-slate-600"><?= date('d. m. Y', strtotime($user['created_at'])) ?></span>
                </p>
            </div>
            
        </div>
    </div>
    
<div class="mt-16 mb-8">
        <h3 class="font-display text-3xl font-bold text-bake-brown mb-8 text-center">
            Recepty od <?= htmlspecialchars($user['nickname'] ?: $user['username']) ?>
        </h3>

        <?php if (!empty($userRecipes)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($userRecipes as $recipe): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>" class="block group">
                        <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-bake-brown/10 transition-all duration-300 h-full flex flex-col">
                            
                            <?php 
    // Rozkódování JSONu s obrázky a výběr prvního z nich
    $images = json_decode($recipe['images'] ?? '[]', true);
    $coverImage = !empty($images) ? $images[0] : null;
?>
<div class="h-48 bg-bake-cream/40 overflow-hidden relative flex items-center justify-center group-hover:bg-bake-cream/60 transition-colors shrink-0">
    <?php if ($coverImage): ?>
        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($coverImage) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    <?php else: ?>
        <i class="fas fa-cookie-bite text-4xl text-bake-brown/30 group-hover:text-bake-brown/50 transition-colors"></i>
    <?php endif; ?>
</div>
                            
                            <div class="p-6 flex-grow flex flex-col">
                                <h4 class="font-display text-xl font-bold text-bake-brown mb-2 group-hover:text-bake-blue transition-colors">
                                    <?= htmlspecialchars($recipe['title']) ?>
                                </h4>
                                
                                <p class="text-slate-500 text-sm line-clamp-2 mb-4 flex-grow">
                                    <?= htmlspecialchars($recipe['description'] ?? 'Tento recept zatím nemá popis...') ?>
                                </p>
                                
                                <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-50">
                                    <span class="text-xs text-slate-400 font-medium">
                                      <i class="far fa-clock mr-1 text-bake-blue"></i> <?= htmlspecialchars($recipe['prep_time'] ?? 'N/A') ?> 
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-50/50 rounded-2xl p-8 text-center border border-slate-100 border-dashed">
                <i class="fas fa-ghost text-4xl text-slate-300 mb-3"></i>
                <p class="text-slate-500 text-lg">Tento pekař zatím nesdílel žádné své tajné recepty.</p>
            </div>
        <?php endif; ?>
    </div>


    <div class="mt-8 text-center">
        <a href="<?= BASE_URL ?>/index.php?url=user/index" class="text-bake-brown hover:text-bake-blue font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Zpět na seznam pekařů
        </a>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>