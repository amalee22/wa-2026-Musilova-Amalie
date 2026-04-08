<?php

class App {
    // Výchozí nastavení, pokud uživatel přijde na hlavní stránku bez parametrů v URL
    protected $controller = 'BookController';
    protected $method = 'index'; // Výchozí metoda typicky zobrazuje seznam (např. seznam knih)
    protected $params = [];

    public function __construct() {
        // Získání a rozsekání URL adresy na jednotlivá slova
        $url = $this->parseUrl();

        // 1. KONTROLER: Existuje pro první část URL příslušný soubor?
        // Příklad: Pokud je URL "book/create", hledá se "BookController.php"
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]); // Odstranění použité části z pole
        }

        // Načtení souboru s kontrolerem a vytvoření jeho instance (objektu)
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 2. METODA: Existuje pro druhou část URL funkce uvnitř kontroleru?
        // Příklad: Pokud je URL "book/create", hledá se funkce "create()" uvnitř BookControlleru
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]); // Odstranění použité části z pole
            }
        }

        // 3. PARAMETRY: Vše, co v URL zbylo, se bere jako parametry (např. ID knihy)
        $this->params = $url ? array_values($url) : [];

        // FINÁLE: Spuštění vybrané metody ve vybraném kontroleru a předání parametrů
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Pomocná metoda pro bezpečné rozsekání URL adresy
    public function parseUrl() {
        if (isset($_GET['url'])) {
            // Odstraní lomítko na konci, vyčistí nebezpečné znaky a rozdělí text podle lomítek do pole
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}