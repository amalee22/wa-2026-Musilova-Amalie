<!DOCTYPE html>
<html lang='cs'>
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail knihy - <?= htmlspecialchars($book['title']) ?></title>
</head>
<body>
    <header> 
        <h1>Aplikace Knihovna</h1>
        <nav>
            <ul>    
                <li><a href="<?= BASE_URL ?>/index.php">&larr; Zpět na seznam knih</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Detail knihy</h2>

        <div style="border: 1px solid #ccc; padding: 20px; max-width: 600px; background: #f9f9f9;">
            <h3><?= htmlspecialchars($book['title']) ?></h3>
            <p><strong>Autor:</strong> <?= htmlspecialchars($book['author']) ?></p>
            <p><strong>Rok vydání:</strong> <?= htmlspecialchars($book['year']) ?></p>
            <p><strong>ISBN:</strong> <?= htmlspecialchars($book['isbn'] ?? 'Není uvedeno') ?></p>
            <p><strong>Kategorie:</strong> <?= htmlspecialchars($book['category'] ?? 'Není uvedeno') ?></p>
            <p><strong>Subkategorie:</strong> <?= htmlspecialchars($book['subcategory'] ?? 'Není uvedeno') ?></p>
            <p><strong>Cena:</strong> <?= htmlspecialchars($book['price'] ?? '0') ?> Kč</p>
            
            <?php if (!empty($book['link'])): ?>
                <p><strong>Odkaz:</strong> <a href="<?= htmlspecialchars($book['link']) ?>" target="_blank">Otevřít odkaz</a></p>
            <?php endif; ?>

            <hr>
            <h4>Popis:</h4>
            <p><?= nl2br(htmlspecialchars($book['description'] ?? 'Popis chybí.')) ?></p>
        </div>

        <br>
        <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>"><button>Upravit tuto knihu</button></a>
    </main>
</body>
</html>