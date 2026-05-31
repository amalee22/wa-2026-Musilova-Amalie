<?php require_once '../app/views/layout/header.php'; ?>

<style>
    /* ── Input icon group ─────────────────────────── */
    .input-group { position: relative; }
    .input-group .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #694A47;
        opacity: 0.35;
        font-size: 0.85rem;
        pointer-events: none;
        transition: opacity 0.2s;
    }
    .input-group input:focus ~ .input-icon,
    .input-group input:not(:placeholder-shown) ~ .input-icon {
        opacity: 0.7;
    }
    .input-field {
        width: 100%;
        background: rgba(255,240,222,0.35);
        border: none;
        color: #1e293b;
        border-radius: 0.875rem;
        padding: 0.875rem 1rem 0.875rem 2.75rem;
        box-shadow: inset 0 0 0 1.5px rgba(255,240,222,0.9);
        outline: none;
        font-size: 0.9rem;
        font-family: 'DM Sans', sans-serif;
        transition: box-shadow 0.2s ease, background 0.2s ease;
    }
    .input-field::placeholder { color: #94a3b8; }
    .input-field:focus {
        background: #fff;
        box-shadow: inset 0 0 0 2px #96C1C5, 0 0 0 4px rgba(150,193,197,0.15);
    }

    /* ── Toggle password ──────────────────────────── */
    .pw-toggle {
        position: absolute;
        right: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.8rem;
        padding: 4px;
        transition: color 0.2s;
    }
    .pw-toggle:hover { color: #694A47; }

    /* ── Left panel decoration ────────────────────── */
    .login-panel-left {
        position: relative;
    }
    .login-panel-left::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            repeating-linear-gradient(45deg,
                rgba(255,240,222,0.035) 0px, rgba(255,240,222,0.035) 1px,
                transparent 1px, transparent 8px),
            repeating-linear-gradient(-45deg,
                rgba(255,240,222,0.035) 0px, rgba(255,240,222,0.035) 1px,
                transparent 1px, transparent 8px);
        border-radius: inherit;
        pointer-events: none;
    }

    /* ── Floating orbs in left panel ─────────────── */
    .orb {
        position: absolute;
        border-radius: 9999px;
        filter: blur(40px);
        pointer-events: none;
    }

    /* ── Submit button ────────────────────────────── */
    .btn-submit {
        width: 100%;
        background: #694A47;
        color: #FFF0DE;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.925rem;
        padding: 0.9rem 1.5rem;
        border-radius: 0.875rem;
        border: none;
        cursor: pointer;
        letter-spacing: 0.02em;
        position: relative;
        overflow: hidden;
        transition: background 0.2s ease, transform 0.15s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 20px rgba(105,74,71,0.3);
    }
    .btn-submit::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0);
        transition: background 0.2s;
    }
    .btn-submit:hover {
        transform: translateY(-1.5px);
        box-shadow: 0 8px 28px rgba(105,74,71,0.35);
    }
    .btn-submit:hover::after { background: rgba(255,255,255,0.06); }
    .btn-submit:active { transform: translateY(0); }

    /* ── Card fade-in ─────────────────────────────── */
    .login-card {
        animation: loginFadeIn 0.45s cubic-bezier(0.4, 0, 0.2, 1) both;
    }
    @keyframes loginFadeIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<!-- Outer wrapper: vertically centres the card on the page -->
<div class="flex items-start justify-center py-8 px-4 min-h-[calc(100vh-180px)]">

    <div class="login-card w-full max-w-[860px] bg-white rounded-[2rem] shadow-2xl shadow-bake-brown/12 border border-bake-cream/60 overflow-hidden flex flex-col md:flex-row">

        <!-- ══ LEFT PANEL — brand / decoration ════════ -->
        <div class="login-panel-left bg-bake-brown flex flex-col justify-between p-10 md:p-12 md:w-[42%] shrink-0 relative overflow-hidden">

            <!-- Decorative orbs -->
            <div class="orb w-52 h-52 bg-bake-blue/20 -top-10 -right-10"></div>
            <div class="orb w-40 h-40 bg-bake-cream/10 bottom-16 -left-12"></div>

            <!-- Top: logo -->
            <div class="relative z-10">
                <a href="<?= BASE_URL ?>/index.php"
                   class="flex items-center gap-3 group w-fit mb-10">
                    <i class="fas fa-cookie-bite text-3xl text-bake-cream group-hover:text-bake-blue transition-colors duration-300"
                       style="transition: color .3s, transform .3s;"
                       onmouseover="this.style.transform='rotate(15deg)'"
                       onmouseout="this.style.transform='rotate(0deg)'"></i>
                    <span class="font-display text-2xl font-black tracking-tight text-bake-cream group-hover:text-bake-blue transition-colors duration-300">
                        Overbaked
                    </span>
                </a>

                <!-- Headline -->
                <h2 class="font-display text-4xl md:text-[2.6rem] font-black text-bake-cream leading-tight mb-4">
                    Vítejte<br>
                    <span class="text-bake-blue">zpátky.</span>
                </h2>
                <p class="text-bake-cream/55 text-sm leading-relaxed max-w-[220px]">
                    Přihlaste se a pokračujte ve vaší pekařské cestě.
                </p>
            </div>

            <!-- Middle: decorative icon grid -->
            <div class="relative z-10 grid grid-cols-4 gap-3 my-8 md:my-0">
                <?php
                    $icons = [
                        ['fas fa-cookie-bite', 'opacity-60'],
                        ['fas fa-birthday-cake', 'opacity-30'],
                        ['fas fa-bread-slice', 'opacity-50'],
                        ['fas fa-lemon', 'opacity-25'],
                        ['fas fa-mortar-pestle', 'opacity-30'],
                        ['fas fa-blender', 'opacity-50'],
                        ['fas fa-fire-alt', 'opacity-25'],
                        ['fas fa-weight', 'opacity-40'],
                    ];
                    foreach ($icons as $icon): ?>
                    <div class="w-11 h-11 rounded-2xl bg-bake-cream/6 border border-bake-cream/10 flex items-center justify-center">
                        <i class="<?= $icon[0] ?> text-bake-cream <?= $icon[1] ?> text-base"></i>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Bottom: register nudge -->
            <div class="relative z-10 mt-auto">
                <p class="text-bake-cream/40 text-xs leading-relaxed">
                    Ještě nemáte účet?
                </p>
                <a href="<?= BASE_URL ?>/index.php?url=auth/register"
                   class="inline-flex items-center gap-2 mt-2 text-bake-blue font-bold text-sm hover:text-bake-cream transition-colors duration-200">
                    Zaregistrovat se <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- ══ RIGHT PANEL — form ══════════════════════ -->
        <div class="flex flex-col justify-center px-8 sm:px-12 py-10 md:py-12 flex-1">

            <!-- Heading (visible on mobile only — desktop uses left panel) -->
            <div class="mb-8 md:mb-10">
                <h3 class="font-display text-3xl font-bold text-bake-brown mb-1">Přihlášení</h3>
                <p class="text-slate-400 text-sm">Zadejte své přihlašovací údaje níže.</p>
            </div>

            <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate"
                  method="post"
                  class="flex flex-col gap-5"
                  novalidate>

                <!-- E-mail -->
                <div>
                    <label for="email"
                           class="block text-xs font-bold text-bake-brown/70 uppercase tracking-wider mb-2">
                        E-mail
                    </label>
                    <div class="input-group">
                        <input type="email"
                               id="email"
                               name="email"
                               required
                               autofocus
                               placeholder="vas@email.cz"
                               autocomplete="email"
                               class="input-field">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Heslo -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password"
                               class="block text-xs font-bold text-bake-brown/70 uppercase tracking-wider">
                            Heslo
                        </label>
                        <!-- Forgot password placeholder — wire up if you add the feature -->
                        <span class="text-[11px] text-slate-400 cursor-default select-none">
                            <!-- <a href="#" class="text-bake-blue hover:underline font-medium">Zapomněli jste?</a> -->
                        </span>
                    </div>
                    <div class="input-group">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               placeholder="••••••••"
                               autocomplete="current-password"
                               class="input-field pr-10">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button"
                                class="pw-toggle"
                                onclick="togglePassword()"
                                aria-label="Zobrazit/skrýt heslo"
                                title="Zobrazit heslo">
                            <i id="pw-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit" class="btn-submit">
                        <span class="flex items-center justify-center gap-2.5">
                            <i class="fas fa-sign-in-alt text-sm"></i>
                            Přihlásit se
                        </span>
                    </button>
                </div>

                <!-- Register link (mobile / small screens) -->
                <p class="text-center text-slate-400 text-sm pt-1 md:hidden">
                    Nemáte účet?
                    <a href="<?= BASE_URL ?>/index.php?url=auth/register"
                       class="text-bake-blue font-bold hover:underline">
                        Zaregistrujte se
                    </a>
                </p>

            </form>

            <!-- Decorative bottom separator -->
            <div class="flex items-center gap-3 mt-10 opacity-30" aria-hidden="true">
                <div class="flex-1 h-px bg-bake-cream"></div>
                <i class="fas fa-cookie-bite text-bake-brown text-xs"></i>
                <div class="flex-1 h-px bg-bake-cream"></div>
            </div>

        </div>
        <!-- /right panel -->

    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('pw-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
</script>

<?php require_once '../app/views/layout/footer.php'; ?>