<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitní Databáze</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> 
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased h-screen flex overflow-hidden">

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col hidden md:flex shadow-2xl z-10 shrink-0">
        <div class="h-20 flex items-center px-8 border-b border-slate-800">
            <h1 class="text-2xl font-bold text-white tracking-wider">KNIHOVNA<span class="text-teal-500">.</span></h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl font-medium transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Veřejný katalog
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/index.php?url=book/create" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl font-medium transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Přidat záznam
                </a>

                <div class="pt-6 mt-6 border-t border-slate-800">
                    <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Přihlášen jako</p>
                    <div class="px-4 text-teal-400 font-medium mb-4"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Uživatel') ?></div>
                    
                    <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl font-medium transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Odhlásit se
                    </a>

                    <a href="<?= BASE_URL ?>/index.php?url=auth/deleteAccount" onclick="return confirm('Opravdu chcete TRVALE smazat svůj účet a přijít o přístup ke svým knihám? Tuto akci nelze vrátit zpět!')" class="flex items-center gap-3 px-4 py-3 mt-2 text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl font-medium transition-all border border-rose-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Smazat účet
                    </a>
                </div>

            <?php else: ?>
                <div class="pt-6 mt-6 border-t border-slate-800 space-y-2">
                    <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl font-medium transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Přihlásit se
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="flex items-center gap-3 px-4 py-3 bg-slate-800/50 hover:bg-slate-700 text-teal-400 hover:text-teal-300 rounded-xl font-medium transition-all border border-slate-700/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Registrace
                    </a>
                </div>
            <?php endif; ?>
        </nav>
        <div class="p-6 border-t border-slate-800 text-sm text-slate-500">
            &copy; WA 2026 Amálie Musilová
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-6 lg:p-12">

        <div class="max-w-4xl mx-auto mb-8">
            <?php if (isset($_SESSION['messages']) && !empty($_SESSION['messages'])): ?>
                <div class="space-y-3">
                    <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                        <?php 
                            // Stylování zachovávající náš "Calm Aesthetic" design
                            $styles = [
                                'success' => 'bg-teal-50 border-teal-500 text-teal-800',
                                'error'   => 'bg-rose-50 border-rose-500 text-rose-800',
                                'notice'  => 'bg-amber-50 border-amber-500 text-amber-800',
                            ];
                            $style = $styles[$type] ?? 'bg-slate-50 border-slate-500 text-slate-800';
                        ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="<?= $style ?> border-l-4 p-4 rounded-r-lg shadow-sm">
                                <p class="font-medium text-sm"><?= htmlspecialchars($message) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <?php unset($_SESSION['messages']); ?>
                </div>
            <?php endif; ?>
        </div>