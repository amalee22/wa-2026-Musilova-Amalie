<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Přidat do databáze</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased h-screen flex overflow-hidden">

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col hidden md:flex shadow-2xl z-10">
        <div class="h-20 flex items-center px-8 border-b border-slate-800">
            <h1 class="text-2xl font-bold text-white tracking-wider">KNIHOVNA<span class="text-teal-500">.</span></h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 text-slate-400 hover:text-white rounded-xl font-medium transition-all">
                &larr; Zpět do katalogu
            </a>
        </nav>
        <div class="p-6 border-t border-slate-800 text-sm text-slate-500">
            &copy; 2026 Komunitní databáze
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-8 lg:p-12">
        <div class="max-w-6xl mx-auto">
            
            <div class="flex flex-col lg:flex-row gap-12 lg:gap-24">
                
                <div class="lg:w-1/3 lg:sticky lg:top-12 h-fit">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-800 mb-4">Přidat záznam</h2>
                    <p class="text-slate-500 leading-relaxed mb-8">
                        Vyplňte pečlivě všechny detaily. Čím přesnější informace zadáte, tím snáze titul v naší veřejné databázi najdou ostatní čtenáři. Pole označená hvězdičkou jsou povinná.
                    </p>
                </div>

                <div class="lg:w-2/3">
                    <form action="<?= BASE_URL ?>/index.php?url=book/store" method="post" enctype="multipart/form-data" class="bg-white p-8 sm:p-10 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 space-y-8">
                        
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 mb-6 border-b border-slate-100 pb-2">Základní informace</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="sm:col-span-2">   
                                    <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Název titulu <span class="text-rose-500">*</span></label>
                                    <input type="text" id="title" name="title" required class="w-full bg-slate-50 border-0 text-slate-900 rounded-xl px-4 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-teal-500 focus:bg-white transition-all shadow-sm">
                                </div>
                                <div class="sm:col-span-2">   
                                    <label for="author" class="block text-sm font-medium text-slate-700 mb-2">Autor <span class="text-rose-500">*</span></label>
                                    <input type="text" id="author" name="author" required class="w-full bg-slate-50 border-0 text-slate-900 rounded-xl px-4 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-teal-500 focus:bg-white transition-all shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 mb-6 border-b border-slate-100 pb-2">Specifikace</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>   
                                    <label for="isbn" class="block text-sm font-medium text-slate-700 mb-2">ID / Kód platformy <span class="text-rose-500">*</span></label>
                                    <input type="text" id="isbn" name="isbn" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                                </div>
                                <div>   
                                    <label for="year" class="block text-sm font-medium text-slate-700 mb-2">Rok vydání <span class="text-rose-500">*</span></label>
                                    <input type="number" id="year" name="year" required class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                                </div>
                                <div>   
                                    <label for="category" class="block text-sm font-medium text-slate-700 mb-2">Žánr</label>
                                    <input type="text" id="category" name="category" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                                </div>
                                <div>   
                                    <label for="price" class="block text-sm font-medium text-slate-700 mb-2">Cena (Kč)</label>
                                    <input type="number" id="price" name="price" step="0.5" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Popis</label>
                            <textarea name="description" id="description" rows="5" class="w-full bg-slate-50 border-0 rounded-xl px-4 py-3 ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-teal-500 transition-all resize-y"></textarea>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="<?= BASE_URL ?>/index.php" class="text-slate-500 hover:text-slate-800 font-medium px-4 py-2 transition-colors">Zrušit</a>
                            <button type="submit" class="bg-teal-600 hover:bg-teal-500 text-white font-medium px-8 py-3 rounded-xl shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:-translate-y-0.5 transition-all">
                                Uložit do databáze
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>