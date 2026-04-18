<?php 
/* PHP for all pages */
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/iconsController.php';

require_once __DIR__ . '/../models/Order.php';


$orderModel = new Order($mysqli);

date_default_timezone_set('America/Sao_Paulo');

// print the amount of products on the cart at header
function verifyCartAmount(){
    global $orderModel;

    if(isset($_SESSION["idOrder"])){
        $cartAmount = $orderModel->orderItemAmount($_SESSION["idOrder"]);
    
        $result = ($cartAmount != null) ? "<div class=\"cart-amount\">{$cartAmount}</div>" : "<div class=\"cart-amount\">0</div>";
        return $result;
    }
    return null;
}

function printProduct($productData, $page = "product", $priceType = "complete"){
    $name       = htmlspecialchars($productData['printName']);
    $link       = "productView.php?id=" . urlencode($productData['altName']);
    $imageURL   = htmlspecialchars($productData['image']);
    $brand      = htmlspecialchars($productData['brand']);
    $priceDate  = htmlspecialchars($productData['priceDate']);
    $price      = numfmt_format_currency(numfmt_create("pt-BR", NumberFormatter::CURRENCY), $productData['price'], "BRL");
    $id         = htmlspecialchars($productData['altName']);

    $priceComplement = "";

    if($page == "index"){
        $link = "products/productView.php?id={$productData['altName']}";
    }

    if($priceType == "complete"){
        $priceComplement = "A partir de: ";
    }

    echo "
        <div class='product hover' id='{$id}'>
            <img src='{$imageURL}' alt='{$name} Image'>
            <div class='content'>
                <div class='title'>
                    <p>{$brand}</p>
                    <abbr title='{$name}'><h1>{$name}</h1></abbr>
                </div>

                <div class='price'>{$priceComplement} <span class='value'>{$price}</span></div>

                <div class='others'>
                    <span>Atualizado em: <strong>{$priceDate}</strong></span>
                </div>

                <a class='regular-btn' href='{$link}'>Adicionar ao Carrinho</a>
            </div>
        </div>
    ";
}

// check the session expiration
function checkSessionStatus(){
    if(isset($_SESSION['lastActivity'])){
        $maxInactivity = 3600; // 1 hour
        $elapsed = time() - $_SESSION['lastActivity'];

        if($elapsed > $maxInactivity){ // logout with another message
            session_unset();
            session_destroy();

            redirectWithMessage("timeout", "/account/login.php", 0);
        }
    }
}

function logout(){
    session_unset();
    session_destroy();

    redirectWithMessage("logout", "/index.php", 1);
}

// remove orders that hasn't confirmed(Pendente/Pending) for more then 1 day
function verifyOrders(){
    global $orderModel;

    $orderModel->removeExpiredOrders();
}

function optionSelect($local, $option){
    // display if the option is "selected" or not at <option> using the special variable $_SESSION
    if(isset($_SESSION[$local]) && $_SESSION[$local] == $option)
        return " selected";
    return null;
}

function optionSelectAlt($row, $local, $option){
    // display if the option is "selected" or not at <option> using regular variables
    if(isset($row[$local]) && $row[$local] == $option)
        return " selected";
    return null;
}

function displayStateOptions(){
    $states = [
        ["label" => "Acre",                     "value" => "AC"],
        ["label" => "Alagoas",                  "value" => "AL"],
        ["label" => "Amapá",                    "value" => "AP"],
        ["label" => "Amazonas",                 "value" => "AM"],
        ["label" => "Bahia",                    "value" => "BA"],
        ["label" => "Ceará",                    "value" => "CE"],
        ["label" => "Distrito Federal",         "value" => "DF"],
        ["label" => "Espírito Santo",           "value" => "ES"],
        ["label" => "Goiás",                    "value" => "GO"],
        ["label" => "Maranhão",                 "value" => "MA"],
        ["label" => "Mato Grosso",              "value" => "MT"],
        ["label" => "Mato Grosso do Sul",       "value" => "MS"],
        ["label" => "Minas Gerais",             "value" => "MG"],
        ["label" => "Pará",                     "value" => "PA"],
        ["label" => "Paraíba",                  "value" => "PB"],
        ["label" => "Paraná",                   "value" => "PR"],
        ["label" => "Pernambuco",               "value" => "PE"],
        ["label" => "Piauí",                    "value" => "PI"],
        ["label" => "Rio de Janeiro",           "value" => "RJ"],
        ["label" => "Rio Grande do Norte",      "value" => "RN"],
        ["label" => "Rio Grande do Sul",        "value" => "RS"],
        ["label" => "Rondônia",                 "value" => "RO"],
        ["label" => "Roraima",                  "value" => "RR"],
        ["label" => "Santa Catarina",           "value" => "SC"],
        ["label" => "São Paulo",                "value" => "SP"],
        ["label" => "Sergipe",                  "value" => "SE"],
        ["label" => "Tocantins",                "value" => "TO"],
    ];

    foreach($states as $state){
        $selected = optionSelect("a_state", $state['value']) ?? "";
        echo "
            <option value='{$state['value']}' $selected >{$state['label']}</option>
        ";
    }
}

// set warning cookie value
function redirectWithMessage($value, $page, $type){
    setcookie("warning_message", $value, time() + 120, "/");
    setcookie("warning_type", $type, time() + 120, "/");
    
    header("Location: {$page}");
    exit();
}

// function to print the style file and the version at the correct page 
// everytime that you change something at the styles you are supose to change the version too at $version
function printStyle($fileName){
    $version = "?v=3.24";

    $directory = "/css/";

    $cssFile = match($fileName){
        "base"              => "base.css",
        "admin"             => "admin.css",
        "main"              => "main.css",
        "general"           => "general.css",
        "account"           => "account.css",
        "cart"              => "cart.css",
        "index"             => "index.css",
        "mannager"          => "mannager.css",
        "mannagerSettings"  => "mannager-settings.css",
        "products"          => "products.css",
        "productVersion"    => "productView.css",
        default             => "general.css",
    };

    echo "{$directory}{$cssFile}{$version}";
}