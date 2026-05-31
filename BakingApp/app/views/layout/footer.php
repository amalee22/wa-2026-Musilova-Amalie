</main>
<!-- /main closed here -->

<!-- ═══════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════ -->

<style>
    /* Reuse the same woven texture as the header */
    .footer-texture {
        position: relative;
    }
    .footer-texture::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            repeating-linear-gradient(
                45deg,
                rgba(255,240,222,0.03) 0px,
                rgba(255,240,222,0.03) 1px,
                transparent 1px,
                transparent 8px
            ),
            repeating-linear-gradient(
                -45deg,
                rgba(255,240,222,0.03) 0px,
                rgba(255,240,222,0.03) 1px,
                transparent 1px,
                transparent 8px
            );
        pointer-events: none;
    }

    /* Footer nav link hover */
    .footer-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        color: rgba(255,240,222,0.55);
        font-size: 0.875rem;
        font-weight: 500;
        transition: color 0.2s ease;
    }
    .footer-link:hover { color: #96C1C5; }
    .footer-link::before {
        content: '→';
        font-size: 0.7rem;
        opacity: 0;
        transform: translateX(-4px);
        transition: opacity 0.2s ease, transform 0.2s ease;
    }
    .footer-link:hover::before {
        opacity: 1;
        transform: translateX(0);
    }

    /* Back to top button */
    #back-to-top {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    #back-to-top:hover {
        transform: translateY(-3px);
    }

    /* Big faded wordmark */
    .footer-wordmark {
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        white-space: nowrap;
        font-family: 'Playfair Display', serif;
        font-weight: 900;
        font-size: clamp(60px, 10vw, 130px);
        color: rgba(255,240,222,0.04);
        pointer-events: none;
        user-select: none;
        letter-spacing: -0.04em;
        line-height: 1;
    }
</style>

<!-- Wave separator: bake-cream → bake-brown -->
<div class="overflow-hidden leading-none bg-bake-cream" aria-hidden="true">
    <svg viewBox="0 0 1440 64" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-12 md:h-16 block">
        <path d="M0,32 C180,64 360,0 540,32 C720,64 900,0 1080,32 C1260,64 1380,20 1440,32 L1440,64 L0,64 Z"
              fill="#694A47"/>
    </svg>
</div>

<footer class="footer-texture bg-bake-brown text-bake-cream relative overflow-hidden"
        role="contentinfo"
        aria-label="Zápatí stránky">

    <!-- Large decorative background wordmark -->
    <span class="footer-wordmark" aria-hidden="true">Overbaked</span>

    <!-- Thin top accent (mirrors header bottom accent) -->
    <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-bake-blue/30 to-transparent pointer-events-none"></div>

    <!-- ── MAIN FOOTER BODY ─────────────────────────── -->
    <div class="relative container mx-auto px-5 md:px-8 pt-14 pb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

            <!-- Col 1 · Brand ──────────────────────────── -->
            <div class="sm:col-span-2 lg:col-span-1 flex flex-col gap-5">

                <!-- Logo -->
                <a href="<?= BASE_URL ?>/index.php"
                   class="flex items-center gap-3 group w-fit">
                    <i class="fas fa-cookie-bite text-3xl text-bake-cream group-hover:text-bake-blue transition-colors duration-300 group-hover:rotate-[15deg] inline-block" style="transition: color 0.3s, transform 0.3s;"></i>
                    <span class="font-display text-2xl font-black tracking-tight text-bake-cream group-hover:text-bake-blue transition-colors duration-300">
                        Overbaked
                    </span>
                </a>

                <!-- Tagline -->
                <p class="font-display italic text-bake-cream/45 text-sm leading-relaxed">
                    "We may have gone<br>a little extra."
                </p>

                <!-- Short about -->
                <p class="text-bake-cream/50 text-sm leading-relaxed max-w-xs">
                    Komunitní sbírka receptů pro všechny, kdo berou pečení vážně — nebo alespoň s humorem.
                </p>

                <!-- Decorative row of icons -->
                <div class="flex items-center gap-3 pt-1" aria-hidden="true">
                    <i class="fas fa-cookie-bite text-bake-cream/20 text-lg"></i>
                    <i class="fas fa-birthday-cake text-bake-cream/15 text-base"></i>
                    <i class="fas fa-bread-slice text-bake-cream/20 text-lg"></i>
                    <i class="fas fa-mortar-pestle text-bake-cream/15 text-base"></i>
                    <i class="fas fa-lemon text-bake-cream/20 text-lg"></i>
                </div>
            </div>

            <!-- Col 2 · Procházet ──────────────────────── -->
            <div class="flex flex-col gap-4">
                <h3 class="font-display font-bold text-bake-cream text-base tracking-wide">
                    Procházet
                </h3>
                <div class="w-8 h-px bg-bake-blue/40 mb-1"></div>
                <nav aria-label="Sekce webu" class="flex flex-col gap-3">
                    <a href="<?= BASE_URL ?>/index.php"
                       class="footer-link">Katalog receptů</a>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/index&sort=latest"
                       class="footer-link">Nejnovější recepty</a>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/index&sort=time"
                       class="footer-link">Rychlovky</a>
                    <a href="<?= BASE_URL ?>/index.php?url=user/index"
                       class="footer-link">Pekaři</a>
                </nav>
            </div>

            <!-- Col 3 · Váš účet ──────────────────────── -->
            <div class="flex flex-col gap-4">
                <h3 class="font-display font-bold text-bake-cream text-base tracking-wide">
                    Váš účet
                </h3>
                <div class="w-8 h-px bg-bake-blue/40 mb-1"></div>
                <nav aria-label="Uživatelské akce" class="flex flex-col gap-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/index.php?url=recipe/create"
                           class="footer-link">Přidat recept</a>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/profile"
                           class="footer-link">Můj profil</a>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/logout"
                           class="footer-link">Odhlásit se</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/login"
                           class="footer-link">Přihlásit se</a>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/register"
                           class="footer-link">Vytvořit účet</a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Col 4 · O projektu ─────────────────────── -->
            <div class="flex flex-col gap-4">
                <h3 class="font-display font-bold text-bake-cream text-base tracking-wide">
                    O projektu
                </h3>
                <div class="w-8 h-px bg-bake-blue/40 mb-1"></div>
                <div class="flex flex-col gap-3 text-bake-cream/50 text-sm leading-relaxed">
                    <p>Semestrální projekt vytvořený v rámci předmětu <span class="text-bake-cream/70 font-medium">Webové aplikace</span>.</p>
                    <p>Postaveno na <span class="text-bake-cream/70 font-medium">PHP (MVC)</span> + <span class="text-bake-cream/70 font-medium">Tailwind CSS</span> + <span class="text-bake-cream/70 font-medium">MySQL</span>.</p>
                </div>

                <!-- Tech badges -->
                <div class="flex flex-wrap gap-2 pt-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-bake-cream/8 border border-bake-cream/15 text-bake-cream/50 px-2.5 py-1 rounded-full">PHP</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-bake-cream/8 border border-bake-cream/15 text-bake-cream/50 px-2.5 py-1 rounded-full">MySQL</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-bake-cream/8 border border-bake-cream/15 text-bake-cream/50 px-2.5 py-1 rounded-full">Tailwind</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-bake-cream/8 border border-bake-cream/15 text-bake-cream/50 px-2.5 py-1 rounded-full">MVC</span>
                </div>
            </div>

        </div>
    </div>

    <!-- ── BOTTOM BAR ──────────────────────────────────── -->
    <div class="relative border-t border-bake-cream/10">
        <div class="container mx-auto px-5 md:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">

            <!-- Copyright -->
            <p class="text-bake-cream/35 text-xs text-center sm:text-left">
                &copy; <?= date('Y') ?> <span class="font-semibold text-bake-cream/50">Overbaked</span>
                &nbsp;·&nbsp; Všechna práva vyhrazena
            </p>

            <!-- Center: decorative dividers -->
            <div class="hidden sm:flex items-center gap-2" aria-hidden="true">
                <span class="w-1 h-1 rounded-full bg-bake-blue/30"></span>
                <span class="w-1 h-1 rounded-full bg-bake-blue/20"></span>
                <span class="w-1 h-1 rounded-full bg-bake-blue/30"></span>
            </div>

            <!-- Back to top -->
            <button id="back-to-top"
                    onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="flex items-center gap-2 text-bake-cream/35 hover:text-bake-blue text-xs font-semibold uppercase tracking-wider transition-colors duration-200"
                    aria-label="Zpět nahoru">
                <i class="fas fa-arrow-up text-[10px]"></i>
                Zpět nahoru
            </button>
        </div>
    </div>

</footer>

<script>
    // ── Back to top: only show after scrolling ────────────
    const backToTopBtn = document.getElementById('back-to-top');
    if (backToTopBtn) {
        // Always visible in footer, no toggle needed — it's always in view when you reach it
        // Optional: add a floating back-to-top that appears after scroll
        const floatingBtn = document.createElement('button');
        floatingBtn.innerHTML = '<i class="fas fa-arrow-up text-sm"></i>';
        floatingBtn.setAttribute('aria-label', 'Zpět na začátek stránky');
        floatingBtn.className = [
            'fixed bottom-6 right-6 z-50',
            'w-11 h-11 rounded-2xl shadow-lg',
            'bg-bake-brown text-bake-cream',
            'flex items-center justify-center',
            'opacity-0 pointer-events-none',
            'transition-all duration-300',
            'hover:bg-bake-blue hover:text-bake-brown hover:-translate-y-1',
        ].join(' ');

        floatingBtn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
        document.body.appendChild(floatingBtn);

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                floatingBtn.style.opacity = '1';
                floatingBtn.style.pointerEvents = 'auto';
            } else {
                floatingBtn.style.opacity = '0';
                floatingBtn.style.pointerEvents = 'none';
            }
        }, { passive: true });
    }
</script>

</body>
</html>