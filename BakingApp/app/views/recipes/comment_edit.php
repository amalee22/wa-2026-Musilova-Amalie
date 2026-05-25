<?php require_once '../app/views/layout/header.php'; ?>
<div class="max-w-2xl mx-auto bg-white p-10 sm:p-14 rounded-[2rem] shadow-xl shadow-bake-brown/10 border border-bake-cream mt-10 mb-20">
    
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-semibold text-bake-brown mb-2"><i class="fas fa-pen text-bake-blue mr-3"></i>Úprava komentáře</h2>
        <p class="text-slate-500">Zde můžete poupravit své hodnocení receptu.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=recipe/updateComment/<?= htmlspecialchars($comment['id']) ?>" method="post">
        <textarea name="text" rows="5" required class="w-full bg-bake-cream/20 border-0 rounded-xl px-5 py-4 focus:ring-2 focus:ring-bake-blue transition-all resize-y mb-8 text-slate-700 font-medium"><?= htmlspecialchars($comment['text']) ?></textarea>
        
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $comment['recipe_id'] ?>" class="text-slate-400 hover:text-bake-brown font-medium px-4 py-2 transition-colors">Zrušit změny</a>
            <button type="submit" class="w-full sm:w-auto bg-bake-brown hover:bg-opacity-90 text-bake-cream font-semibold px-10 py-3.5 rounded-2xl shadow-lg shadow-bake-brown/30 transition-all">Uložit úpravy</button>
        </div>
    </form>

</div>
<?php require_once '../app/views/layout/footer.php'; ?>