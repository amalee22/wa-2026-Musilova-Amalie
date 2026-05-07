<?php
session_start();

//pro účely výuky a ladění na lokálním serveru (např. XAMPP)
//je vhodné zapnout kompletní zobrazování chyb 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//dynamické zjištění základní adresy aplikace
$baseDir = str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', $baseDir); 
//echo($baseDir);


require_once '../core/app.php';
$app = new App();