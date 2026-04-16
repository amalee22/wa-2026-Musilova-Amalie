<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-2xl mx-auto bg-white p-10 sm:p-14 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 mt-10">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-semibold text-slate-800 mb-2">Nová registrace</h2>
        <p class="text-slate-500">Vytvořte si účet pro přidávání a správu vlastních titulů.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="post" class="space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label for="username" class="block text-sm font-semibold text-slate-600 mb-2">Uživatelské jméno <span class="text-teal-500">*</span></label>
                <input type="text" id="username" name="username" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-600 mb-2">E-mail <span class="text-teal-500">*</span></label>
                <input type="email" id="email" name="email" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-600 mb-2">Heslo <span class="text-teal-500">*</span></label>
                <input type="password" id="password" name="password" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="password_confirm" class="block text-sm font-semibold text-slate-600 mb-2">Potvrzení hesla <span class="text-teal-500">*</span></label>
                <input type="password" id="password_confirm" name="password_confirm" required class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div class="sm:col-span-2 pt-4 border-t border-slate-100">
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Osobní údaje (Volitelné)</h3>
            </div>

            <div>
                <label for="first_name" class="block text-sm font-semibold text-slate-600 mb-2">Křestní jméno</label>
                <input type="text" id="first_name" name="first_name" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div>
                <label for="last_name" class="block text-sm font-semibold text-slate-600 mb-2">Příjmení</label>
                <input type="text" id="last_name" name="last_name" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>

            <div class="sm:col-span-2">
                <label for="nickname" class="block text-sm font-semibold text-slate-600 mb-2">Přezdívka (zobrazí se v menu)</label>
                <input type="text" id="nickname" name="nickname" class="w-full bg-slate-50/50 border-0 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold py-3.5 rounded-2xl shadow-lg shadow-teal-600/30 hover:-translate-y-0.5 transition-all">
                Vytvořit účet
            </button>
            <p class="text-center text-slate-500 text-sm mt-6">
                Už máte účet? <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="text-teal-600 font-medium hover:underline">Přihlaste se zde</a>.
            </p>
        </div>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>