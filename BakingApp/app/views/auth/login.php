<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-md mx-auto bg-white p-10 sm:p-14 rounded-[2rem] shadow-xl shadow-bake-brown/10 border border-bake-cream mt-10">
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-semibold text-bake-brown mb-2">Přihlášení</h2>
        <p class="text-slate-500">Vítejte zpět v naší pekařské komunitě.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="post" class="space-y-6">
        <div>
            <label for="email" class="block text-sm font-semibold text-bake-brown mb-2">E-mail</label>
            <input type="email" id="email" name="email" required autofocus class="w-full bg-bake-cream/30 border-0 text-slate-900 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-inset focus:ring-bake-blue focus:bg-white transition-all">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-bake-brown mb-2">Heslo</label>
            <input type="password" id="password" name="password" required class="w-full bg-bake-cream/30 border-0 text-slate-900 rounded-xl px-4 py-3.5 ring-1 ring-inset ring-bake-cream focus:ring-2 focus:ring-inset focus:ring-bake-blue focus:bg-white transition-all">
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-bake-brown hover:bg-opacity-90 text-bake-cream font-semibold py-3.5 rounded-2xl shadow-lg shadow-bake-brown/30 hover:-translate-y-0.5 transition-all">
                Přihlásit se
            </button>
        </div>
        
        <p class="text-center text-slate-500 text-sm mt-6">
            Nemáte ještě účet? <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="text-bake-blue font-bold hover:underline">Zaregistrujte se</a>.
        </p>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>