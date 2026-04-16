<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-md mx-auto bg-white p-10 sm:p-14 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 mt-10">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-semibold text-slate-800 mb-2">Přihlášení</h2>
        <p class="text-slate-500">Vítejte zpět v komunitní databázi.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="post" class="space-y-6">
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-600 mb-2">E-mail</label>
            <input type="email" id="email" name="email" required autofocus class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-teal-500 focus:bg-white transition-all">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-600 mb-2">Heslo</label>
            <input type="password" id="password" name="password" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-teal-500 focus:bg-white transition-all">
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold py-3.5 rounded-2xl shadow-lg shadow-teal-600/30 hover:-translate-y-0.5 transition-all">
                Přihlásit se
            </button>
        </div>
        
        <p class="text-center text-slate-500 text-sm mt-6">
            Nemáte ještě účet? <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="text-teal-600 font-medium hover:underline">Zaregistrujte se</a>.
        </p>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>