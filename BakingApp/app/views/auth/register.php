<?php require_once '../app/views/layout/header.php'; ?>

<div class="max-w-xl mx-auto bg-white border border-bake-brown/10 rounded-[2rem] shadow-xl shadow-bake-brown/5 p-10 sm:p-14 mt-8 mb-16">

    <!-- Brand mark -->
    <div class="flex items-center justify-center gap-3 mb-8">
        <i class="fas fa-cookie-bite text-2xl text-bake-brown"></i>
        <span class="font-display text-xl font-bold text-bake-brown tracking-tight">Overbaked</span>
    </div>

    <!-- Heading -->
    <div class="mb-8 text-center">
        <h2 class="font-display text-3xl font-bold text-bake-brown mb-2">Nová registrace</h2>
        <p class="text-sm text-slate-400 font-light tracking-wide">Vytvořte si účet pro přidávání a hodnocení receptů.</p>
    </div>

    <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="post">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            <div class="flex flex-col gap-1.5">
                <label for="username" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    Uživatelské jméno <span class="text-bake-blue">*</span>
                </label>
                <input type="text" id="username" name="username" required placeholder="jannovak"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/25 placeholder:font-light">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    E-mail <span class="text-bake-blue">*</span>
                </label>
                <input type="email" id="email" name="email" required placeholder="jan@email.cz"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/25 placeholder:font-light">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="password" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    Heslo <span class="text-bake-blue">*</span>
                </label>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/25 placeholder:font-light">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="password_confirm" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    Potvrzení hesla <span class="text-bake-blue">*</span>
                </label>
                <input type="password" id="password_confirm" name="password_confirm" required placeholder="••••••••"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/25 placeholder:font-light">
            </div>

            <!-- Divider -->
            <div class="sm:col-span-2 flex items-center gap-4 py-1">
                <div class="flex-1 h-px bg-bake-brown/10"></div>
                <span class="text-[10px] font-medium text-slate-300 uppercase tracking-[0.09em] whitespace-nowrap">
                    Osobní údaje — volitelné
                </span>
                <div class="flex-1 h-px bg-bake-brown/10"></div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="first_name" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    Křestní jméno
                </label>
                <input type="text" id="first_name" name="first_name" placeholder="Jan"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/25 placeholder:font-light">
            </div>

            <div class="flex flex-col gap-1.5">
                <label for="last_name" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    Příjmení
                </label>
                <input type="text" id="last_name" name="last_name" placeholder="Novák"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/25 placeholder:font-light">
            </div>

            <div class="sm:col-span-2 flex flex-col gap-1.5">
                <label for="nickname" class="text-[11px] font-medium text-slate-400 uppercase tracking-[0.07em]">
                    Přezdívka
                    <span class="normal-case font-light text-slate-300 tracking-normal ml-1">(zobrazí se u komentářů)</span>
                </label>
                <input type="text" id="nickname" name="nickname" placeholder="Honzík"
                       class="w-full bg-bake-cream/30 border-0 rounded-xl px-4 py-3 text-bake-brown text-sm ring-1 ring-inset ring-bake-brown/20 focus:ring-2 focus:ring-bake-blue transition-all placeholder:text-bake-brown/25 placeholder:font-light">
            </div>

        </div>

        <div class="mt-8">
            <button type="submit"
                    class="w-full bg-bake-brown text-bake-cream font-medium py-3.5 rounded-[14px] text-sm tracking-wide shadow-md shadow-bake-brown/20 hover:bg-opacity-90 hover:-translate-y-0.5 transition-all">
                Vytvořit účet
            </button>
            <p class="text-center text-slate-400 text-sm mt-5 font-light">
                Už máte účet?
                <a href="<?= BASE_URL ?>/index.php?url=auth/login"
                   class="text-bake-blue font-medium hover:underline">Přihlaste se zde</a>
            </p>
        </div>
    </form>
</div>

<?php require_once '../app/views/layout/footer.php'; ?>