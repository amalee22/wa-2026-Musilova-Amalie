<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-2xl mx-auto space-y-5 mb-20 mt-6">
    <div class="bg-white border border-bake-brown/10 rounded-[1.75rem] shadow-lg shadow-bake-brown/5 p-8 sm:p-10">
        
        <div class="flex items-center gap-5 mb-8 pb-7 border-b border-bake-brown/10">
            <div class="w-14 h-14 rounded-full bg-bake-blue/20 border border-bake-blue/40 flex items-center justify-center shrink-0">
                <i class="fas fa-lightbulb text-xl text-bake-brown"></i>
            </div>
            <div>
                <h2 class="font-display text-2xl font-bold text-bake-brown leading-tight">Přidat pekařský tip</h2>
                <p class="text-xs text-slate-400 font-light mt-0.5 tracking-wide">Poraďte ostatním, jak si usnadnit práci v kuchyni.</p>
            </div>
        </div>

        <form action="<?= BASE_URL ?>/index.php?url=recipe/storeTip" method="post" class="flex flex-col gap-5">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="flex flex-col gap-1.5">
                <label for="title" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Název tipu <span class="text-bake-blue">*</span></label>
                <input type="text" id="title" name="title" required placeholder="Např. Jak na dokonalý sníh"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="content" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Popis (Váš tip) <span class="text-bake-blue">*</span></label>
                <textarea name="content" id="content" rows="5" required placeholder="Vždy přidávám špetku soli..."
                          class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all resize-y"></textarea>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="icon" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">Ikona (Volitelné)</label>
                <select id="icon" name="icon" class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all">
                    <option value="fas fa-lightbulb">💡 Žárovka (Základní)</option>
                    <option value="fas fa-thermometer-half">🌡️ Teploměr (Teplota)</option>
                    <option value="fas fa-weight">⚖️ Váha (Měření)</option>
                    <option value="fas fa-snowflake">❄️ Vločka (Chlazení)</option>
                    <option value="fas fa-blender">🥣 Mixér (Míchání)</option>
                </select>
            </div>

            <div class="flex items-center justify-between pt-6 mt-2 border-t border-bake-brown/10">
                <a href="<?= BASE_URL ?>/index.php" class="text-[13px] text-slate-400 hover:text-bake-brown font-medium px-2 py-2 transition-colors">Zrušit</a>
                <button type="submit" class="bg-bake-brown text-bake-cream px-8 py-3 rounded-xl text-sm font-medium tracking-wide shadow-md shadow-bake-brown/20 hover:bg-opacity-90 hover:-translate-y-0.5 transition-all">
                    Odeslat tip
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>