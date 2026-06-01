<?php
$currentUrl = $_GET['url'] ?? 'recipe/index';
$isHomePage = ($currentUrl === 'recipe/index' || $currentUrl === '');
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overbaked — Semestrální projekt</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;0,900;1,400;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bake-brown': '#694A47',
                        'bake-blue':  '#96C1C5',
                        'bake-cream': '#FFF0DE',
                    },
                    fontFamily: {
                        'sans':    ['"DM Sans"', 'sans-serif'],
                        'display': ['"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* ─── Global base ─────────────────────────────── */
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }

        /* ─── Header texture overlay ──────────────────── */
        .header-texture {
            position: relative;
        }
        .header-texture::before {
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

        /* ─── Nav link underline animation ───────────── */
        .nav-link {
            position: relative;
            padding-bottom: 2px;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1.5px;
            background-color: #96C1C5;
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link:hover::after { width: 100%; }
        .nav-link:hover { color: #96C1C5; }

        /* ─── Mobile menu slide ───────────────────────── */
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.25s ease;
            opacity: 0;
        }
        #mobile-menu.open {
            max-height: 480px;
            opacity: 1;
        }

        /* ─── Flash message fade-out ──────────────────── */
        .flash-msg { transition: opacity 0.5s ease, transform 0.5s ease; }
        .flash-msg.hiding {
            opacity: 0;
            transform: translateY(-6px);
        }

        /* ─── Scrollbar hide utility ──────────────────── */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-bake-cream min-h-screen flex flex-col text-slate-800">

<header class="header-texture bg-bake-brown text-bake-cream sticky top-0 z-50 flex flex-col <?= $isHomePage ? '' : 'shadow-sm' ?>">

    <div class="relative z-30 bg-bake-brown">
        <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-bake-blue/40 to-transparent pointer-events-none"></div>

        <div class="container mx-auto px-5 md:px-8 py-5 flex justify-between items-center gap-6">

            <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-4 group shrink-0 select-none">
                <div class="relative">
                    <i class="fas fa-cookie-bite text-4xl text-bake-cream group-hover:text-bake-blue transition-colors duration-300 group-hover:rotate-[15deg] inline-block transition-transform"></i>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="font-display text-3xl md:text-4xl font-black tracking-tighter text-bake-cream group-hover:text-bake-blue transition-colors duration-300">
                        Overbaked
                    </span>
                    <span class="font-display italic text-[11px] text-bake-cream/50 tracking-[0.18em] uppercase mt-0.5 hidden sm:block">
                        We may have gone a little extra
                    </span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-7 font-medium">
                <a href="<?= BASE_URL ?>/index.php" class="nav-link text-bake-cream/85 hover:text-bake-blue text-[15px] tracking-wide transition-colors duration-200">Recepty</a>
                <a href="<?= BASE_URL ?>/index.php?url=user/index" class="nav-link text-bake-cream/85 hover:text-bake-blue text-[15px] tracking-wide transition-colors duration-200">Pekaři</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/index.php?url=recipe/create" class="flex items-center gap-2 bg-bake-blue text-bake-brown font-bold text-sm px-5 py-2.5 rounded-2xl hover:bg-bake-cream transition-colors duration-200 shadow-md shadow-black/10 ml-1"><i class="fas fa-plus text-xs"></i> Přidat recept</a>
                    <div class="w-px h-6 bg-bake-cream/15 mx-1"></div>
                    <div class="flex items-center gap-3">
                        <a href="<?= BASE_URL ?>/index.php?url=auth/profile" class="flex items-center gap-2.5 group/user">
                            <div class="w-9 h-9 rounded-full bg-bake-blue/30 border border-bake-blue/50 flex items-center justify-center text-bake-blue font-bold font-display text-base shrink-0 group-hover/user:bg-bake-blue group-hover/user:text-bake-brown transition-all duration-200"><?= strtoupper(mb_substr($_SESSION['user_name'] ?? 'P', 0, 1)) ?></div>
                            <div class="flex flex-col leading-none">
                                <span class="text-bake-cream/60 text-[10px] uppercase tracking-wider font-semibold">Ahoj,</span>
                                <span class="text-bake-blue font-bold text-sm group-hover/user:text-bake-cream transition-colors duration-200"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pekaři') ?></span>
                            </div>
                            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                <span class="bg-bake-blue/20 border border-bake-blue/40 text-bake-blue text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">Admin</span>
                            <?php endif; ?>
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="text-bake-cream/40 hover:text-bake-cream/80 transition-colors duration-200 text-xs font-medium" title="Odhlásit se"><i class="fas fa-sign-out-alt text-base"></i></a>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-3 ml-1">
                        <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="nav-link text-bake-cream/85 hover:text-bake-blue text-[15px] tracking-wide transition-colors duration-200">Přihlásit se</a>
                        <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="border border-bake-blue/60 text-bake-blue hover:bg-bake-blue hover:text-bake-brown px-5 py-2 rounded-2xl transition-all duration-200 font-bold text-sm">Registrace</a>
                    </div>
                <?php endif; ?>
            </nav>

            <button id="hamburger" onclick="toggleMobileMenu()" class="md:hidden flex flex-col gap-[5px] p-2 rounded-xl hover:bg-bake-cream/10 transition" aria-label="Otevřít menu">
                <span class="hamburger-line block w-6 h-[2px] bg-bake-cream rounded-full transition-all duration-300"></span>
                <span class="hamburger-line block w-6 h-[2px] bg-bake-cream rounded-full transition-all duration-300"></span>
                <span class="hamburger-line block w-4 h-[2px] bg-bake-cream rounded-full transition-all duration-300"></span>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="md:hidden border-t border-bake-cream/10 relative z-20 bg-bake-brown">
        <nav class="container mx-auto px-5 py-5 flex flex-col gap-1">
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-bake-cream/80 hover:text-bake-cream hover:bg-bake-cream/10 transition font-medium text-[15px]"><i class="fas fa-book-open w-5 text-center text-bake-blue/70"></i> Recepty</a>
            <a href="<?= BASE_URL ?>/index.php?url=user/index" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-bake-cream/80 hover:text-bake-cream hover:bg-bake-cream/10 transition font-medium text-[15px]"><i class="fas fa-users w-5 text-center text-bake-blue/70"></i> Pekaři</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="h-px bg-bake-cream/10 my-2"></div>
                <a href="<?= BASE_URL ?>/index.php?url=recipe/create" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-bake-blue/20 text-bake-blue hover:bg-bake-blue hover:text-bake-brown transition font-bold text-[15px]"><i class="fas fa-plus w-5 text-center"></i> Přidat recept</a>
                <a href="<?= BASE_URL ?>/index.php?url=auth/profile" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-bake-cream/80 hover:text-bake-cream hover:bg-bake-cream/10 transition font-medium text-[15px]"><i class="fas fa-user-circle w-5 text-center text-bake-blue/70"></i> Profil · <strong class="text-bake-blue"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pekaři') ?></strong></a>
                <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-bake-cream/50 hover:text-bake-cream/80 hover:bg-bake-cream/10 transition font-medium text-sm"><i class="fas fa-sign-out-alt w-5 text-center"></i> Odhlásit se</a>
            <?php else: ?>
                <div class="h-px bg-bake-cream/10 my-2"></div>
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-bake-cream/80 hover:text-bake-cream hover:bg-bake-cream/10 transition font-medium text-[15px]"><i class="fas fa-sign-in-alt w-5 text-center text-bake-blue/70"></i> Přihlásit se</a>
                <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-bake-blue/40 text-bake-blue hover:bg-bake-blue hover:text-bake-brown transition font-bold text-[15px]"><i class="fas fa-user-plus w-5 text-center"></i> Registrace</a>
            <?php endif; ?>
        </nav>
    </div>

    <?php if ($isHomePage): ?>
        <div class="bg-bake-brown relative z-20">
            <div class="container mx-auto px-5 md:px-8 py-3.5 flex items-center justify-between gap-4">
                <nav class="flex items-center gap-1 bg-bake-cream/10 p-1 rounded-2xl border border-bake-cream/15">
                    <button id="btn-tab-recipes" onclick="switchTab('tab-recipes')" class="tab-btn tab-btn-active flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200"><i class="fas fa-book-open text-[13px]"></i><span class="hidden sm:inline">Katalog</span></button>
                    <button id="btn-tab-tips" onclick="switchTab('tab-tips')" class="tab-btn tab-btn-inactive flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200"><i class="fas fa-magic text-[13px]"></i><span class="hidden sm:inline">Tipy &amp; Triky</span></button>
                    <button id="btn-tab-qa" onclick="switchTab('tab-qa')" class="tab-btn tab-btn-inactive flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold transition-all duration-200"><i class="far fa-comments text-[13px]"></i><span class="hidden sm:inline">Komunita</span></button>
                </nav>
                <form method="GET" action="<?= BASE_URL ?>/index.php" class="w-full xl:w-auto flex items-center">
                    <input type="hidden" name="url" value="recipe/index">
                    <div class="flex items-center w-full xl:w-[420px] bg-bake-cream/10 rounded-2xl border border-bake-cream/15 px-1.5 py-1 gap-1.5 focus-within:ring-2 focus-within:ring-bake-blue/50 focus-within:border-bake-blue/30 transition-all">
                        <select name="sort" onchange="this.form.submit()" class="bg-bake-cream/15 border-none text-[11px] font-bold text-bake-cream pl-3 pr-2 py-2 focus:ring-0 outline-none cursor-pointer uppercase tracking-wider appearance-none rounded-xl shrink-0">
    <option class="text-bake-brown" value="latest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'latest') ? 'selected' : '' ?>>Nejnovější</option>
    <option class="text-bake-brown" value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : '' ?>>Nejstarší</option>
    <option class="text-bake-brown" value="time"   <?= (isset($_GET['sort']) && $_GET['sort'] == 'time')   ? 'selected' : '' ?>>Rychlovky</option>
</select>
                        <div class="w-px h-4 bg-bake-cream/20 mx-0.5 shrink-0"></div>
                        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Hledat recept…" class="bg-transparent border-none px-3 py-2 text-sm focus:ring-0 outline-none w-full text-bake-cream font-medium placeholder:text-bake-cream/35">
                        <button type="submit" class="bg-bake-blue text-bake-brown w-9 h-9 rounded-xl flex items-center justify-center hover:bg-bake-cream hover:text-bake-brown transition shrink-0"><i class="fas fa-search text-xs"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="absolute top-full left-0 w-full overflow-hidden leading-none z-10 pointer-events-none" aria-hidden="true">
            <svg viewBox="0 0 1440 64" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-12 md:h-16 block drop-shadow-md">
                <path d="M0,32 C180,64 360,0 540,32 C720,64 900,0 1080,32 C1260,64 1380,20 1440,32 L1440,0 L0,0 Z" fill="#694A47"/>
            </svg>
        </div>
    <?php endif; ?>

</header>

<main class="container mx-auto px-5 md:px-8 py-10 flex-grow">

    <?php if (isset($_SESSION['messages'])): ?>
        <div id="flash-messages" class="mb-8 space-y-3">
            <?php foreach ($_SESSION['messages'] as $type => $msgs): ?>
                <?php foreach ($msgs as $msg): ?>
                    <div class="flash-msg flex items-center justify-between gap-4 px-5 py-4 rounded-2xl shadow-sm border-l-4
                                <?= $type === 'success'
                                    ? 'bg-green-50  text-green-800  border-green-400'
                                    : 'bg-red-50    text-red-800    border-red-400' ?>">
                        <div class="flex items-center gap-3">
                            <i class="<?= $type === 'success' ? 'fas fa-check-circle text-green-500' : 'fas fa-exclamation-circle text-red-500' ?> text-lg shrink-0"></i>
                            <span class="font-medium text-sm"><?= htmlspecialchars($msg) ?></span>
                        </div>
                        <button onclick="dismissFlash(this)"
                                class="opacity-40 hover:opacity-100 transition text-xl leading-none font-bold shrink-0">
                            &times;
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php unset($_SESSION['messages']); ?>
        </div>

        <script>
            function dismissFlash(btn) {
                const msg = btn.closest('.flash-msg');
                msg.classList.add('hiding');
                setTimeout(() => msg.style.display = 'none', 500);
            }

            setTimeout(() => {
                document.querySelectorAll('.flash-msg').forEach(msg => {
                    msg.classList.add('hiding');
                    setTimeout(() => msg.style.display = 'none', 500);
                });
            }, 5000);
        </script>
    <?php endif; ?>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const lines = document.querySelectorAll('.hamburger-line');

        menu.classList.toggle('open');

        if (menu.classList.contains('open')) {
            lines[0].style.transform = 'translateY(7px) rotate(45deg)';
            lines[1].style.opacity   = '0';
            lines[2].style.transform = 'translateY(-7px) rotate(-45deg)';
            lines[2].style.width     = '24px';
        } else {
            lines[0].style.transform = '';
            lines[1].style.opacity   = '';
            lines[2].style.transform = '';
            lines[2].style.width     = '';
        }
    }

    document.addEventListener('click', (e) => {
        const menu = document.getElementById('mobile-menu');
        const btn  = document.getElementById('hamburger');
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.remove('open');
            document.querySelectorAll('.hamburger-line').forEach(l => {
                l.style.transform = '';
                l.style.opacity   = '';
                l.style.width     = '';
            });
        }
    });
</script>