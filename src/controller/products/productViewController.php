<?php 
require_once __DIR__ . '/../mainController.php';
require_once __DIR__ . '/../../models/Product.php';

checkSessionStatus();

$productModel   = new Product($mysqli);
$orderModel     = new Order($mysqli);

$productId = $_GET["id"] ?? '';

if(isset($_GET['variant'], $_GET['amount'])){ 
    add2Cart($_GET['variant'], $_GET['amount']);
}

if($productId === ''){ // trying to access page without selecting a product
    header("location: products.php");
    exit();
}

// return product variants
$variants = $productModel->getById($productId, "pd.idProduct");

if($variants['amount'] <= 0){
    header("location: products.php");
    exit();
}

// mapping product and variants data
$dataMap = [
    "prodName"      => $variants['data'][0]['printName'],
    "brand"         => $variants['data'][0]['brand'],
    "image"         => $variants['data'][0]['image'],
    "link"          => "",
    "prices"        => [],
    "values"        => [],
    "placeholders"  => [],
];

foreach($variants['data'] as $variant){ // filling options value and price
    if($variant['status'] == True){ // avoid not available variants
        $dataMap['prices'][]        = $variant['price'];
        $dataMap['placeholders'][]  = $variant['flavor'] ?? $variant['size'];
        $dataMap['values'][]        = $variant['idVariant'];
    }
}

function fillPage(){
    global $dataMap;

    $submitButton = "";
    if(! isset($_SESSION["isAdmin"])){ // only customers can add to the cart
        $submitButton = "<button class='regular-btn'>Adicionar ao Carrinho</button>";
    }

    echo "
        <div class='product'>
            <img src='{$dataMap['image']}' alt='{$dataMap['prodName']} image'>
            <div class='content'>
                <div class='title'>
                    <p>{$dataMap['brand']}</p>
                    <h1>{$dataMap['prodName']}</h1>
                </div>
                <div class='price product-price-value'><span>R$ 00,00</span></div>
                <form method='GET'>
                    <div class='regular-input-box'>
                        <label for='ivariant'>Tamanho: </label>
                        <select name='variant' id='ivariant' class='product-variant-selector'>";
                            fillOptions($dataMap['prices'], $dataMap['placeholders'], $dataMap['values']);
    echo "              </select>
                    </div>
                    <div class='regular-input-box'>
                        <label for='iamount'>Quantidade: </label>
                        <input type='number' name='amount' id='iamount' value='1' min='1'>
                    </div>
                    {$submitButton}
                </form>
            </div>
        </div>
    ";

}

function fillOptions($prices, $placeholders, $values){
    for($i = 0; $i < sizeof($values); $i++){
        $value = htmlspecialchars($values[$i], ENT_QUOTES, 'UTF-8');
        $price = htmlspecialchars($prices[$i], ENT_QUOTES, 'UTF-8');
        $placeholder = htmlspecialchars($placeholders[$i], ENT_QUOTES, 'UTF-8');

        echo "<option value='{$value}' data-price='{$price}'>{$placeholder}</option>";
    }
}


function add2cart($idVariant, $amount){
    global $productModel, $orderModel;

    $variant = $productModel->getById($idVariant, "pv.idVariant");

    if($variant['amount'] <= 0){
        header("location: products.php");
        exit();
    }

    // verify if the product is already at the cart
    $variantExists = $orderModel->verifyVariant($_SESSION['idOrder'], $idVariant);

    if($variantExists){ // update value
        $orderModel->modifyVariant($_SESSION['idOrder'], $idVariant, $amount);
    }else{ // add new variant
        $orderModel->addVariant($_SESSION['idOrder'], $idVariant, $amount);
    }

    redirectWithMessage("prodAdd", "products.php", 1);
}