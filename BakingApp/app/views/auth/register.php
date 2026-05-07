<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-2xl mx-auto bg-white p-10 sm:p-14 rounded-[2rem] shadow-xl shadow-bake-brown/10 border border-bake-cream mt-10">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-semibold text-bake-brown mb-2">Nová registrace</h2>
        <p class="text-slate-500">Vytvořte si účet pro přidávání a hodnocení receptů.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="post" class="space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="username" class="block text-sm font-semibold text-bake-brown mb-2">Uživatelské jméno <span class="text-bake-blue">*</span></label>
                <input type="text" id="username" name="username" required class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-bake-brown mb-2">E-mail <span class="text-bake-blue">*</span></label>
                <input type="email" id="email" name="email" required class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-bake-brown mb-2">Heslo <span class="text-bake-blue">*</span></label>
                <input type="password" id="password" name="password" required class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div>
                <label for="password_confirm" class="block text-sm font-semibold text-bake-brown mb-2">Potvrzení hesla <span class="text-bake-blue">*</span></label>
                <input type="password" id="password_confirm" name="password_confirm" required class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div class="sm:col-span-2 pt-4 border-t border-bake-cream/50">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Osobní údaje (Volitelné)</h3>
            </div>

            <div>
                <label for="first_name" class="block text-sm font-semibold text-bake-brown mb-2">Křestní jméno</label>
                <input type="text" id="first_name" name="first_name" class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div>
                <label for="last_name" class="block text-sm font-semibold text-bake-brown mb-2">Příjmení</label>
                <input type="text" id="last_name" name="last_name" class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>

            <div class="sm:col-span-2">
                <label for="nickname" class="block text-sm font-semibold text-bake-brown mb-2">Přezdívka (zobrazí se u komentářů)</label>
                <input type="text" id="nickname" name="nickname" class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-bake-blue transition-all">
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-bake-brown hover:bg-opacity-90 text-bake-cream font-semibold py-3.5 rounded-2xl shadow-lg shadow-bake-brown/30 hover:-translate-y-0.5 transition-all">
                Vytvořit účet
            </button>
            <p class="text-center text-slate-500 text-sm mt-6">
                Už máte účet? <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-bake-blue font-bold hover:underline">Přihlaste se zde</a>.
            </p>
        </div>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>