<?php
$name = "";
$message = "";
$age = 0;


if($_SERVER["REQUEST_METHOD"] == "POST") {
    
$name = $_POST["name"];

if($name == "Amy"){
    $message = "Čauky Amy :)";
    $age = $_POST["age"];
} 
else {
    $message = "Neznám tě!>:(";
}

                                        }

?>



<!--html struktura-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<h1> Test formuláře </h1> 
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, libero dolorum! Sint quae quibusdam cupiditate amet hic pariatur error nihil ad minus mollitia dolor libero explicabo laudantium quam, culpa eaque</p>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ad quisquam harum qui dolorem placeat vero, ratione exercitationem autem fugit at, distinctio nihil aliquam impedit rem minus doloribus dolor laboriosam corporis.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Porro, aliquid ipsam? Odit libero beatae architecto esse sed magnam earum? Accusamus cum veritatis soluta exercitationem non voluptatem repellat laboriosam aut sapiente?</p>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus odit vel quam voluptate magni, possimus exercitationem! Cumque ad repudiandae debitis itaque aspernatur aliquam provident libero sit sunt consectetur, consequuntur obcaecati.</p>

<form method="post">
    <input type="text" name="name" placeholder="Zadejte jméno" >

     <input  type="number" name="age" placeholder="Zadejte věk">


    <button type"submit">Odeslat</button>
</form>







<p>

<?php  
        echo "výstup:  ";
        echo $message;  
        echo ",  ";
        echo "tvůj věk:  ";
        echo $age    ;
 ?>

</p>




</body>
</html>