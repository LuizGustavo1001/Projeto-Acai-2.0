<?php
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";

    function add2Cart($prodName, $amount){
        global $mysqli;

        if(! isset($_SESSION['userName'])){ setCookies("unkUser", "/index.php", 0); }

        // mapping the allowed product variant names
        $getAllNames = $mysqli->query("SELECT nameProduct FROM product_version") or die("var getAllNames (productView.php): " . $mysqli->errno);
        $allowedNames = [];
        while($allNames = $getAllNames->fetch_assoc()){ $allowedNames[] = $allNames["nameProduct"]; }
        $getAllNames->close();

        if(in_array($prodName, $allowedNames)){
            $getProductData = $mysqli->prepare("
                SELECT pv.idVersion, pv.priceProduct, pv.availability, pd.altName, pv.sizeProduct, pd.printName
                FROM product_version AS pv JOIN product_data AS pd ON pv.idProduct = pd.idProduct
                WHERE nameProduct = ?
                LIMIT 1
            ") or die("var getProductData (productView.php): " . $mysqli->errno);

            $getProductData->bind_param("s",$prodName);

            $getProductData->execute();
            $productData = $getProductData->get_result();
            $productData = $productData->fetch_assoc();
            $getProductData->close();

            switch($productData["availability"]){
                case "indisponivel":
                    setCookies("outOfOrder", "productView.php?id={$productData["altName"]}", 0);
                default:
                    // verify if the product is already at the cart
                    $check = $mysqli->prepare("
                        SELECT amount FROM product_order WHERE idOrder = ? AND idVersion = ?
                    ") or die("var check (productView.php): " . $mysqli->errno);
                    $check->bind_param("ii", $_SESSION["idOrder"], $productData["idVersion"]);

                    $check->execute();
                    $result = $check->get_result();
                    $check->close();

                    if($result->num_rows > 0){ // update product variant data at the cart
                        $update = $mysqli->prepare("
                            UPDATE product_order
                            SET amount = ?, totPrice = ?
                            WHERE idOrder = ? AND idProduct = ?
                        ") or die("var update (productView.php):" . $mysqli->errno);
                        $update->bind_param("idii", $amount, $productData["priceProduct"], $_SESSION["idOrder"], $productData["idVersion"]);

                        $update->execute();
                        $update->close();
                    }else{ // insert new product variant at the cart
                        $totalPrice = $productData["priceProduct"] * $amount;

                        $insertOrder = $mysqli->prepare("INSERT INTO product_order (idOrder, idVersion, amount, singlePrice, totPrice) VALUES (?, ?, ?, ?, ?)") or die("var insertOrder (productView.php):" . $mysqli->errno);
                        $insertOrder->bind_param(
                            "iiidd",
                            $_SESSION["idOrder"], 
                            $productData["idVersion"], 
                            $amount, 
                            $productData["priceProduct"], 
                            $totalPrice
                        );

                        $insertOrder->execute();
                        $insertOrder->close();
                    }

                    // redirect to product page
                    header("Location: products.php?prodAdd=1&id={$productData['printName']}&size={$productData['sizeProduct']}");
                    exit();
            }
        }
    }

    if(isset($_GET['size'], $_GET['amount'])){ add2Cart($_GET['size'], $_GET['amount']); }

    $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);
    $productName = $_GET["id"];

    // mapping the allowed product names
    $getAllNames = $mysqli->query("SELECT altName FROM product_data") or die("var getAllNames (productView.php)" . $mysqli->errno);
    $allowedNames = [];
    while($allNames = $getAllNames->fetch_assoc()){ $allowedNames[] = $allNames["altName"]; }
    $getAllNames->close();

    if(in_array($_GET["id"], $allowedNames)){
        // returning all the data that match with the product with the name above
        $getProductData = $mysqli->prepare("
            SELECT pd.printName, pd.brandProduct, pv.priceProduct, pv.imageURL, pd.altName, pd.altName
            FROM product_data AS pd 
                JOIN product_version AS pv ON pd.idProduct = pv.idProduct
            WHERE pd.altName = ? 
        ") or die("var getProductData (productView.php)" . $mysqli->errno);
        $getProductData->bind_param("s", $productName);

        $realName = "";
        // returning the prices of the products with the name that matches the one above
        $getProductData->execute();
        $result = $getProductData->get_result();
        $getProductData->close();

        while($productData = $result->fetch_assoc()){
            $realName   = $productData["altName"];
            $printName  = $productData["printName"];
            $brand      = $productData["brandProduct"];
            $price      = $productData["priceProduct"];
            $image      = $productData["imageURL"];
            $linkName   = $productData["altName"];
        }
        
         // fill selectable options inside product form
        function getOptions($nameProd){
            global $defaultMoney, $mysqli;

            $getOptions = $mysqli->prepare("
                SELECT pv.sizeProduct, pv.priceProduct, pv.nameProduct, pv.flavor
                FROM product_version AS pv
                    JOIN product_data AS pd ON pd.idProduct = pv.idProduct
                WHERE  pd.altName = ?
            ") or die("var getOptions (productView.php)" . $mysqli->errno);
            $getOptions->bind_param("s", $nameProd);

            $getOptions->execute();
            $result = $getOptions->get_result();
            $getOptions->close();
            
            while($options = $result->fetch_assoc()){
                $price = numfmt_format_currency($defaultMoney, $options['priceProduct'], "BRL");
                if($options['flavor'] != null){
                    $value = htmlspecialchars($options['flavor'], ENT_QUOTES, 'UTF-8');
                    $text  = $value;
                }else{
                    $value = htmlspecialchars($options['sizeProduct'], ENT_QUOTES, 'UTF-8');
                    $text  = $value;
                }
                echo " <option value='{$options["nameProduct"]}' data-preco='{$price}'>{$text}</option>";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("products") ?>">

    <?php displayFavicon()?>

    <script>
        // Update the prices of each product variant selected in real time
        document.addEventListener('DOMContentLoaded', function () {
            const priceElements = document.querySelectorAll('.product-price-value')
            const sizeSelectors = document.querySelectorAll('.product-size-selector')

            function updatePrices(){
                sizeSelectors.forEach((selector, index) => {
                    const selectedOption = selector.options[selector.selectedIndex]
                    const price = selectedOption.dataset.preco || 'Preço indisponível'
                    if(priceElements[index]){
                        priceElements[index].textContent = price
                    }
                })
            }
            updatePrices();

            sizeSelectors.forEach(selector => {
                selector.addEventListener('change', updatePrices)
            })
        })
    </script>
    
    <style>
        body{
            align-items: center;
            justify-content: center;
        }
    </style>

    <title>Açaí e Polpas Amazônia | <?php echo $printName?> </title>
</head>

<body>
    <main class="rise-above product-view">
        <div class="back-button" onclick="window.location.href = 'products.php'">
            <?php echo getIcon("back") ?>
            <span>Voltar</span>
        </div>

        <?php 
            if(in_array($_GET["id"], $allowedNames)){
                $submitButton = "";
                if(! isset($_SESSION["isAdmin"])){
                    $submitButton = "<button class='regular-btn'>Adicionar ao Carrinho</button>";
                }

                echo "
                    <div class='product'>
                        <img src='{$image}' alt='product image'>
                        <div class='content'>
                            <div class='title'>
                                <p>{$brand}</p>
                                <h1>{$printName}</h1>
                            </div>

                            <div class='price product-price-value'><span>R$ 00,00</span></div>

                            <form method='GET'>
                                <div class='inputs'>
                                    <div class='regular-input'>
                                        <label for='isize'>Tamanho: </label>
                                        <select name='size' id='isize' class='product-size-selector'>";
                                           echo getOptions($realName);
                echo                    "</select>
                                    </div>
                                    <div class='regular-input'>
                                        <label for='iamount'>Quantidade: </label>
                                        <input type='number' name='amount' id='iamount' value='1' min='1'>
                                    </div>
                                </div>
                                {$submitButton}
                            </form>
                        </div>
                    </div>
                ";
            }
        ?>

        <div class="fot-copy">2026 &copy; Açaí e Polpas Amazônia</div>

        <?php 
            /*
            if(in_array($_GET["id"], $allowedNames)){
                $submitButton = "";
                if(! isset($_SESSION["isAdmin"])){
                    $submitButton = "<li><button class='regular-button'>Adicionar ao Carrinho</button> </li>";
                }

                echo "
                    <div class='back-button'>
                        <a href='products.php#{$linkName}'>
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3'/>
                            </svg>
                            Voltar
                        </a>
                    </div>

                    <div class='product-hero'>
                        <div class='product-img'>
                            <img src='{$image}' alt='Product Image'>
                        </div>

                        <div class='product-data'>
                            <div class='data-title'>
                                <p><span>{$brand}</span></p>
                                <h1>{$printName}</h1>
                            </div>
                            <p class='price product-price-value'>--</p>
                            <form method='GET'>
                                <ul class='product-var-list'>
                                    <li class='product-var regular-input'>
                                        <label for='isize'>Tamanho: </label>
                                        <select name='size' id='isize' class='product-size-selector'>";
                                             getOptions($realName);
                echo "                  </select>
                                    </li>
                                    <li class='product-var regular-input'>
                                        <label for='iamount'>Quantidade: </label>
                                        <input type='number' name='amount' id='iamount' value='1'>
                                    </li>
                                    {$submitButton}
                                </ul>
                            </form>
                        </div>
                    </div>
                ";                   
            }else{
                echo "
                    <div class= 'errorText'>
                        <i class=\"fa-solid fa-triangle-exclamation\"></i>
                        <p>Erro: Nenhum Produto encontrado com o Id Selecionado</p>
                    </div>
                ";
            }
               */ 
        ?>
    </main>

    <script src="/js/script.js"></script>

</body>
</html>