<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-md mx-auto bg-white p-8 sm:p-10 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 mt-4">
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-semibold text-slate-800 mb-2">Přihlášení</h2>
        <p class="text-slate-500 text-sm">Vítejte zpět v komunitní databázi.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="post" class="space-y-5">
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-600 mb-1.5">E-mail</label>
            <input type="email" id="email" name="email" required autofocus class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl px-4 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-teal-500 focus:bg-white transition-all">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-600 mb-1.5">Heslo</label>
            <div class="relative">
                <input type="password" id="password" name="password" required class="w-full bg-slate-50/50 border-0 text-slate-900 rounded-xl pl-4 pr-12 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-teal-500 focus:bg-white transition-all">
                <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-teal-600 transition">
                    <span class="text-lg">👁️</span>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold py-3 rounded-2xl shadow-lg shadow-teal-600/30 hover:-translate-y-0.5 transition-all">
                Přihlásit se
            </button>
        </div>
        
        <p class="text-center text-slate-500 text-sm mt-4">
            Nemáte ještě účet? <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="text-teal-600 font-medium hover:underline">Zaregistrujte se</a>.
        </p>
    </form>
</div>

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '<span class="text-lg opacity-50">🙈</span>';
        } else {
            input.type = 'password';
            btn.innerHTML = '<span class="text-lg">👁️</span>';
        }
    }
</script>

<?php require_once '../app/views/layout/footer.php'; ?>