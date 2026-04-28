<?php 
require_once __DIR__ . '/../mainController.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../../config/composer/vendor/autoload.php';

use Dotenv\Dotenv;
use Google\Client;
use Google\Service\Sheets;

if (isset($_SESSION["isAdmin"])){ 
    redirectWithMessage("adminNotAllowed", "../mannager/admin.php", 0); 
}
if(! isset($_SESSION["mailUser"])){ 
    redirectWithMessage("unkUser", "../account/login.php", 0); 
}

checkSessionStatus();

$orderModel     = new Order($mysqli);
$productModel   = new Product($mysqli);

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../config/');
$dotenv->load();

if(isset($_GET["orderConfirmed"])){
    cartToSpreadsheet();
}

if(isset($_GET['removeId'])){
    removeFromCart($_GET['removeId']);
}

function getProductsAtCart(){
    global $orderModel, $productModel, $defaultMoney;

    $products = $orderModel->getProductsAtOrder($_SESSION['idOrder']);

    if(! $products){
        return False;
    }

    $productsData = [];

    foreach($products as $productAtOrder){
        $variantData = $productModel->getById($productAtOrder['idVariant'], "pv.idVariant");
        
        if($variantData){
            foreach($variantData as $variant){
                $name = "{$variant['printName']}";
                if($variant['flavor'] == null){
                    $name = "{$variant['printName']} - {$variant['size']}";
                }else{
                    $name = "{$variant['printName']} - {$variant['flavor']}";
                }

                $image      = htmlspecialchars($variant['image'], ENT_QUOTES, 'UTF-8');
                $name       = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                $amount     = $productAtOrder['amount'];
                $totalPrice = $productAtOrder['totalPrice'];
                $id         = htmlspecialchars($variant['idVariant'], ENT_QUOTES, 'UTF-8');

                $productsData[] = [
                    "image"         => $image,
                    "name"          => $name,
                    "amount"        => $amount,
                    "totalPrice"    => $totalPrice,
                    "id"            => $id
                ];
            }
        }
    }
    return $productsData;
}

// print all the products in the cart
function printProducts(){
    global $defaultMoney;
    $products = getProductsAtCart();

    if(! $products){
        echo "Nenhum produto adicionado ao carrinho ainda";
        return 0;
    }

    // print product
    $trashBin = getIcon("trash-bin");

    foreach($products as $product){
        $product['totalPrice'] = numfmt_format_currency($defaultMoney, $product['totalPrice'], "BRL");
        echo "
            <li>
                <div>
                    <img src='{$product['image']}' alt='product image'>
                    <span>
                        <p class='line-clamp'><strong>{$product['name']}</strong></p>
                        <p class='line-clamp'>Quantidade: <strong>{$product['amount']}</strong></p>
                        <p class='line-clamp'>Total: <strong>{$product['totalPrice']}</strong></p>
                    </span>
                </div>
                <a href='cart.php?removeId={$product['id']}' aria-label='Remover item do carrinho'> {$trashBin} </a>
            </li>
        ";
    }
}

function getSubTotal(){
    global $orderModel;

    $subTotal = $orderModel->getOrderSubtotal($_SESSION["idOrder"]);
    return $subTotal;
}

function displayClientInfo(){
    $street     = htmlspecialchars($_SESSION['a_street'], ENT_QUOTES, 'UTF-8');
    $numHouse   = htmlspecialchars($_SESSION['a_numHouse'], ENT_QUOTES, 'UTF-8');
    $district   = htmlspecialchars($_SESSION['a_district'], ENT_QUOTES, 'UTF-8');
    $city       = htmlspecialchars($_SESSION['a_city'], ENT_QUOTES, 'UTF-8');

    $phone      = htmlspecialchars($_SESSION['phoneUser'], ENT_QUOTES, 'UTF-8');
    $name       = htmlspecialchars($_SESSION['nameUser'], ENT_QUOTES, 'UTF-8');
    $address    = "
        {$street}, 
        {$numHouse}, 
        {$district} - 
        {$city}
    ";
    
    $iconBg = getIcon("iconBg");

    $map = [
        [
            "icon"  => getIcon("maps_outer"),
            "label" => "Cliente",
            "value" => $name,
        ],
        [
            "icon"  => getIcon("userOuter"),
            "label" => "Endereço",
            "value" => $address,
        ],
        [
            "icon"  => getIcon("phone"),
            "label" => "Telefone de Contato",
            "value" => $phone,
        ]
    ];

    foreach($map as $item){
        echo "
            <li>
                <div class='icon-wrapper'>
                    {$iconBg}
                    {$item['icon']}
                </div>

                <div class='text'>
                    <p class='line-clamp'><strong>{$item['label']}</strong></p>
                    <p class='line-clamp'>{$item['value']}</p>
                </div>
            </li>
        ";
    }
}

function displayOrderOverview(){
    global $defaultMoney;

    $subTotal       = getSubTotal();
    $subTotalValue  = numfmt_format_currency($defaultMoney, getSubTotal(), "BRL");

    $deliveryTax        = 0;
    $deliveryTaxValue   = numfmt_format_currency($defaultMoney, $deliveryTax, "BRL");

    $total      = $subTotal + $deliveryTax;
    $totalValue = numfmt_format_currency($defaultMoney, $total, "BRL");

    $map = [
        [
            "label" => "Subtotal",
            "value" => $subTotalValue,
        ],
        [
            "label" => "Taxa de Entrega",
            "value" => $deliveryTaxValue,
        ],
        [
            "label" => "Total",
            "value" => $totalValue,
        ]
    ];

    foreach($map as $item){
        echo "
            <li>
                <div class='label line-clamp'>{$item['label']}</div>
                <p class='text line-clamp'><strong>{$item['value']}</strong></p>
            </li>
        ";
    }
}

// add order to spreadsheet
function cartToSpreadsheet(){
    global $defaultMoney, $orderModel;

    // Google Cloud API setup
    $spreadSheet_id = $_ENV['SPREADSHEET_ID'];

    $client = new Client();

    $client->setAuthConfig(__DIR__ . '/../../../config/projetoacai-472803-2e77e7899901.json');
    $client->addScope(Sheets::SPREADSHEETS);

    // adding cart products to the spreadsheet
    $subTotal = getSubTotal();

    if($subTotal == 0){ // no products at the cart
        redirectWithMessage("noItem", "cart.php", 0);
        return 0;
    }

    $subTotal   = numfmt_format_currency($defaultMoney, getSubTotal(), "BRL");
    $street     = htmlspecialchars($_SESSION['a_street'], ENT_QUOTES, 'UTF-8');
    $houseNum   = htmlspecialchars($_SESSION['a_numHouse'], ENT_QUOTES, 'UTF-8');
    $district   = htmlspecialchars($_SESSION['a_district'], ENT_QUOTES, 'UTF-8');
    $city       = htmlspecialchars($_SESSION['a_city'], ENT_QUOTES, 'UTF-8');

    $address = "{$street}, {$houseNum}, {$district} - {$city}";
    $currentDate = date("Y-m-d");
    $currentHour = date("H:i:s");

    $products = getProductsAtCart();

    $prodConcat = "";
    $totalOrderPrice = 0;
    $deliveryTax = 0;
    
    foreach($products as $product){ // concat data
        $totalOrderPrice += $product['totalPrice'];
        $price = numfmt_format_currency($defaultMoney, $product['totalPrice'], "BRL");

        $prodConcat .= "{$product['name']} / {$product['amount']} / {$price}\n";
    }

    // Adding the data to the spreadsheet
    $service = new Sheets($client);
    $range = "Página1!A8"; // start to write at spreadsheet

    $values = [
        [
            $_SESSION["nameUser"],
            $_SESSION['mailUser'],
            $address,
            $_SESSION['a_referencePoint'],
            $_SESSION['phoneUser'],
            $prodConcat,
            "{$currentDate} {$currentHour}",
            numfmt_format_currency($defaultMoney, $deliveryTax, "BRL"),
            numfmt_format_currency($defaultMoney, $totalOrderPrice, "BRL")
        ]
    ];

    $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
    $params = ['valueInputOption' => 'RAW'];
    $service->spreadsheets_values->append(
        $spreadSheet_id,
        $range,
        $body,
        $params
    );

    // confirm order
    $orderStatus = "confirmed";

    $modifyStatus = $orderModel->modifyStatus($_SESSION['idOrder'], $orderStatus);
    
    if($modifyStatus){ // create new order for the same customer + redirect
        $newOrder = $orderModel->addOrder($_SESSION['idUser']);

        $newOrderId = $newOrder['newId'];
        $_SESSION['idOrder'] = $newOrderId;

        redirectWithMessage("orderConfirmed", "/index.php", 1);
    }
}

function removeFromCart($idVariant){
    global $orderModel;

    $removeVariant = $orderModel->removeVariant($_SESSION['idOrder'], $idVariant);

    if($removeVariant){
        redirectWithMessage("prodRem", "cart.php", 1);
    }
}