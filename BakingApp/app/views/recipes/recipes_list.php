<?php require_once '../app/views/layout/header.php'; ?>

<div class="text-center mt-8 mb-12">
    <h2 class="text-4xl md:text-5xl font-black text-bake-brown tracking-tight">Pečeme společně</h2>
    <p class="text-slate-500 mt-4 font-serif italic text-lg">Vaše denní dávka inspirace a komunitní pomoci.</p>
</div>

<div class="border-y border-bake-cream py-5 mb-14 flex flex-col xl:flex-row justify-between items-center gap-8 bg-bake-cream/10 sticky top-0 z-30 backdrop-blur-md">
    
    <div class="flex gap-8 sm:gap-12 overflow-x-auto w-full xl:w-auto px-4 scrollbar-hide justify-center xl:justify-start">
        <button id="btn-tab-recipes" onclick="switchTab('tab-recipes')" class="tab-btn flex flex-col items-center gap-2 text-bake-brown border-b-2 border-bake-brown pb-2 transition-all">
            <i class="fas fa-book-open text-2xl"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Katalog</span>
        </button>
        <button id="btn-tab-tips" onclick="switchTab('tab-tips')" class="tab-btn flex flex-col items-center gap-2 text-slate-400 border-b-2 border-transparent hover:text-bake-brown pb-2 transition-all">
            <i class="fas fa-magic text-2xl"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Tipy a triky</span>
        </button>
        <button id="btn-tab-qa" onclick="switchTab('tab-qa')" class="tab-btn flex flex-col items-center gap-2 text-slate-400 border-b-2 border-transparent hover:text-bake-brown pb-2 transition-all">
            <i class="far fa-comments text-2xl"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Komunita</span>
        </button>
    </div>

    <form method="GET" action="<?= BASE_URL ?>/index.php" class="w-full sm:w-auto flex items-center bg-white rounded-full border border-slate-200 shadow-sm p-1.5 focus-within:ring-2 focus-within:ring-bake-blue/50 transition-all mx-4 xl:mx-0">
        <input type="hidden" name="url" value="recipe/index">
        
        <select name="sort" onchange="this.form.submit()" class="bg-transparent border-none text-xs font-bold text-slate-500 pl-4 pr-2 py-2 focus:ring-0 outline-none cursor-pointer uppercase tracking-wider appearance-none">
            <option value="latest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'latest') ? 'selected' : '' ?>>Nejnovější</option>
            <option value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : '' ?>>Nejstarší</option>
            <option value="time" <?= (isset($_GET['sort']) && $_GET['sort'] == 'time') ? 'selected' : '' ?>>Rychlovky</option>
        </select>
        
        <div class="w-px h-5 bg-slate-200 mx-2"></div>
        
        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Hledat recept..." class="bg-transparent border-none px-3 py-2 text-sm focus:ring-0 outline-none w-full sm:w-56 text-slate-700 font-medium placeholder-slate-400">
        
        <button type="submit" class="bg-bake-brown text-bake-cream w-9 h-9 rounded-full flex items-center justify-center hover:scale-105 transition shrink-0">
            <i class="fas fa-search text-sm"></i>
        </button>
    </form>
</div>

<div id="tab-recipes" class="tab-content block transition-all duration-500">
    <?php if (!empty($recipes)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach ($recipes as $recipe): ?>
                <?php 
                    $images = json_decode($recipe['images'] ?? '[]', true);
                    $coverImage = !empty($images) ? $images[0] : null;
                ?>
                
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group flex flex-col h-full">
                    <div class="w-full h-56 mb-5 rounded-2xl overflow-hidden bg-bake-cream/30 relative">
                        <?php if ($coverImage): ?>
                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($coverImage) ?>" alt="Cover" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-bake-blue/50">
                                <i class="fas fa-cookie-bite text-5xl"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur text-bake-brown text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                            <i class="far fa-clock text-bake-blue"></i> <?= htmlspecialchars($recipe['prep_time'] ?? 0) ?> min
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="text-[10px] font-bold text-bake-blue uppercase tracking-widest"><?= htmlspecialchars($recipe['category_name'] ?? 'Nezařazeno') ?></span>
                    </div>

                    <h3 class="text-xl font-black text-bake-brown mb-2 leading-tight group-hover:text-bake-blue transition-colors">
                        <?= htmlspecialchars($recipe['title']) ?>
                    </h3>
                    
                    <p class="text-xs text-slate-400 mb-4 font-medium">
                        od <a href="<?= BASE_URL ?>/index.php?url=user/show/<?= $recipe['created_by'] ?>" class="text-bake-brown hover:text-bake-blue transition-colors"><?= htmlspecialchars($recipe['nickname'] ?: $recipe['username']) ?></a>
                    </p>

                    <p class="text-slate-500 text-sm mb-6 flex-1 line-clamp-2 font-serif italic">"<?= htmlspecialchars($recipe['description']) ?>"</p>

                    <div class="flex gap-3 pt-4 border-t border-slate-50 mt-auto">
                        <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>" class="flex-1 bg-bake-cream/40 hover:bg-bake-brown text-bake-brown hover:text-bake-cream text-center py-2.5 rounded-xl font-bold text-sm transition-colors">Zobrazit recept</a>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['created_by']): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/edit/<?= $recipe['id'] ?>" class="w-12 flex items-center justify-center bg-slate-50 hover:bg-slate-200 text-slate-500 text-center rounded-xl font-bold transition-colors" title="Upravit"><i class="fas fa-pen"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!isset($_GET['q']) && isset($totalPages) && $totalPages > 1): ?>
            <div class="flex justify-center items-center gap-2 mt-16 mb-8">
                <?php if ($page > 1): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/index&sort=<?= $sort ?>&page=<?= $page - 1 ?>" class="px-4 py-2 bg-white border border-slate-200 text-bake-brown font-bold rounded-xl hover:bg-bake-cream hover:border-bake-cream transition"><i class="fas fa-chevron-left text-xs"></i></a>
                <?php endif; ?>
                
                <div class="flex gap-2">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?= BASE_URL ?>/index.php?url=recipe/index&sort=<?= $sort ?>&page=<?= $i ?>" class="w-10 h-10 flex items-center justify-center rounded-xl font-bold transition <?= ($i == $page) ? 'bg-bake-brown text-bake-cream shadow-md' : 'bg-white border border-slate-200 text-slate-500 hover:text-bake-brown hover:bg-bake-cream hover:border-bake-cream' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>

                <?php if ($page < $totalPages): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/index&sort=<?= $sort ?>&page=<?= $page + 1 ?>" class="px-4 py-2 bg-white border border-slate-200 text-bake-brown font-bold rounded-xl hover:bg-bake-cream hover:border-bake-cream transition"><i class="fas fa-chevron-right text-xs"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="flex flex-col items-center justify-center h-64 bg-white rounded-3xl border border-dashed border-slate-300">
            <p class="text-slate-400 text-lg mb-4 font-serif italic">
                <?= !empty($_GET['q']) ? 'Pro tento výraz jsme nenašli žádný recept.' : 'Zatím tu žádné recepty nejsou.' ?>
            </p>
            <a href="<?= BASE_URL ?>/index.php" class="text-bake-blue font-bold hover:underline">Zpět na katalog</a>
        </div>
    <?php endif; ?>
</div>

<div id="tab-tips" class="tab-content hidden transition-all duration-500">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-gradient-to-br from-bake-blue/20 to-bake-cream/30 border border-bake-blue/30 rounded-3xl p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-bake-blue/10 text-9xl transform -rotate-12 group-hover:scale-110 transition-transform"><i class="fas fa-temperature-low"></i></div>
            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-3xl mb-2 z-10"><i class="fas fa-thermometer-half"></i></div>
            <div class="z-10">
                <span class="text-xs font-bold text-bake-blue uppercase tracking-widest mb-2 block">Zlaté pravidlo</span>
                <h3 class="text-2xl font-bold text-bake-brown mb-3">Pokojová teplota surovin</h3>
                <p class="text-slate-600 leading-relaxed">Pokud recept výslovně neříká jinak (např. u křehkého těsta), vždy dbejte na to, aby vejce, máslo i mléko měly pokojovou teplotu. Těsto se pak mnohem lépe spojí, nesrazí se a výsledek bude nadýchanější!</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-bake-cream/50 to-white border border-bake-cream rounded-3xl p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-bake-brown/5 text-9xl transform rotate-12 group-hover:scale-110 transition-transform"><i class="fas fa-balance-scale"></i></div>
            <div class="w-16 h-16 bg-bake-brown/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-brown text-3xl mb-2 z-10"><i class="fas fa-weight"></i></div>
            <div class="z-10">
                <span class="text-xs font-bold text-bake-brown/50 uppercase tracking-widest mb-2 block">Přesnost nade vše</span>
                <h3 class="text-2xl font-bold text-bake-brown mb-3">Lžíce není vždy lžíce</h3>
                <p class="text-slate-600 leading-relaxed">Pečení je chemie. Pokud nemáte doma standardizované odměrky (cups/spoons), raději si suroviny vždy važte na gramy. Jedna lžíce mouky může u někoho znamenat 10g a u jiného 25g (když je pořádně navršená).</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-bake-cream/50 to-white border border-bake-cream rounded-3xl p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-bake-brown/5 text-9xl transform rotate-12 group-hover:scale-110 transition-transform"><i class="fas fa-door-closed"></i></div>
            <div class="w-16 h-16 bg-bake-brown/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-brown text-3xl mb-2 z-10"><i class="fas fa-fire-alt"></i></div>
            <div class="z-10">
                <span class="text-xs font-bold text-bake-brown/50 uppercase tracking-widest mb-2 block">Častá chyba</span>
                <h3 class="text-2xl font-bold text-bake-brown mb-3">Zavřete tu troubu!</h3>
                <p class="text-slate-600 leading-relaxed">Otevírání trouby během prvních 2/3 pečení (zvlášť u piškotových těst, odpalovaného těsta a soufflé) je zaručený recept na to, jak nechat těsto spadnout. Vydržte to a sledujte svůj výtvor jen přes sklo.</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-bake-blue/10 to-white border border-bake-blue/20 rounded-3xl p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-bake-blue/5 text-9xl transform rotate-12 group-hover:scale-110 transition-transform"><i class="fas fa-snowflake"></i></div>
            <div class="w-16 h-16 bg-bake-blue/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-3xl mb-2 z-10"><i class="fas fa-cookie"></i></div>
            <div class="z-10">
                <span class="text-xs font-bold text-bake-blue/60 uppercase tracking-widest mb-2 block">Tajemství sušenek</span>
                <h3 class="text-2xl font-bold text-bake-brown mb-3">Dejte těsto k ledu</h3>
                <p class="text-slate-600 leading-relaxed">Pokud pečete cookies, nechte těsto před pečením alespoň 30 minut (ideálně přes noc) odpočinout v lednici. Tuk ztuhne, sušenky se na plechu tolik neroztečou a jejich chuť bude díky odležení mnohem intenzivnější.</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-bake-cream/40 to-white border border-bake-cream rounded-3xl p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-bake-brown/5 text-9xl transform -rotate-12 group-hover:scale-110 transition-transform"><i class="fas fa-utensil-spoon"></i></div>
            <div class="w-16 h-16 bg-bake-brown/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-brown text-3xl mb-2 z-10"><i class="fas fa-mortar-pestle"></i></div>
            <div class="z-10">
                <span class="text-xs font-bold text-bake-brown/50 uppercase tracking-widest mb-2 block">Magická ingredience</span>
                <h3 class="text-2xl font-bold text-bake-brown mb-3">Špetka soli do sladkého</h3>
                <p class="text-slate-600 leading-relaxed">Nikdy nezapomínejte na sůl! I do toho nejsladšího čokoládového dortu nebo karamelu patří velká špetka soli. Sůl totiž funguje jako zvýrazňovač chuti a posune váš dezert z "obyčejně sladkého" na "neodolatelně lahodný".</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-bake-blue/20 to-bake-cream/30 border border-bake-blue/30 rounded-3xl p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-bake-blue/10 text-9xl transform rotate-12 group-hover:scale-110 transition-transform"><i class="fas fa-ban"></i></div>
            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-3xl mb-2 z-10"><i class="fas fa-blender"></i></div>
            <div class="z-10">
                <span class="text-xs font-bold text-bake-blue uppercase tracking-widest mb-2 block">Křehkost versus guma</span>
                <h3 class="text-2xl font-bold text-bake-brown mb-3">Těsto nepřešlehejte</h3>
                <p class="text-slate-600 leading-relaxed">Když spojujete suché (mouka) a mokré ingredience na muffiny nebo třené buchty, míchejte jen do chvíle, než zmizí suché kousky mouky. Dlouhým mícháním se uvolní lepek a těsto bude po upečení tuhé a "gumové".</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-bake-cream/50 to-white border border-bake-cream rounded-3xl p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-bake-brown/5 text-9xl transform -rotate-12 group-hover:scale-110 transition-transform"><i class="fas fa-search-plus"></i></div>
            <div class="w-16 h-16 bg-bake-brown/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-brown text-3xl mb-2 z-10"><i class="fas fa-check-circle"></i></div>
            <div class="z-10">
                <span class="text-xs font-bold text-bake-brown/50 uppercase tracking-widest mb-2 block">Jistota je jistota</span>
                <h3 class="text-2xl font-bold text-bake-brown mb-3">Trik se špejlí</h3>
                <p class="text-slate-600 leading-relaxed">Každá trouba peče jinak, takže časy v receptech jsou jen orientační. Zda je bábovka nebo korpus hotový, poznáte tak, že doprostřed zapíchnete dřevěnou špejli. Pokud ji vytáhnete čistou (bez nalepeného syrového těsta), máte hotovo!</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-bake-blue/10 to-white border border-bake-blue/20 rounded-3xl p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-bake-blue/5 text-9xl transform rotate-12 group-hover:scale-110 transition-transform"><i class="fas fa-tint"></i></div>
            <div class="w-16 h-16 bg-bake-blue/10 rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-3xl mb-2 z-10"><i class="fas fa-lemon"></i></div>
            <div class="z-10">
                <span class="text-xs font-bold text-bake-blue/60 uppercase tracking-widest mb-2 block">Přírodní chemie</span>
                <h3 class="text-2xl font-bold text-bake-brown mb-3">Zkroťte jedlou sodu citronem</h3>
                <p class="text-slate-600 leading-relaxed">Zatímco prášek do pečiva začne fungovat sám od sebe díky vlhkosti a teplu, jedlá soda potřebuje k aktivaci kyselou složku. Pokud je v receptu soda, ujistěte se, že těsto obsahuje podmáslí, jogurt, kakao, nebo přidejte pár kapek citronové šťávy.</p>
            </div>
        </div>

    </div>
</div>

<div id="tab-qa" class="tab-content hidden transition-all duration-500">
    
    <div class="bg-bake-brown p-8 rounded-3xl shadow-lg mb-10 flex flex-col md:flex-row gap-6 items-center">
        <div class="text-bake-cream md:w-1/3">
            <h3 class="text-2xl font-bold mb-2">Trápí vás něco?</h3>
            <p class="text-bake-cream/80 text-sm">Zeptejte se naší komunity a zkušení pekaři vám rádi poradí.</p>
        </div>
        <div class="w-full md:w-2/3 flex gap-4">
            <input type="text" placeholder="Napište svůj dotaz..." class="w-full bg-white/10 border border-bake-cream/30 text-white placeholder-bake-cream/50 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-bake-blue">
            <button class="bg-bake-blue text-bake-brown font-bold px-6 py-3 rounded-xl hover:opacity-90 transition shadow-sm whitespace-nowrap">Zeptat se</button>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 cursor-pointer hover:bg-slate-50 transition" onclick="toggleAnswer('qa-1')">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-bake-blue/20 rounded-full flex items-center justify-center text-bake-brown font-bold text-xl shrink-0">M</div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-bake-brown text-lg">Proč mi bábovka vždycky splaskne?</h4>
                            <span class="text-xs font-bold text-bake-blue bg-bake-blue/10 px-3 py-1 rounded-full">2 Odpovědi</span>
                        </div>
                        <p class="text-slate-500 text-sm mt-1 mb-3">Peču bábovku přesně podle receptu, v troubě je krásně naskočená, ale jakmile ji vytáhnu ven, okamžitě splaskne. Nevíte, čím to může být?</p>
                        <p class="text-xs text-slate-400">Pekařka Míša • před 2 dny</p>
                    </div>
                </div>
            </div>
            
            <div id="answer-qa-1" class="hidden bg-slate-50 border-t border-slate-200 p-6 sm:p-8">
                <div class="flex items-start gap-4 mb-6 ml-8">
                    <div class="w-10 h-10 bg-bake-brown/20 rounded-full flex items-center justify-center text-bake-brown font-bold shrink-0">K</div>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex-1">
                        <p class="font-bold text-bake-brown text-sm mb-1">Karel_Pecitel <span class="text-slate-400 font-normal ml-2">před 1 dnem</span></p>
                        <p class="text-slate-600 text-sm">Ahoj! Většinou je to teplotním šokem. Jakmile se bábovka dopeče, nevytahuj ji hned ven. Vypni troubu, lehce pootevři dvířka (třeba do nich dej vařečku) a nech ji tam ještě 10-15 minut pomalu stydnout.</p>
                    </div>
                </div>
                
                <div class="ml-8 flex gap-4 mt-4">
                    <div class="w-10 h-10 bg-bake-blue rounded-full flex items-center justify-center text-bake-brown font-bold shrink-0 shadow-sm"><i class="fas fa-reply"></i></div>
                    <div class="flex-1">
                        <textarea rows="2" placeholder="Přidat odpověď..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-bake-blue outline-none resize-none mb-2"></textarea>
                        <button class="bg-bake-brown text-bake-cream px-6 py-2 rounded-xl font-bold hover:bg-opacity-90 transition text-sm">Odpovědět</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 cursor-pointer hover:bg-slate-50 transition" onclick="toggleAnswer('qa-2')">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-bake-blue/20 rounded-full flex items-center justify-center text-bake-brown font-bold text-xl shrink-0">P</div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-bake-brown text-lg">Lze udělat makronky z vlašských ořechů?</h4>
                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">Zatím bez odpovědi</span>
                        </div>
                        <p class="text-slate-500 text-sm mt-1 mb-3">Mám alergii na mandle, ze kterých se dělá klasická makronková mouka. Zkoušel to někdo z vlašáků?</p>
                        <p class="text-xs text-slate-400">Pepa_Novotny • před 5 hodinami</p>
                    </div>
                </div>
            </div>
            
            <div id="answer-qa-2" class="hidden bg-slate-50 border-t border-slate-200 p-6 sm:p-8">
                <div class="ml-8 flex gap-4">
                    <div class="w-10 h-10 bg-bake-blue rounded-full flex items-center justify-center text-bake-brown font-bold shrink-0 shadow-sm"><i class="fas fa-reply"></i></div>
                    <div class="flex-1">
                        <textarea rows="2" placeholder="Znáte odpověď? Poraďte Pepovi..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-bake-blue outline-none resize-none mb-2"></textarea>
                        <button class="bg-bake-brown text-bake-cream px-6 py-2 rounded-xl font-bold hover:bg-opacity-90 transition text-sm">Odpovědět</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Aktualizovaný JS pro přepínání tabů, aby pracoval s novými třídami
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('text-bake-brown', 'border-bake-brown');
            el.classList.add('text-slate-400', 'border-transparent');
        });

        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(tabId).classList.add('block');
        
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('text-slate-400', 'border-transparent');
        activeBtn.classList.add('text-bake-brown', 'border-bake-brown');
    }

    function toggleAnswer(qaId) {
        const answerBox = document.getElementById('answer-' + qaId);
        if (answerBox.classList.contains('hidden')) {
            answerBox.classList.remove('hidden');
        } else {
            answerBox.classList.add('hidden');
        }
    }
</script>

<?php require_once '../app/views/layout/footer.php'; ?>