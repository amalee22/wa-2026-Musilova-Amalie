<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overbaked — Semestrální projekt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bake-brown': '#694A47',
                        'bake-blue': '#96C1C5',
                        'bake-cream': '#FFF0DE',
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-bake-cream min-h-screen flex flex-col font-sans text-slate-800">

<header class="bg-bake-brown text-bake-cream shadow-lg">
    <div class="container mx-auto px-4 py-6 flex justify-between items-center">
        
        <a href="<?= BASE_URL ?>/index.php" class="flex items-center space-x-5 hover:text-bake-blue transition duration-300 group">
            <i class="fas fa-cookie-bite text-5xl group-hover:rotate-12 transition-transform"></i>
            <div class="flex flex-col">
                <span class="text-4xl font-black tracking-tighter leading-none mb-1">Overbaked</span>
                <span class="text-sm italic text-bake-cream/70 tracking-widest uppercase">"We may have gone a little extra."</span>
            </div>
        </a>

        <nav class="hidden md:flex space-x-8 items-center font-medium">
            <a href="<?= BASE_URL ?>/index.php" class="hover:text-bake-blue transition duration-300 text-lg">Recepty</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/index.php?url=recipe/create" class="bg-bake-blue text-bake-brown hover:bg-opacity-90 px-6 py-2.5 rounded-xl transition duration-300 font-bold shadow-md">
                    <i class="fas fa-plus mr-2"></i> Přidat recept
                </a>
                
                <div class="flex items-center space-x-4 ml-4 border-l border-bake-cream/20 pl-6">
                    <a href="<?= BASE_URL ?>/index.php?url=auth/profile" class="text-sm hover:opacity-80 transition group">
                        Ahoj, <strong class="text-bake-blue group-hover:underline"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Pekaři') ?></strong>
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="text-bake-cream/80 hover:text-white transition text-sm">Odhlásit</a>
                </div>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="hover:text-bake-blue transition duration-300 text-lg">Přihlásit se</a>
                <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="border-2 border-bake-blue text-bake-blue hover:bg-bake-blue hover:text-bake-brown px-6 py-2.5 rounded-xl transition duration-300 font-bold">Registrace</a>
            <?php endif; ?>
        </nav>

        <div class="md:hidden">
            <i class="fas fa-bars text-3xl cursor-pointer hover:text-bake-blue transition"></i>
        </div>
    </div>
</header>

<main class="container mx-auto px-4 py-10 flex-grow">
    <?php if (isset($_SESSION['messages'])): ?>
        <?php foreach ($_SESSION['messages'] as $type => $msgs): ?>
            <?php foreach ($msgs as $msg): ?>
                <div class="mb-6 p-5 rounded-2xl shadow-sm <?= $type === 'success' ? 'bg-green-100 text-green-800 border-l-4 border-green-500' : 'bg-red-100 text-red-800 border-l-4 border-red-500' ?>">
                    <?= htmlspecialchars($msg) ?>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <?php unset($_SESSION['messages']); ?>
    <?php endif; ?>