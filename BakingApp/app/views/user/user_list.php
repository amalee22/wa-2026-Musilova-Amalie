<?php require_once '../app/views/layout/header.php'; ?>

<style>
    /* ── User card avatar ring animation ─────────── */
    .user-card-avatar {
        transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
    }
    .user-card:hover .user-card-avatar {
        background: #96C1C5;
        box-shadow: 0 0 0 4px rgba(150,193,197,0.25), 0 8px 24px rgba(105,74,71,0.12);
        transform: scale(1.06);
    }
    .user-card:hover .user-card-avatar .avatar-initial {
        color: #694A47;
    }

    /* ── Arrow appear on hover ───────────────────── */
    .card-arrow {
        opacity: 0;
        transform: translateX(-6px);
        transition: opacity 0.25s ease, transform 0.25s ease;
    }
    .user-card:hover .card-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    /* ── Card lift ────────────────────────────────── */
    .user-card {
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .user-card:hover {
        box-shadow: 0 16px 40px rgba(105,74,71,0.10);
        transform: translateY(-4px);
    }

    /* ── Page fade in ─────────────────────────────── */
    .page-enter { animation: fadeUp 0.4s ease both; }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Staggered card entrance ──────────────────── */
    .user-card { opacity: 0; animation: cardIn 0.4s ease forwards; }
    @keyframes cardIn {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="page-enter space-y-12">

    <!-- ── PAGE HEADER ────────────────────────────── -->
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">

        <!-- Title block -->
        <div>
            <div class="flex items-center gap-4 mb-3">
                <div class="h-px w-10 bg-gradient-to-r from-transparent to-bake-brown/30"></div>
                <i class="fas fa-cookie-bite text-bake-brown/30 text-xs"></i>
            </div>
            <h1 class="font-display text-5xl font-black text-bake-brown tracking-tight leading-none mb-2">
                Pekaři
            </h1>
            <p class="font-display italic text-slate-400 text-lg">
                Komunita, která miluje mouku, máslo a trpělivost.
            </p>
        </div>

        <!-- Search bar -->
        <form method="GET" action="<?= BASE_URL ?>/index.php"
              class="w-full lg:w-auto flex items-center bg-white rounded-2xl border border-slate-100 shadow-sm p-1 gap-1.5 focus-within:ring-2 focus-within:ring-bake-brown/15 focus-within:border-bake-brown/25 transition-all">
            <input type="hidden" name="url" value="user/index">
            <i class="fas fa-search text-slate-300 text-xs pl-3 shrink-0"></i>
            <input type="text"
                   name="q"
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                   placeholder="Hledat pekaře…"
                   class="bg-transparent border-none px-3 py-2 text-sm focus:ring-0 outline-none w-full lg:w-64 text-slate-700 font-medium placeholder-slate-300">
            <button type="submit"
                    class="bg-bake-brown text-bake-cream w-9 h-9 rounded-xl flex items-center justify-center hover:opacity-80 transition shrink-0">
                <i class="fas fa-arrow-right text-xs"></i>
            </button>
        </form>
    </div>

    <!-- ── USER GRID ───────────────────────────────── -->
    <?php if (!empty($users)): ?>

        <!-- Result count if searching -->
        <?php if (!empty($_GET['q'])): ?>
            <p class="text-sm text-slate-400 -mt-4 font-medium">
                Výsledky pro <span class="text-bake-brown font-semibold">"<?= htmlspecialchars($_GET['q']) ?>"</span>
                — <?= count($users) ?> <?= count($users) === 1 ? 'pekař' : (count($users) < 5 ? 'pekaři' : 'pekařů') ?>
            </p>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php foreach ($users as $i => $u): ?>
                <?php $displayName = htmlspecialchars($u['nickname'] ?: $u['username']); ?>

                <a href="<?= BASE_URL ?>/index.php?url=user/show/<?= $u['id'] ?>"
                   class="user-card bg-white rounded-[22px] border border-slate-100 p-7 flex flex-col items-center text-center relative overflow-hidden group"
                   style="animation-delay: <?= $i * 40 ?>ms">

                    <!-- Subtle warm bg blob on hover -->
                    <div class="absolute inset-0 bg-gradient-to-br from-bake-cream/0 to-bake-cream/0 group-hover:from-bake-cream/20 group-hover:to-bake-blue/5 transition-all duration-500 rounded-[22px]"></div>

                    <!-- Avatar -->
                    <div class="user-card-avatar relative z-10 w-20 h-20 bg-bake-blue/20 rounded-full flex items-center justify-center mb-5 border-2 border-bake-cream shadow-sm">
                        <span class="avatar-initial font-display text-3xl font-bold text-bake-brown transition-colors duration-300">
                            <?= strtoupper(mb_substr($u['nickname'] ?: $u['username'], 0, 1)) ?>
                        </span>
                    </div>

                    <!-- Name -->
                    <h3 class="relative z-10 font-display text-xl font-bold text-bake-brown mb-1 leading-tight group-hover:text-bake-blue transition-colors duration-200">
                        <?= $displayName ?>
                    </h3>

                    <!-- Real name -->
                    <?php if ($u['first_name'] || $u['last_name']): ?>
                        <p class="relative z-10 text-xs text-slate-400 font-medium mb-4">
                            <?= htmlspecialchars(trim($u['first_name'] . ' ' . $u['last_name'])) ?>
                        </p>
                    <?php else: ?>
                        <div class="mb-4"></div>
                    <?php endif; ?>

                    <!-- "View profile" label with animated arrow -->
                    <div class="relative z-10 flex items-center gap-1.5 text-bake-blue text-xs font-bold uppercase tracking-wider mt-auto">
                        <span>Zobrazit profil</span>
                        <i class="fas fa-arrow-right card-arrow text-[10px]"></i>
                    </div>

                    <!-- Decorative corner icon -->
                    <i class="fas fa-cookie-bite absolute bottom-3 right-4 text-bake-brown/5 text-4xl rotate-12 group-hover:text-bake-brown/8 transition-colors duration-500 z-0" aria-hidden="true"></i>

                </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>

        <!-- Empty state -->
        <div class="flex flex-col items-center justify-center py-28">
            <div class="w-24 h-24 rounded-full bg-bake-cream/40 border border-bake-cream flex items-center justify-center mb-6">
                <i class="fas fa-users text-3xl text-bake-brown/20"></i>
            </div>
            <p class="font-display italic text-slate-400 text-xl mb-1">
                <?= !empty($_GET['q']) ? 'Žádný pekař s tímto jménem nebyl nalezen.' : 'Zatím tu žádní pekaři nejsou.' ?>
            </p>
            <?php if (!empty($_GET['q'])): ?>
                <a href="<?= BASE_URL ?>/index.php?url=user/index"
                   class="text-bake-brown text-sm font-semibold hover:underline mt-4">
                    ← Zobrazit všechny pekaře
                </a>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>

<?php require_once '../app/views/layout/footer.php'; ?>