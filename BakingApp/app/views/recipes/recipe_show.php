<?php 
/**
 * @var array $recipe
 * @var array $comments
 * @var array $similarRecipes
 * @var int $likesCount
 * @var bool $isLiked
 * @var bool $isFavorited
 */
require_once '../app/views/layout/header.php'; 

$ingredientsList = array_filter(array_map('trim', explode("\n", $recipe['ingredients'] ?? '')));
$instructionsList = array_filter(array_map('trim', explode("\n", $recipe['instructions'] ?? '')));
?>

<div class="max-w-5xl mx-auto bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden transition-all duration-300 mb-20">
    
    <div class="bg-bake-brown p-10 sm:p-14 text-center relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-bake-cream opacity-10 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-bake-blue opacity-10 rounded-full blur-3xl"></div>
        
        <span class="inline-block px-5 py-2 bg-bake-blue/20 text-bake-blue rounded-full text-xs font-bold tracking-widest uppercase mb-6 border border-bake-blue/30 relative z-10">
            <?= htmlspecialchars($recipe['category_name'] ?? 'Nezařazeno') ?>
        </span>
        <h2 class="text-4xl sm:text-5xl font-black text-bake-cream mb-4 tracking-tight relative z-10">
            <?= htmlspecialchars($recipe['title']) ?>
        </h2>
        <p class="text-bake-cream/80 text-lg font-medium relative z-10">
            od <a href="<?= BASE_URL ?>/index.php?url=user/show/<?= $recipe['created_by'] ?>" class="text-white hover:text-bake-blue transition-colors underline decoration-bake-blue/50 underline-offset-4"><?= htmlspecialchars($recipe['author_name'] ?? 'Neznámý kuchař') ?></a>
        </p>
    </div>

    <div class="p-8 sm:p-12">
        
        <div class="flex flex-col sm:flex-row items-center justify-between border-b border-bake-cream pb-8 mb-10 no-print gap-4">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 text-bake-brown font-bold text-xl bg-bake-cream/30 px-5 py-2.5 rounded-2xl border border-bake-cream">
                    <i class="far fa-clock text-bake-blue"></i> <?= htmlspecialchars($recipe['prep_time'] ?? 0) ?> min
                </div>
                
                <button onclick="toggleLike(<?= $recipe['id'] ?>)" class="flex items-center gap-2 text-bake-brown hover:text-rose-500 transition-colors group">
                    <i id="heartIcon" class="<?= $isLiked ? 'fas text-rose-500' : 'far' ?> fa-heart text-3xl group-active:scale-75 transition-transform"></i>
                    <span id="likesCount" class="font-bold text-xl"><?= $likesCount ?></span>
                </button>

                <button onclick="toggleFavorite(<?= $recipe['id'] ?>)" class="flex items-center gap-2 text-bake-brown hover:text-bake-blue transition-colors group" title="Uložit do sbírky">
                    <i id="bookmarkIcon" class="<?= $isFavorited ? 'fas text-bake-blue' : 'far' ?> fa-bookmark text-3xl group-active:scale-75 transition-transform"></i>
                </button>
            </div>

            <div>
                <button onclick="window.print()" class="text-bake-brown hover:bg-bake-brown hover:text-bake-cream transition-colors font-bold text-sm flex items-center gap-2 border-2 border-bake-cream px-5 py-2.5 rounded-2xl">
                    <i class="fas fa-print"></i> Vytisknout
                </button>
            </div>
        </div>

        <?php if(!empty($recipe['description'])): ?>
            <div class="mb-12 text-slate-600 text-lg leading-relaxed font-serif italic border-l-4 border-bake-blue pl-6">
                <?= nl2br(htmlspecialchars($recipe['description'])) ?>
            </div>
        <?php endif; ?>

        <div class="bg-bake-cream/30 border border-bake-cream p-6 rounded-3xl mb-12 no-print flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-bake-brown font-bold">
                <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-bake-blue text-xl"><i class="fas fa-calculator"></i></div>
                <span>Převodník hrnků a gramů</span>
            </div>
            <div class="flex w-full sm:w-auto gap-3">
                <input type="number" id="calcValue" placeholder="Mn." class="w-24 p-3 rounded-xl border border-bake-cream focus:ring-2 focus:ring-bake-blue outline-none text-center font-bold text-slate-700">
                <select id="calcType" class="p-3 rounded-xl border border-bake-cream focus:ring-2 focus:ring-bake-blue outline-none bg-white font-medium text-slate-700">
                    <option value="cups_to_g">Hrnky na Gramy</option>
                    <option value="g_to_cups">Gramy na Hrnky</option>
                </select>
                <button onclick="calculateCups()" class="bg-bake-brown text-bake-cream px-5 py-3 rounded-xl font-bold hover:bg-opacity-90 transition"><i class="fas fa-equals"></i></button>
            </div>
            <div id="calcResult" class="font-black text-xl text-bake-blue hidden"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
            
            <div class="lg:col-span-5">
                <h3 class="text-3xl font-black text-bake-brown mb-8">Suroviny</h3>
                <div class="space-y-1">
                    <?php foreach($ingredientsList as $index => $item): ?>
                        <div class="flex justify-between items-center py-4 border-b border-slate-100 last:border-0 group cursor-pointer" onclick="toggleIng('ing-<?= $index ?>')">
                            <span id="ing-<?= $index ?>-text" class="text-slate-800 font-medium text-lg transition-all duration-300 pr-4"><?= htmlspecialchars($item) ?></span>
                            <div class="shrink-0 relative flex items-center justify-center w-7 h-7 rounded border-2 border-slate-300 group-hover:border-bake-blue transition-colors bg-white">
                                <input type="checkbox" id="ing-<?= $index ?>-check" class="absolute opacity-0 w-full h-full cursor-pointer pointer-events-none">
                                <i id="ing-<?= $index ?>-icon" class="fas fa-check text-bake-blue opacity-0 transition-opacity duration-300 scale-50"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button onclick="copyUncheckedIngredients()" class="mt-8 w-full flex items-center justify-center gap-2 py-4 bg-bake-cream/40 hover:bg-bake-cream border border-bake-cream text-bake-brown font-bold rounded-2xl transition group no-print">
                    <i class="far fa-copy group-hover:scale-110 transition-transform"></i> <span id="copyText">Zkopírovat neoznačené do nákupu</span>
                </button>
            </div>

            <div class="lg:col-span-7">
                <h3 class="text-3xl font-black text-bake-brown mb-8">Postup přípravy</h3>
                <div class="space-y-6">
                    <?php 
                        $stepNum = 1; 
                        foreach($instructionsList as $index => $step): 
                            $cleanStep = preg_replace('/^\d+[\.\)]\s*/', '', $step);
                    ?>
                        <div class="flex gap-5 items-start group cursor-pointer p-4 -ml-4 rounded-2xl hover:bg-slate-50 transition-colors" onclick="toggleStep('step-<?= $index ?>')">
                            <div class="w-8 h-8 rounded-full bg-bake-blue text-bake-brown flex items-center justify-center font-bold text-sm shrink-0 mt-1 shadow-sm">
                                <?= $stepNum++ ?>
                            </div>
                            
                            <p id="step-<?= $index ?>-text" class="text-slate-700 text-lg leading-relaxed flex-1 transition-all duration-300">
                                <?= htmlspecialchars($cleanStep) ?>
                            </p>
                            
                            <div class="shrink-0 mt-1 relative flex items-center justify-center w-7 h-7 rounded border-2 border-slate-300 group-hover:border-bake-blue transition-colors bg-white">
                                <input type="checkbox" id="step-<?= $index ?>-check" class="absolute opacity-0 w-full h-full cursor-pointer pointer-events-none">
                                <i id="step-<?= $index ?>-icon" class="fas fa-check text-bake-blue opacity-0 transition-opacity duration-300 scale-50"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <?php $images = json_decode($recipe['images'] ?? '[]', true); ?>
        <?php if (!empty($images)): ?>
        <div class="mt-16 pt-10 border-t border-slate-100 no-print">
            <h3 class="font-bold text-slate-400 uppercase tracking-widest text-sm mb-6">Fotogalerie z pečení</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <?php foreach ($images as $img): ?>
                    <button type="button" onclick="openLightbox('<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>')" class="block w-full rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:ring-4 ring-bake-blue/30 transition-all aspect-square bg-slate-50 cursor-zoom-in">
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>" alt="Fotka receptu" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php 
            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
            $isAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $recipe['created_by'];
        ?>
        <?php if ($isAuthor || $isAdmin): ?>
            <div class="flex justify-end gap-4 pt-10 mt-16 border-t border-slate-100 no-print">
                <a href="<?= BASE_URL ?>/index.php?url=recipe/edit/<?= $recipe['id'] ?>" class="bg-bake-brown hover:bg-opacity-90 text-bake-cream px-8 py-3.5 rounded-2xl shadow-lg font-bold flex items-center gap-2">
                    <i class="fas fa-pen"></i> <?= $isAuthor ? 'Upravit můj recept' : 'Upravit recept (Admin)' ?>
                </a>
                
                <form action="<?= BASE_URL ?>/index.php?url=recipe/delete/<?= $recipe['id'] ?>" method="POST" onsubmit="return confirm('Opravdu chcete tento recept trvale smazat?')" class="inline-block">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-8 py-3.5 rounded-2xl shadow-lg font-bold flex items-center gap-2">
                        <i class="fas fa-trash"></i> Smazat
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <?php if (!empty($similarRecipes)): ?>
        <div class="mt-16 pt-16 border-t border-slate-100 no-print">
            <h3 class="text-2xl font-black text-bake-brown mb-8"><i class="fas fa-layer-group text-bake-blue mr-3"></i>Podobné recepty v této kategorii</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($similarRecipes as $similar): ?>
                    <?php 
                        $simImages = json_decode($similar['images'] ?? '[]', true);
                        $simCover = !empty($simImages) ? $simImages[0] : null;
                    ?>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $similar['id'] ?>" class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-lg transition-all group flex items-center gap-4">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-bake-cream shrink-0 border border-bake-cream relative">
                            <?php if ($simCover): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($simCover) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-bake-blue/50"><i class="fas fa-cookie-bite text-2xl"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-xs font-bold text-slate-400 block mb-1"><i class="far fa-clock"></i> <?= htmlspecialchars($similar['prep_time']) ?> min</span>
                            <h4 class="font-bold text-bake-brown truncate group-hover:text-bake-blue transition-colors"><?= htmlspecialchars($similar['title']) ?></h4>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="mt-16 pt-16 border-t border-slate-100 no-print">
            <h3 class="text-3xl font-black text-bake-brown mb-8"><i class="far fa-comments text-bake-blue mr-3"></i>Hodnocení pekařů</h3>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="bg-bake-cream/20 p-8 rounded-3xl border border-bake-cream mb-10">
                    <form action="<?= BASE_URL ?>/index.php?url=recipe/addComment" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="recipe_id" value="<?= htmlspecialchars($recipe['id']) ?>">
                        
                        <textarea name="text" rows="3" required placeholder="Jak se vám recept povedl? Podělte se o výsledek..." class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-bake-blue outline-none transition-all resize-none mb-4 text-slate-700"></textarea>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-2">
                            <label class="cursor-pointer text-sm font-bold text-bake-blue hover:text-bake-brown transition-colors flex items-center gap-2">
                                <i class="fas fa-camera text-xl"></i> Přidat fotku výtvoru
                                <input type="file" name="comment_image" accept="image/*" class="hidden" onchange="document.getElementById('file-chosen-main').textContent = this.files[0].name">
                            </label>
                            <span id="file-chosen-main" class="text-xs text-slate-500 flex-1 ml-2"></span>
                            
                            <button type="submit" class="bg-bake-blue hover:bg-opacity-80 text-bake-brown px-8 py-3 rounded-2xl font-black transition shadow-sm w-full sm:w-auto">
                                Odeslat hodnocení
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="bg-slate-50 p-6 rounded-2xl mb-10 text-center border border-slate-200">
                    <p class="text-slate-600">Pro přidání komentáře se musíte <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-bake-blue font-bold hover:underline">přihlásit</a>.</p>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex gap-5 group transition-all">
                            <div class="w-12 h-12 rounded-full bg-bake-blue/20 flex items-center justify-center text-bake-brown font-black text-xl shrink-0">
                                <?= strtoupper(substr(htmlspecialchars($comment['nickname'] ?: $comment['username']), 0, 1)) ?>
                            </div>
                            <div class="w-full">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-3">
                                        <span class="font-bold text-bake-brown text-lg"><?= htmlspecialchars($comment['nickname'] ?: $comment['username']) ?></span>
                                        <span class="text-xs text-slate-400 font-medium"><?= date('d. m. Y H:i', strtotime($comment['created_at'])) ?></span>
                                    </div>
                                    
                                    <?php 
                                        $isCommentAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id'];
                                    ?>
                                    <?php if ($isCommentAuthor || $isAdmin): ?>
                                        <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <?php if ($isCommentAuthor): ?>
                                                <a href="<?= BASE_URL ?>/index.php?url=recipe/editComment/<?= $comment['id'] ?>" class="text-bake-blue hover:text-bake-brown transition-colors text-sm font-bold"><i class="fas fa-pen"></i></a>
                                            <?php endif; ?>
                                            
                                            <form action="<?= BASE_URL ?>/index.php?url=recipe/deleteComment/<?= $comment['id'] ?>" method="POST" onsubmit="return confirm('Opravdu smazat komentář?')" class="inline-block m-0 p-0">
                                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                <button type="submit" class="bg-transparent border-0 text-red-400 hover:text-red-600 cursor-pointer p-0 transition-colors text-sm font-bold">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <p class="text-slate-700 leading-relaxed pr-10 mb-3"><?= nl2br(htmlspecialchars($comment['text'])) ?></p>
                                
                                <?php if (!empty($comment['image'])): ?>
                                    <button type="button" onclick="openLightbox('<?= BASE_URL ?>/uploads/<?= htmlspecialchars($comment['image']) ?>')" class="block w-48 aspect-square rounded-xl overflow-hidden shadow-sm hover:shadow-md border border-slate-200 cursor-zoom-in transition-all mb-3">
                                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($comment['image']) ?>" alt="Výtvor od uživatele" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                    </button>
                                <?php endif; ?>
                                
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <button onclick="document.getElementById('reply-form-<?= $comment['id'] ?>').classList.toggle('hidden')" class="mt-2 text-sm font-bold text-bake-blue hover:text-bake-brown transition-colors">
                                        <i class="fas fa-reply mr-1"></i> Odpovědět
                                    </button>

                                    <div id="reply-form-<?= $comment['id'] ?>" class="hidden mt-4 bg-bake-cream/20 p-5 rounded-2xl border border-bake-cream">
                                        <form action="<?= BASE_URL ?>/index.php?url=recipe/addComment" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
                                            <input type="hidden" name="recipe_id" value="<?= $recipe['id'] ?>">
                                            <textarea name="text" rows="2" required placeholder="Vaše odpověď..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-bake-blue outline-none transition-all resize-none mb-3 text-slate-700"></textarea>
                                            
                                            <div class="flex items-center justify-between">
                                                <label class="cursor-pointer text-xs font-bold text-bake-blue hover:text-bake-brown transition-colors flex items-center gap-2">
                                                    <i class="fas fa-camera"></i> Fotka
                                                    <input type="file" name="comment_image" accept="image/*" class="hidden" onchange="document.getElementById('file-chosen-<?= $comment['id'] ?>').textContent = this.files[0].name">
                                                </label>
                                                <span id="file-chosen-<?= $comment['id'] ?>" class="text-xs text-slate-500 mr-auto ml-2"></span>
                                                
                                                <button type="submit" class="bg-bake-brown hover:bg-opacity-90 text-bake-cream px-6 py-2 rounded-xl font-bold transition shadow-sm text-sm">
                                                    Odeslat odpověď
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($comment['replies'])): ?>
                                    <div class="mt-6 space-y-4 border-l-2 border-bake-cream pl-6 ml-2">
                                        <?php foreach ($comment['replies'] as $reply): ?>
                                            <div class="flex items-start gap-4 group/reply">
                                                <div class="w-8 h-8 rounded-full bg-bake-cream flex items-center justify-center text-bake-brown font-bold text-xs shrink-0">
                                                    <?= strtoupper(substr(htmlspecialchars($reply['nickname'] ?: $reply['username']), 0, 1)) ?>
                                                </div>
                                                <div class="w-full bg-slate-50 p-4 rounded-2xl border border-slate-100 shadow-sm">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-bake-brown text-sm"><?= htmlspecialchars($reply['nickname'] ?: $reply['username']) ?></span>
                                                            <span class="text-xs text-slate-400 font-medium"><?= date('d. m. Y H:i', strtotime($reply['created_at'])) ?></span>
                                                        </div>
                                                        
                                                        <?php 
                                                            $isReplyAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $reply['user_id'];
                                                        ?>
                                                        <?php if ($isReplyAuthor || $isAdmin): ?>
                                                            <div class="flex items-center gap-3 opacity-0 group-hover/reply:opacity-100 transition-opacity">
                                                                <?php if ($isReplyAuthor): ?>
                                                                    <a href="<?= BASE_URL ?>/index.php?url=recipe/editComment/<?= $reply['id'] ?>" class="text-bake-blue hover:text-bake-brown transition-colors text-xs font-bold"><i class="fas fa-pen"></i></a>
                                                                <?php endif; ?>
                                                                
                                                                <form action="<?= BASE_URL ?>/index.php?url=recipe/deleteComment/<?= $reply['id'] ?>" method="POST" onsubmit="return confirm('Opravdu smazat odpověď?')" class="inline-block m-0 p-0">
                                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                                    <button type="submit" class="bg-transparent border-0 text-red-400 hover:text-red-600 cursor-pointer p-0 transition-colors text-xs font-bold">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <p class="text-slate-700 text-sm leading-relaxed mb-2"><?= nl2br(htmlspecialchars($reply['text'])) ?></p>
                                                    
                                                    <?php if (!empty($reply['image'])): ?>
                                                        <button type="button" onclick="openLightbox('<?= BASE_URL ?>/uploads/<?= htmlspecialchars($reply['image']) ?>')" class="block w-32 aspect-square rounded-lg overflow-hidden shadow-sm hover:shadow-md border border-slate-200 cursor-zoom-in transition-all">
                                                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($reply['image']) ?>" alt="Výtvor od uživatele" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <p class="text-slate-500 font-medium">Zatím tu nejsou žádné komentáře. Buďte první!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        </div>
</div>

<div id="lightbox" class="fixed inset-0 bg-slate-900/95 z-50 hidden flex items-center justify-center p-4 cursor-pointer" onclick="this.classList.add('hidden')">
    <img id="lightbox-img" src="" class="max-w-full max-h-full rounded-xl shadow-2xl transition-transform transform scale-95 duration-200" alt="Detail fotky">
    <button class="absolute top-6 right-6 text-white/50 hover:text-white text-5xl font-light transition-colors">&times;</button>
</div>

<style>
    @media print {
        header, footer, .no-print, button { display: none !important; }
        body { background: white !important; }
        .max-w-5xl { box-shadow: none !important; border: none !important; margin: 0 !important; max-width: 100% !important; }
    }
</style>

<script>
    function toggleIng(id) {
        const textEl = document.getElementById(id + '-text'); const checkEl = document.getElementById(id + '-check'); const iconEl = document.getElementById(id + '-icon');
        checkEl.checked = !checkEl.checked;
        if(checkEl.checked) { textEl.classList.add('line-through', 'text-slate-400'); textEl.classList.remove('text-slate-800'); iconEl.classList.remove('opacity-0', 'scale-50'); } 
        else { textEl.classList.remove('line-through', 'text-slate-400'); textEl.classList.add('text-slate-800'); iconEl.classList.add('opacity-0', 'scale-50'); }
    }

    function toggleStep(id) {
        const textEl = document.getElementById(id + '-text'); const checkEl = document.getElementById(id + '-check'); const iconEl = document.getElementById(id + '-icon');
        checkEl.checked = !checkEl.checked;
        if(checkEl.checked) { textEl.classList.add('line-through', 'text-slate-400', 'opacity-50'); textEl.classList.remove('text-slate-700'); iconEl.classList.remove('opacity-0', 'scale-50'); } 
        else { textEl.classList.remove('line-through', 'text-slate-400', 'opacity-50'); textEl.classList.add('text-slate-700'); iconEl.classList.add('opacity-0', 'scale-50'); }
    }

    function copyUncheckedIngredients() {
        let textToCopy = "Nákupní seznam:\n"; let hasItems = false;
        <?php foreach($ingredientsList as $index => $item): ?>
            if (!document.getElementById('ing-<?= $index ?>-check').checked) { textToCopy += "- <?= addslashes($item) ?>\n"; hasItems = true; }
        <?php endforeach; ?>
        if (hasItems) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                const btnText = document.getElementById('copyText'); const originalText = btnText.innerText;
                btnText.innerText = "Zkopírováno do schránky! ✔️";
                setTimeout(() => { btnText.innerText = originalText; }, 3000);
            });
        } else { alert("Máte zaškrtnuté úplně všechny suroviny! Nic nechybí."); }
    }

    function toggleLike(recipeId) {
        fetch('<?= BASE_URL ?>/index.php?url=recipe/toggleLike', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ recipe_id: recipeId })
        }).then(res => res.json()).then(data => {
            if (data.error) { alert('Pro lajkování se musíte přihlásit!'); return; }
            const icon = document.getElementById('heartIcon'); const count = document.getElementById('likesCount');
            if (data.status === 'liked') { icon.classList.remove('far'); icon.classList.add('fas', 'text-rose-500'); } 
            else { icon.classList.remove('fas', 'text-rose-500'); icon.classList.add('far'); }
            count.innerText = data.count;
        });
    }

    function toggleFavorite(recipeId) {
        fetch('<?= BASE_URL ?>/index.php?url=recipe/toggleFavorite', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ recipe_id: recipeId })
        }).then(res => res.json()).then(data => {
            if (data.error) { alert('Pro uložení receptu se musíte přihlásit!'); return; }
            const icon = document.getElementById('bookmarkIcon');
            if (data.status === 'favorited') { icon.classList.remove('far'); icon.classList.add('fas', 'text-bake-blue'); } 
            else { icon.classList.remove('fas', 'text-bake-blue'); icon.classList.add('far'); }
        });
    }

    function calculateCups() {
        const val = document.getElementById('calcValue').value; const type = document.getElementById('calcType').value; const result = document.getElementById('calcResult');
        if(!val || val <= 0) return;
        result.classList.remove('hidden');
        if(type === 'g_to_cups') { result.innerText = "= " + (val / 120).toFixed(1) + " hrnků"; } 
        else { result.innerText = "= " + (val * 120).toFixed(0) + " g"; }
    }

    function openLightbox(src) {
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        img.src = src;
        lightbox.classList.remove('hidden');
        setTimeout(() => img.classList.replace('scale-95', 'scale-100'), 10);
    }
</script>

<?php require_once '../app/views/layout/footer.php'; ?>