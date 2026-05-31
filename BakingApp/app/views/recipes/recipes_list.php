<?php require_once '../app/views/layout/header.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;0,900;1,400;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap');

    body, .recipe-body { font-family: 'DM Sans', sans-serif; }

    .font-display { font-family: 'Playfair Display', serif; }

    /* Overlay gradient on featured card */
    .featured-overlay {
        background: linear-gradient(
            to top,
            rgba(44, 22, 8, 0.92) 0%,
            rgba(44, 22, 8, 0.45) 45%,
            rgba(44, 22, 8, 0.10) 100%
        );
    }

    /* Image zoom on hover */
    .img-zoom { transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
    .group:hover .img-zoom { transform: scale(1.07); }

    /* Scrollbar hide */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* Line clamp */
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

    /* Featured card spans 2 cols on lg+ */
    @media (min-width: 1024px) {
        .card-featured { grid-column: span 2; }
    }

    /* Page fade-in */
    .page-enter {
        animation: fadeUp 0.5s ease both;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Active tab pill */
    .tab-btn-active {
        background-color: white;
        color: var(--color-bake-brown, #6B3F1E);
        box-shadow: 0 1px 8px rgba(0,0,0,0.08);
    }
    .tab-btn-inactive {
        color: #94a3b8;
    }
    .tab-btn-inactive:hover {
        color: var(--color-bake-brown, #6B3F1E);
    }

    /* Grain overlay for the sticky nav */
    .nav-glass {
        background: rgba(255,255,255,0.82);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    /* Category pill */
    .cat-pill {
        font-size: 0.6rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        font-weight: 700;
    }
</style>

<!-- ═══════════════════════════════════════════════════
     PAGE WRAPPER
════════════════════════════════════════════════════ -->
<div class="page-enter">

    <!-- ─── HERO HEADER ────────────────────────────────── -->
    

    <!-- ─── STICKY NAV ──────────────────────────────────── -->
    <div class="nav-glass border-y border-slate-100/80 py-4 mb-12 flex items-center justify-between gap-4 sticky top-[84px] z-30 w-full px-6">
    

        <!-- Tab pills -->
        <nav class="flex items-center gap-1 bg-slate-100/70 p-1 rounded-2xl border border-slate-100">
            <button id="btn-tab-recipes"
                    onclick="switchTab('tab-recipes')"
                    class="tab-btn tab-btn-active flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200">
                <i class="fas fa-book-open text-[13px]"></i>
                <span>Katalog</span>
            </button>
            <button id="btn-tab-tips"
                    onclick="switchTab('tab-tips')"
                    class="tab-btn tab-btn-inactive flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200">
                <i class="fas fa-magic text-[13px]"></i>
                <span>Tipy &amp; Triky</span>
            </button>
            <button id="btn-tab-qa"
                    onclick="switchTab('tab-qa')"
                    class="tab-btn tab-btn-inactive flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200">
                <i class="far fa-comments text-[13px]"></i>
                <span>Komunita</span>
            </button>
        </nav>

        <!-- Search & sort bar -->
        <form method="GET" action="<?= BASE_URL ?>/index.php"
              class="w-full xl:w-auto flex items-center">
            <input type="hidden" name="url" value="recipe/index">
            <div class="flex items-center w-full xl:w-[400px] bg-white rounded-2xl border border-slate-150 shadow-sm px-1.5 py-1 gap-1.5 focus-within:ring-2 focus-within:ring-bake-brown/15 focus-within:border-bake-brown/25 transition-all">

                <!-- Sort selector -->
                <select name="sort" onchange="this.form.submit()"
                        class="bg-bake-cream/60 border-none text-[11px] font-bold text-bake-brown pl-3 pr-2 py-2 focus:ring-0 outline-none cursor-pointer uppercase tracking-wider appearance-none rounded-xl shrink-0">
                    <option value="latest"  <?= (isset($_GET['sort']) && $_GET['sort'] == 'latest')  ? 'selected' : '' ?>>Nejnovější</option>
                    <option value="oldest"  <?= (isset($_GET['sort']) && $_GET['sort'] == 'oldest')  ? 'selected' : '' ?>>Nejstarší</option>
                    <option value="time"    <?= (isset($_GET['sort']) && $_GET['sort'] == 'time')    ? 'selected' : '' ?>>Rychlovky</option>
                </select>

                <div class="w-px h-4 bg-slate-150 mx-0.5 shrink-0"></div>

                <input type="text" name="q"
                       value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                       placeholder="Hledat recept…"
                       class="bg-transparent border-none px-3 py-2 text-sm focus:ring-0 outline-none w-full text-slate-700 font-medium placeholder-slate-300">

                <button type="submit"
                        class="bg-bake-brown text-bake-cream w-9 h-9 rounded-xl flex items-center justify-center hover:opacity-80 transition shrink-0">
                    <i class="fas fa-search text-xs"></i>
                </button>
            </div>
        </form>
    </div>


    <!-- ═══════════════════════════════════════════════════
         TAB: RECIPES
    ════════════════════════════════════════════════════ -->
    <div id="tab-recipes" class="tab-content block">
        <?php if (!empty($recipes)): ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($recipes as $index => $recipe): ?>
                    <?php
                        $images     = json_decode($recipe['images'] ?? '[]', true);
                        $coverImage = !empty($images) ? $images[0] : null;
                        $isFeatured = ($index === 0 && $coverImage); // First card with image → featured
                    ?>

                    <?php if ($isFeatured): ?>
                    <!-- ── FEATURED / HERO CARD ───────────────────── -->
                    <div class="card-featured relative rounded-[28px] overflow-hidden shadow-2xl shadow-bake-brown/15 h-[480px] group">

                        <!-- Full-bleed image -->
                        <div class="absolute inset-0">
                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($coverImage) ?>"
                                 alt="<?= htmlspecialchars($recipe['title']) ?>"
                                 class="img-zoom w-full h-full object-cover">
                        </div>

                        <!-- Gradient overlay -->
                        <div class="featured-overlay absolute inset-0"></div>

                        <!-- Floating category pill -->
                        <div class="absolute top-5 left-5 z-20">
                            <span class="cat-pill bg-bake-blue text-bake-brown px-3 py-1.5 rounded-full shadow-sm">
                                ★ <?= htmlspecialchars($recipe['category_name'] ?? 'Nezařazeno') ?>
                            </span>
                        </div>

                        <!-- Edit button -->
                        <?php if ((isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['created_by']) || (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1)): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/edit/<?= $recipe['id'] ?>"
                               class="absolute top-5 right-5 z-20 w-9 h-9 flex items-center justify-center bg-white/15 backdrop-blur-md hover:bg-white/30 text-white rounded-xl transition"
                               title="Upravit"><i class="fas fa-pen text-xs"></i></a>
                        <?php endif; ?>

                        <!-- Content -->
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
                    <!-- ── REGULAR CARD ───────────────────────────── -->
                    <div class="group bg-white rounded-[24px] overflow-hidden border border-slate-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-400 flex flex-col">

                        <!-- Image -->
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

                            <!-- Time badge (bottom-left) -->
                            <div class="absolute bottom-3 left-3 bg-white/95 backdrop-blur text-bake-brown text-[11px] font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                                <i class="far fa-clock text-bake-blue text-[10px]"></i>
                                <?= htmlspecialchars($recipe['prep_time'] ?? 0) ?> min
                            </div>

                            <!-- Category badge (top-right) -->
                            <div class="absolute top-3 right-3 bg-bake-brown/80 backdrop-blur cat-pill text-bake-cream px-2.5 py-1 rounded-full">
                                <?= htmlspecialchars($recipe['category_name'] ?? 'Ostatní') ?>
                            </div>
                        </a>

                        <!-- Text body -->
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

                            <!-- Action row -->
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

            <!-- ── PAGINATION ─────────────────────────────── -->
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
            <!-- Empty state -->
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


    <!-- ═══════════════════════════════════════════════════
         TAB: TIPS & TRICKS
    ════════════════════════════════════════════════════ -->
    <div id="tab-tips" class="tab-content hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Tip 1 -->
            <div class="bg-gradient-to-br from-bake-blue/20 to-bake-cream/30 border border-bake-blue/30 rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-5 -bottom-5 text-bake-blue/10 text-[120px] transform -rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none"><i class="fas fa-temperature-low"></i></div>
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-2xl mb-1 z-10"><i class="fas fa-thermometer-half"></i></div>
                <div class="z-10">
                    <span class="cat-pill text-bake-blue block mb-2">Zlaté pravidlo</span>
                    <h3 class="font-display text-2xl font-bold text-bake-brown mb-3">Pokojová teplota surovin</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Pokud recept výslovně neříká jinak (např. u křehkého těsta), vždy dbejte na to, aby vejce, máslo i mléko měly pokojovou teplotu. Těsto se pak mnohem lépe spojí, nesrazí se a výsledek bude nadýchanější!</p>
                </div>
            </div>

            <!-- Tip 2 -->
            <div class="bg-gradient-to-br from-bake-cream/50 to-white border border-bake-cream rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-5 -bottom-5 text-bake-brown/5 text-[120px] transform rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none"><i class="fas fa-balance-scale"></i></div>
                <div class="w-14 h-14 bg-bake-brown/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-brown text-2xl mb-1 z-10"><i class="fas fa-weight"></i></div>
                <div class="z-10">
                    <span class="cat-pill text-bake-brown/50 block mb-2">Přesnost nade vše</span>
                    <h3 class="font-display text-2xl font-bold text-bake-brown mb-3">Lžíce není vždy lžíce</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Pečení je chemie. Pokud nemáte doma standardizované odměrky, raději si suroviny vždy važte na gramy. Jedna lžíce mouky může u někoho znamenat 10g a u jiného 25g.</p>
                </div>
            </div>

            <!-- Tip 3 -->
            <div class="bg-gradient-to-br from-bake-cream/50 to-white border border-bake-cream rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-5 -bottom-5 text-bake-brown/5 text-[120px] transform rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none"><i class="fas fa-door-closed"></i></div>
                <div class="w-14 h-14 bg-bake-brown/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-brown text-2xl mb-1 z-10"><i class="fas fa-fire-alt"></i></div>
                <div class="z-10">
                    <span class="cat-pill text-bake-brown/50 block mb-2">Častá chyba</span>
                    <h3 class="font-display text-2xl font-bold text-bake-brown mb-3">Zavřete tu troubu!</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Otevírání trouby během prvních 2/3 pečení je zaručený recept na to, jak nechat těsto spadnout. Vydržte to a sledujte svůj výtvor jen přes sklo.</p>
                </div>
            </div>

            <!-- Tip 4 -->
            <div class="bg-gradient-to-br from-bake-blue/10 to-white border border-bake-blue/20 rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-5 -bottom-5 text-bake-blue/5 text-[120px] transform rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none"><i class="fas fa-snowflake"></i></div>
                <div class="w-14 h-14 bg-bake-blue/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-2xl mb-1 z-10"><i class="fas fa-cookie"></i></div>
                <div class="z-10">
                    <span class="cat-pill text-bake-blue/60 block mb-2">Tajemství sušenek</span>
                    <h3 class="font-display text-2xl font-bold text-bake-brown mb-3">Dejte těsto k ledu</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Nechte těsto před pečením alespoň 30 minut odpočinout v lednici. Tuk ztuhne, sušenky se na plechu tolik neroztečou a jejich chuť bude díky odležení mnohem intenzivnější.</p>
                </div>
            </div>

            <!-- Tip 5 -->
            <div class="bg-gradient-to-br from-bake-cream/40 to-white border border-bake-cream rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-5 -bottom-5 text-bake-brown/5 text-[120px] transform -rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none"><i class="fas fa-utensil-spoon"></i></div>
                <div class="w-14 h-14 bg-bake-brown/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-brown text-2xl mb-1 z-10"><i class="fas fa-mortar-pestle"></i></div>
                <div class="z-10">
                    <span class="cat-pill text-bake-brown/50 block mb-2">Magická ingredience</span>
                    <h3 class="font-display text-2xl font-bold text-bake-brown mb-3">Špetka soli do sladkého</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">I do toho nejsladšího čokoládového dortu patří velká špetka soli. Sůl funguje jako zvýrazňovač chuti a posune váš dezert z „obyčejně sladkého" na „neodolatelně lahodný".</p>
                </div>
            </div>

            <!-- Tip 6 -->
            <div class="bg-gradient-to-br from-bake-blue/20 to-bake-cream/30 border border-bake-blue/30 rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-5 -bottom-5 text-bake-blue/10 text-[120px] transform rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none"><i class="fas fa-ban"></i></div>
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-2xl mb-1 z-10"><i class="fas fa-blender"></i></div>
                <div class="z-10">
                    <span class="cat-pill text-bake-blue block mb-2">Křehkost versus guma</span>
                    <h3 class="font-display text-2xl font-bold text-bake-brown mb-3">Těsto nepřešlehejte</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Míchejte jen do chvíle, než zmizí suché kousky mouky. Dlouhým mícháním se uvolní lepek a těsto bude po upečení tuhé a „gumové".</p>
                </div>
            </div>

            <!-- Tip 7 -->
            <div class="bg-gradient-to-br from-bake-cream/50 to-white border border-bake-cream rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-5 -bottom-5 text-bake-brown/5 text-[120px] transform -rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none"><i class="fas fa-search-plus"></i></div>
                <div class="w-14 h-14 bg-bake-brown/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-brown text-2xl mb-1 z-10"><i class="fas fa-check-circle"></i></div>
                <div class="z-10">
                    <span class="cat-pill text-bake-brown/50 block mb-2">Jistota je jistota</span>
                    <h3 class="font-display text-2xl font-bold text-bake-brown mb-3">Trik se špejlí</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Zda je bábovka nebo korpus hotový, poznáte tak, že doprostřed zapíchnete dřevěnou špejli. Pokud ji vytáhnete čistou bez nalepeného syrového těsta, máte hotovo!</p>
                </div>
            </div>

            <!-- Tip 8 -->
            <div class="bg-gradient-to-br from-bake-blue/10 to-white border border-bake-blue/20 rounded-[24px] p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-5 -bottom-5 text-bake-blue/5 text-[120px] transform rotate-12 group-hover:scale-110 transition-transform duration-500 leading-none"><i class="fas fa-tint"></i></div>
                <div class="w-14 h-14 bg-bake-blue/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-2xl mb-1 z-10"><i class="fas fa-lemon"></i></div>
                <div class="z-10">
                    <span class="cat-pill text-bake-blue/60 block mb-2">Přírodní chemie</span>
                    <h3 class="font-display text-2xl font-bold text-bake-brown mb-3">Zkroťte jedlou sodu citronem</h3>
                    <p class="text-slate-600 leading-relaxed text-sm">Jedlá soda potřebuje k aktivaci kyselou složku. Pokud je v receptu soda, ujistěte se, že těsto obsahuje podmáslí, jogurt, kakao, nebo přidejte pár kapek citronové šťávy.</p>
                </div>
            </div>

        </div>
    </div>


    <!-- ═══════════════════════════════════════════════════
         TAB: KOMUNITA / Q&A
    ════════════════════════════════════════════════════ -->
    <div id="tab-qa" class="tab-content hidden">

        <!-- Ask banner -->
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

        <!-- Q&A list -->
        <div class="space-y-5">

            <!-- Q1 -->
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

            <!-- Q2 -->
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
    </div><!-- /tab-qa -->

</div><!-- /page-enter -->

<script>
    function switchTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });

        // Reset all tab buttons
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('tab-btn-active');
            el.classList.add('tab-btn-inactive');
        });

        // Show selected tab
        const tab = document.getElementById(tabId);
        tab.classList.remove('hidden');
        tab.classList.add('block');

        // Activate button
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('tab-btn-inactive');
        activeBtn.classList.add('tab-btn-active');
    }

    function toggleAnswer(qaId) {
        document.getElementById('answer-' + qaId).classList.toggle('hidden');
    }
</script>

<?php require_once '../app/views/layout/footer.php'; ?>