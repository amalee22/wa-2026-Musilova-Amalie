<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <div>
            <h2> > Přidat novou knihu </h2>
            <p>Vyplňte údaje a uložte knihu do databáze.</p>
        </div>

        <div>
             <form action="">
                    <div>
                         <div>   
                                <label for="title"> Název knihy <span>*</span> </label>
                                <input type="text" id="title" name="title" required>
                         </div>
                         
                    </div>


                    <div>
                         <div>   
                                <label for="author"> Autor knihy <span>*</span> </label>
                                <input type="text" id="author" name="author" required>
                         </div>
                         
                    </div>


                    <div>
                         <div>   
                                <label for="category"> Kategorie knihy  </label>
                                <input type="text" id="category" name="category">
                         </div>
                        
                    </div>


                    <div>
                         <div>   
                                <label for="year"> Rok vydání knihy <span>*</span> </label>
                                <input type="number" id="year" name="year" required>
                         </div>
                         
                    </div>







                                        <div>
                                                    <button type="submit"> Uložit knihu do DB </button>
                                         </div>

             </form>
        </div>


    </div>
</body>
</html>