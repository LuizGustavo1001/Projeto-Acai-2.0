<?php
require_once "icons.php";

date_default_timezone_set('America/Sao_Paulo');

if(! isset($_SESSION)){ 
    session_start(); 
}

if(isset($_SESSION["passwordToken"])){ 
    unset($_SESSION["passwordToken"]); 
}

function getProductByName($prodName, $page): void{
    // print the product data with the $prodName above
    global $mysqli;

    // mapping the allowed product names
    $getAllNames = $mysqli->query("SELECT altName FROM product_data") or die("var getAllNames (generalPHP.php):" . $mysqli->errno);
    $allowedNames = [];
    while($allNames = $getAllNames->fetch_assoc()){ $allowedNames[] = $allNames["altName"]; }

    $getAllNames->close();

    if(in_array($prodName, $allowedNames)){
        $getProducts = $mysqli->prepare("
            SELECT pd.idProduct, pd.printName, pd.altName, pd.brandProduct,
                pv.imageURL, pv.priceProduct, pv.priceDate
            FROM product_data pd
            JOIN product_version pv 
                ON pv.idProduct = pd.idProduct
            WHERE pd.altName = ?
            AND pv.priceProduct = (
                SELECT MIN(pv2.priceProduct)
                FROM product_version pv2
                WHERE pv2.idProduct = pd.idProduct
            )
        ") or die("var getProducts (generalPHP.php):" . $mysqli->errno);
        $getProducts->bind_param("s", $prodName);

        $getProducts->execute();
        $products = $getProducts->get_result();
        $getProducts->close();

        if($products->num_rows > 0){
            $row            = $products->fetch_assoc();
            $name           = $row["printName"];
            $link           = "productView.php?id={$row['altName']}";
            $imageURL       = htmlspecialchars          ($row['imageURL']);
            $brand          = htmlspecialchars          ($row['brandProduct']);
            $priceDate      = htmlspecialchars          ($row['priceDate']);
            $price          = numfmt_format_currency    (numfmt_create("pt-BR", NumberFormatter::CURRENCY), $row['priceProduct'], "BRL");

            if($page == "index"){
                $link = "products/productView.php?id={$row['altName']}";
            }

            echo "
                <div class='product hover' id='{$prodName}'>
                    <img src='{$imageURL}' alt='{$name} Image'>
                    <div class='content'>
                        <div class='title'>
                            <p>{$brand}</p>
                            <abbr title='{$name}'><h1>{$name}</h1></abbr>
                        </div>

                        <div class='price'>A partir de: <span class='value'>{$price}</span></div>

                        <div class='others'>
                            <span>Atualizado em: <strong>{$priceDate}</strong></span>
                        </div>

                        <a class='regular-btn' href='{$link}'>Adicionar ao Carrinho</a>
                    </div>
                </div>
            ";
        }
    }else{
    echo "
        <div class='product' id='{$prodName}'>
            <p><em>Nenhum produto encontrado com o nome selecionado -> <strong>{$prodName}</strong></em></p>
        </div>
        ";
    }
}

    // check the session expiration
function checkSession(){
    if(isset($_SESSION['lastActivity'])){
        $maxInactivity = 3600; // 1 hour
        $elapsed = time() - $_SESSION['lastActivity'];

        if($elapsed > $maxInactivity){ // logout with another message
            session_unset();
            session_destroy();

            setCookies("timeout", "/index.php", 0);
        }
    }
}

function logout(){
    session_unset();
    session_destroy();

    setCookies("logout", "/index.php", 1);
}

// remove orders that hasn't confirmed(Pendente/Pending) for more then 1 day
function verifyOrders(){
    global $mysqli;

    $getOrders = $mysqli->prepare("
        SELECT idOrder
        FROM order_data AS od
        WHERE 
            od.orderDate < NOW() - INTERVAL 1 DAY
            AND od.orderStatus <> 'Pendente'
            AND NOT EXISTS (
                SELECT 1 FROM product_order WHERE idOrder = od.idOrder
            );
    ") or die($mysqli->errno);

    $getOrders->execute();
    $result = $getOrders->get_result();
    
    while($row = $result->fetch_assoc()){
        $idOrder = $row["idOrder"];

        $deleteOrder = $mysqli->prepare(query: "DELETE FROM order_data WHERE idOrder = ?") or die($mysqli->errno);

        $deleteOrder->bind_param("i", $idOrder);
        $deleteOrder->execute();
        $deleteOrder->close();
    }
    $getOrders->close();
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

// set warning cookie value
function setCookies($value, $page, $type){
    setcookie("warning_message", $value, time() + 20, "/");
    setcookie("warning_type", $type, time() + 20, "/");
    
    header("Location: {$page}");
    exit();
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
        $selected = optionSelect("state", $state['value']) ?? "";
        echo "
            <option value='{$state['value']}' $selected >{$state['label']}</option>
        ";
    }
}