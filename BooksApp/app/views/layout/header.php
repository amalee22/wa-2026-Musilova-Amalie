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
            <a href="<?= BASE_URL ?>/index.php?url=book/create" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl font-medium transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Přidat záznam
            </a>
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