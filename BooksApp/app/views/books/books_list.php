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
                                 <li><a href="index.php"> Seznam knih (domů)</a> </li>
                                 <li><a href="index.php?url=book/create">Přidat novou knihu</a></li>
                        </ul>
                </nav>
        </header>




        <main>
                <h2>Dostupné knihy</h2>

                <?php if (!empty($books)): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Autor</th>
                    <th>Rok</th>
                    <th>ISBN</th>
                    <th>Cena</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author']) ?></td>
                        <td><?= htmlspecialchars($book['year']) ?></td>
                        <td><?= htmlspecialchars($book['isbn']) ?></td>
                        <td><?= htmlspecialchars($book['price']) ?> Kč</td>
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