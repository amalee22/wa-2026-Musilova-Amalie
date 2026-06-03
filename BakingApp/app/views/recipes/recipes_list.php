<?php require_once '../app/views/layout/header.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;0,900;1,400;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap');

    body, .recipe-body { font-family: 'DM Sans', sans-serif; }
    .font-display { font-family: 'Playfair Display', serif; }

    .featured-overlay {
        background: linear-gradient(
            to top,
            rgba(44, 22, 8, 0.92) 0%,
            rgba(44, 22, 8, 0.45) 45%,
            rgba(44, 22, 8, 0.10) 100%
        );
    }

    .img-zoom { transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .group:hover .img-zoom { transform: scale(1.07); }

    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (min-width: 1024px) {
        .card-featured { grid-column: span 2; }
    }

    .page-enter {
        animation: fadeUp 0.5s ease both;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Tab pills — na tmavém bake-brown pozadí ── */
    .tab-btn-active {
        background-color: rgba(255, 240, 222, 0.95);
        color: #694A47;
        box-shadow: 0 1px 10px rgba(0, 0, 0, 0.12);
    }
    .tab-btn-inactive {
        color: rgba(255, 240, 222, 0.55);
    }
    .tab-btn-inactive:hover {
        color: rgba(255, 240, 222, 0.90);
    }

    .cat-pill {
        font-size: 0.6rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        font-weight: 700;
    }
</style>

<div class="page-enter pt-10">

    <div id="tab-recipes" class="tab-content block">
        <?php if (!empty($recipes)): ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($recipes as $index => $recipe): ?>
                    <?php
                        $images     = json_decode($recipe['images'] ?? '[]', true);
                        $coverImage = !empty($images) ? $images[0] : null;
                        $isFeatured = ($index === 0 && $coverImage);
                    ?>

                    <?php if ($isFeatured): ?>
                    <div class="card-featured relative rounded-[28px] overflow-hidden shadow-2xl shadow-bake-brown/15 h-[480px] group">

                        <div class="absolute inset-0">
                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($coverImage) ?>"
                                 alt="<?= htmlspecialchars($recipe['title']) ?>"
                                 class="img-zoom w-full h-full object-cover">
                        </div>

                        <div class="featured-overlay absolute inset-0"></div>

                        <div class="absolute top-5 left-5 z-20">
                            <span class="cat-pill bg-bake-blue text-bake-brown px-3 py-1.5 rounded-full shadow-sm">
                                ★ <?= htmlspecialchars($recipe['category_name'] ?? 'Nezařazeno') ?>
                            </span>
                        </div>

                        <?php if ((isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['created_by']) || (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1)): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/edit/<?= $recipe['id'] ?>"
                               class="absolute top-5 right-5 z-20 w-9 h-9 flex items-center justify-center bg-white/15 backdrop-blur-md hover:bg-white/30 text-white rounded-xl transition"
                               title="Upravit"><i class="fas fa-pen text-xs"></i></a>
                        <?php endif; ?>

                        <div class="absolute bottom-0 inset-x-0 z-10 p-7">
                            <h3 class="font-display text-3xl md:text-4xl font-bold text-white leading-tight mb-2">
                                <?= htmlspecialchars($recipe['title']) ?>
                            </h3>
                            <p class="font-display italic text-white/65 text-sm mb-5 line-clamp-2">
                                "<?= htmlspecialchars($recipe['description']) ?>"
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-bake-blue flex items-center justify-center text-bake-brown font-bold text-sm shrink-0">
                                        <?= strtoupper(mb_substr($recipe['nickname'] ?: $recipe['username'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="text-white/90 text-xs font-semibold leading-none">
                                            <?= htmlspecialchars($recipe['nickname'] ?: $recipe['username']) ?>
                                        </p>
                                        <p class="text-white/50 text-[10px] mt-0.5 flex items-center gap-1">
                                            <i class="far fa-clock"></i> <?= htmlspecialchars($recipe['prep_time'] ?? 0) ?> min
                                        </p>
                                    </div>
                                </div>
                                <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>"
                                   class="bg-bake-cream text-bake-brown text-sm font-bold px-6 py-2.5 rounded-2xl hover:bg-white transition-colors shadow-lg">
                                    Zobrazit →
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php else: ?>
                    <div class="group bg-white rounded-[24px] overflow-hidden border border-slate-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-400 flex flex-col">

                        <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>"
                           class="block h-52 overflow-hidden relative bg-bake-cream/20 shrink-0">
                            <?php if ($coverImage): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($coverImage) ?>"
                                     alt="<?= htmlspecialchars($recipe['title']) ?>"
                                     class="img-zoom w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-cookie-bite text-6xl text-bake-brown/15"></i>
                                </div>
                            <?php endif; ?>

                            <div class="absolute bottom-3 left-3 bg-white/95 backdrop-blur text-bake-brown text-[11px] font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                                <i class="far fa-clock text-bake-blue text-[10px]"></i>
                                <?= htmlspecialchars($recipe['prep_time'] ?? 0) ?> min
                            </div>

                            <div class="absolute top-3 right-3 bg-bake-brown/80 backdrop-blur cat-pill text-bake-cream px-2.5 py-1 rounded-full">
                                <?= htmlspecialchars($recipe['category_name'] ?? 'Ostatní') ?>
                            </div>
                        </a>

                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="font-display text-xl font-bold text-bake-brown mb-1 leading-snug group-hover:text-bake-blue transition-colors line-clamp-2">
                                <?= htmlspecialchars($recipe['title']) ?>
                            </h3>

                            <p class="text-[11px] text-slate-400 mb-3">
                                od&nbsp;<a href="<?= BASE_URL ?>/index.php?url=user/show/<?= $recipe['created_by'] ?>"
                                           class="text-bake-brown/70 hover:text-bake-brown font-semibold transition-colors">
                                    <?= htmlspecialchars($recipe['nickname'] ?: $recipe['username']) ?>
                                </a>
                            </p>

                            <p class="font-display italic text-slate-400 text-sm flex-1 line-clamp-2 leading-relaxed mb-5">
                                "<?= htmlspecialchars($recipe['description']) ?>"
                            </p>

                            <div class="flex gap-2 pt-4 border-t border-slate-50 mt-auto">
                                <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>"
                                   class="flex-1 bg-bake-cream/50 hover:bg-bake-brown text-bake-brown hover:text-bake-cream text-center py-2.5 rounded-2xl font-semibold text-sm transition-all duration-300">
                                    Zobrazit recept
                                </a>
                                <?php if ((isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['created_by']) || (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1)): ?>
                                    <a href="<?= BASE_URL ?>/index.php?url=recipe/edit/<?= $recipe['id'] ?>"
                                       class="w-11 flex items-center justify-center bg-slate-50 hover:bg-slate-200 text-slate-400 hover:text-slate-600 rounded-2xl transition-all"
                                       title="Upravit"><i class="fas fa-pen text-xs"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                <?php endforeach; ?>
            </div>

            <?php if (!isset($_GET['q']) && isset($totalPages) && $totalPages > 1): ?>
                <div class="flex justify-center items-center gap-2 mt-20 mb-8">
                    <?php if ($page > 1): ?>
                        <a href="<?= BASE_URL ?>/index.php?url=recipe/index&sort=<?= $sort ?>&page=<?= $page - 1 ?>"
                           class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-bake-brown rounded-2xl hover:bg-bake-brown hover:text-bake-cream hover:border-bake-brown transition-all font-bold">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    <?php endif; ?>

                    <div class="flex gap-1.5">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/index&sort=<?= $sort ?>&page=<?= $i ?>"
                               class="w-10 h-10 flex items-center justify-center rounded-2xl font-semibold text-sm transition-all
                                      <?= ($i == $page) ? 'bg-bake-brown text-bake-cream shadow-lg shadow-bake-brown/25' : 'bg-white border border-slate-200 text-slate-400 hover:text-bake-brown hover:bg-bake-cream/40 hover:border-bake-cream' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= BASE_URL ?>/index.php?url=recipe/index&sort=<?= $sort ?>&page=<?= $page + 1 ?>"
                           class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 text-bake-brown rounded-2xl hover:bg-bake-brown hover:text-bake-cream hover:border-bake-brown transition-all font-bold">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="flex flex-col items-center justify-center py-36">
                <div class="w-24 h-24 rounded-full bg-bake-cream/40 border border-bake-cream flex items-center justify-center mb-6">
                    <i class="fas fa-cookie-bite text-4xl text-bake-brown/25"></i>
                </div>
                <p class="font-display italic text-slate-400 text-xl mb-1">
                    <?= !empty($_GET['q']) ? 'Pro tento výraz jsme nenašli žádný recept.' : 'Zatím tu žádné recepty nejsou.' ?>
                </p>
                <a href="<?= BASE_URL ?>/index.php"
                   class="text-bake-brown text-sm font-semibold hover:underline mt-4">← Zpět na katalog</a>
            </div>
        <?php endif; ?>
    </div>


    <div id="tab-tips" class="tab-content hidden">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h3 class="font-display text-3xl font-bold text-bake-brown">Pekařské tipy a triky</h3>
                <p class="text-slate-500 mt-1">Máte vlastní zlepšovák? Podělte se s komunitou!</p>
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/index.php?url=recipe/createTip" class="shrink-0 bg-bake-brown hover:bg-bake-blue text-bake-cream hover:text-bake-brown font-bold text-sm px-6 py-3 rounded-2xl transition-all shadow-md shadow-bake-brown/10 flex items-center gap-2">
                    <i class="fas fa-plus"></i> Přidat tip
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($tips)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php foreach ($tips as $i => $tip): ?>
                    <?php 
                        $bgGradient = ($i % 2 === 0) ? 'from-bake-blue/20 to-bake-cream/30 border-bake-blue/30' : 'from-bake-cream/50 to-white border-bake-cream';
                        $iconColor = ($i % 2 === 0) ? 'text-bake-blue bg-white' : 'text-bake-brown bg-bake-brown/10';
                        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                        $isTipAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $tip['created_by'];
                    ?>
                    <div class="bg-gradient-to-br <?= $bgGradient ?> border rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300">
                        
                        <?php if ($isTipAuthor || $isAdmin): ?>
                            <form action="<?= BASE_URL ?>/index.php?url=recipe/deleteTip/<?= $tip['id'] ?>" method="POST" onsubmit="return confirm('Opravdu chcete tento tip smazat?')" class="absolute top-6 right-6 z-20">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" class="text-bake-brown/40 hover:text-red-500 bg-white/50 hover:bg-white w-8 h-8 rounded-full flex items-center justify-center transition-all shadow-sm" title="Smazat tip">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        <?php endif; ?>

                        <div class="absolute -right-5 -bottom-5 text-bake-brown/5 text-[120px] transform rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none pointer-events-none">
                            <i class="<?= htmlspecialchars($tip['icon'] ?? 'fas fa-lightbulb') ?>"></i>
                        </div>
                        
                        <div class="w-14 h-14 rounded-2xl shadow-sm flex items-center justify-center text-2xl mb-1 z-10 <?= $iconColor ?>">
                            <i class="<?= htmlspecialchars($tip['icon'] ?? 'fas fa-lightbulb') ?>"></i>
                        </div>
                        
                        <div class="z-10 flex-grow flex flex-col">
                            <h3 class="font-display text-xl font-bold text-bake-brown mb-3 leading-tight"><?= htmlspecialchars($tip['title']) ?></h3>
                            <p class="text-slate-600 text-[15px] leading-relaxed mb-4 flex-grow"><?= nl2br(htmlspecialchars($tip['content'])) ?></p>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-bake-brown/40 pt-4 border-t border-bake-brown/5">
                                Tip od: <?= htmlspecialchars($tip['nickname'] ?: $tip['username']) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-[2rem] border border-dashed border-bake-brown/20 shadow-sm">
                <div class="w-20 h-20 mx-auto bg-bake-cream/30 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-lightbulb text-3xl text-bake-brown/40"></i>
                </div>
                <h4 class="font-display text-xl font-bold text-bake-brown mb-2">Zatím tu nejsou žádné tipy</h4>
                <p class="text-slate-500">Znáte nějaký dobrý pekařský trik? Buďte první, kdo ho přidá!</p>
            </div>
        <?php endif; ?>
    </div>


    <div id="tab-qa" class="tab-content hidden">

        <div class="bg-bake-brown rounded-[24px] shadow-xl shadow-bake-brown/20 mb-10 p-8 flex flex-col md:flex-row gap-6 items-center">
            <div class="text-bake-cream md:w-2/5">
                <h3 class="font-display text-3xl font-bold mb-1.5">Trápí vás něco?</h3>
                <p class="text-bake-cream/70 text-sm leading-relaxed">Zeptejte se naší komunity a zkušení pekaři vám rádi poradí.</p>
            </div>
            <div class="w-full md:w-3/5 flex gap-3">
                <input type="text"
                       placeholder="Napište svůj dotaz…"
                       class="w-full bg-white/10 border border-bake-cream/25 text-white placeholder-bake-cream/40 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-bake-blue/60 text-sm transition">
                <button class="bg-bake-blue text-bake-brown font-bold px-6 py-3 rounded-2xl hover:opacity-90 transition shadow-sm whitespace-nowrap text-sm">
                    Zeptat se
                </button>
            </div>
        </div>

        <div class="space-y-5">

            <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 cursor-pointer hover:bg-slate-50/60 transition" onclick="toggleAnswer('qa-1')">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-bake-blue/20 rounded-full flex items-center justify-center text-bake-brown font-bold text-lg shrink-0 font-display">M</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-3 mb-1">
                                <h4 class="font-display font-bold text-bake-brown text-lg leading-snug">Proč mi bábovka vždycky splaskne?</h4>
                                <span class="text-[11px] font-bold text-bake-blue bg-bake-blue/10 px-3 py-1 rounded-full whitespace-nowrap shrink-0">2 odpovědi</span>
                            </div>
                            <p class="text-slate-500 text-sm mb-2 line-clamp-2">Peču bábovku přesně podle receptu, v troubě je krásně naskočená, ale jakmile ji vytáhnu ven, okamžitě splaskne. Nevíte, čím to může být?</p>
                            <p class="text-[11px] text-slate-300 font-medium">Pekařka Míša · před 2 dny</p>
                        </div>
                    </div>
                </div>
                <div id="answer-qa-1" class="hidden bg-slate-50/60 border-t border-slate-100 p-6">
                    <div class="flex items-start gap-3 mb-6 ml-8">
                        <div class="w-9 h-9 bg-bake-brown/15 rounded-full flex items-center justify-center text-bake-brown font-bold shrink-0 font-display">K</div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex-1">
                            <p class="font-semibold text-bake-brown text-sm mb-1">Karel_Pecitel <span class="text-slate-300 font-normal ml-2 text-xs">před 1 dnem</span></p>
                            <p class="text-slate-500 text-sm leading-relaxed">Ahoj! Většinou je to teplotním šokem. Jakmile se bábovka dopeče, nevytahuj ji hned ven. Vypni troubu, lehce pootevři dvířka a nech ji tam ještě 10–15 minut pomalu stydnout.</p>
                        </div>
                    </div>
                    <div class="ml-8 flex gap-3">
                        <div class="w-9 h-9 bg-bake-blue rounded-full flex items-center justify-center text-bake-brown shrink-0 shadow-sm">
                            <i class="fas fa-reply text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <textarea rows="2" placeholder="Přidat odpověď…"
                                      class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-bake-blue/40 outline-none resize-none mb-2 text-sm transition"></textarea>
                            <button class="bg-bake-brown text-bake-cream px-6 py-2 rounded-2xl font-semibold hover:opacity-90 transition text-sm">Odpovědět</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-6 cursor-pointer hover:bg-slate-50/60 transition" onclick="toggleAnswer('qa-2')">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-bake-blue/20 rounded-full flex items-center justify-center text-bake-brown font-bold text-lg shrink-0 font-display">P</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-3 mb-1">
                                <h4 class="font-display font-bold text-bake-brown text-lg leading-snug">Lze udělat makronky z vlašských ořechů?</h4>
                                <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full border border-slate-200 whitespace-nowrap shrink-0">Bez odpovědi</span>
                            </div>
                            <p class="text-slate-500 text-sm mb-2 line-clamp-2">Mám alergii na mandle, ze kterých se dělá klasická makronková mouka. Zkoušel to někdo z vlašáků?</p>
                            <p class="text-[11px] text-slate-300 font-medium">Pepa_Novotny · před 5 hodinami</p>
                        </div>
                    </div>
                </div>
                <div id="answer-qa-2" class="hidden bg-slate-50/60 border-t border-slate-100 p-6">
                    <div class="ml-8 flex gap-3">
                        <div class="w-9 h-9 bg-bake-blue rounded-full flex items-center justify-center text-bake-brown shrink-0 shadow-sm">
                            <i class="fas fa-reply text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <textarea rows="2" placeholder="Znáte odpověď? Poraďte Pepovi…"
                                      class="w-full bg-white border border-slate-200 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-bake-blue/40 outline-none resize-none mb-2 text-sm transition"></textarea>
                            <button class="bg-bake-brown text-bake-cream px-6 py-2 rounded-2xl font-semibold hover:opacity-90 transition text-sm">Odpovědět</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div><script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('tab-btn-active');
            el.classList.add('tab-btn-inactive');
        });
        const tab = document.getElementById(tabId);
        tab.classList.remove('hidden');
        tab.classList.add('block');
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('tab-btn-inactive');
        activeBtn.classList.add('tab-btn-active');
    }

    function toggleAnswer(qaId) {
        document.getElementById('answer-' + qaId).classList.toggle('hidden');
    }
</script>

<?php require_once '../app/views/layout/footer.php'; ?>