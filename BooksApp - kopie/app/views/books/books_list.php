<!DOCTYPE html>

<html lang='cs'>

<head>  
        <meta charset="UTF-8">
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0">

        <title> Knihovna - Seznam knih </title>
</head>


<body>
        <header> 
                <h1>Aplikace Knihovna</h1>

               <nav>
                              <ul>    
                                     <li><a href="<?= BASE_URL ?>/index.php">Seznam knih (Domů)</a></li>
                                     <li><a href="<?= BASE_URL ?>/index.php?url=book/create">Přidat novou knihu</a></li>
                            </ul>
                </nav>
        </header>




        <main>
                <h2>Dostupné knihy</h2>


<?php if (isset($_SESSION['success_msg'])): ?>
                    <div style="background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; margin-bottom: 15px;">
                        <?= htmlspecialchars($_SESSION['success_msg']) ?>
                    </div>
                    <?php unset($_SESSION['success_msg']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_msg'])): ?>
                    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 15px;">
                        <?= htmlspecialchars($_SESSION['error_msg']) ?>
                    </div>
                    <?php unset($_SESSION['error_msg']); ?>
                <?php endif; ?>



                
                <?php if (!empty($books)): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Autor</th>
                    <th>Rok</th>
                    <th>ISBN</th>
                    <th>Cena</th>
                    <th>Akce</th> </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['year']) ?></td>
                        <td><?= htmlspecialchars($book['isbn']) ?></td>
                        <td><?= htmlspecialchars($book['price']) ?> Kč</td>
                        <td>
                            <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>">Detail</a> | 
                            <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>">Upravit</a> | 
                            <a href="<?= BASE_URL ?>/index.php?url=book/delete/<?= $book['id'] ?>" onclick="return confirm('Opravdu chcete tuto knihu smazat?')">Smazat</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Zatím tu nejsou žádné knihy. Zkuste nějakou přidat!</p>
    <?php endif; ?>

            
        </main>

        <footer>
            <p>&copy; WA 2026 - Výukový projekt</p>
        </footer>


</body>



</html>