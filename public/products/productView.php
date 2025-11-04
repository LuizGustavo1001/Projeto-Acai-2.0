<?php
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    include "../printStyles.php";

    function add2Cart($prodName, $amount){
        // add a product to the cart in database based on the product selected on productView.php
        global $mysqli;

        if(! isset($_SESSION['userName'])){
            header("Location: ../account/login.php?unkUser=1");
            exit();
        }

        $getAllNames = $mysqli->query("SELECT nameProduct FROM product_version");
        $allowedNames = [];
        
        while($allNames = $getAllNames->fetch_assoc()){
            $allowedNames[] = $allNames["nameProduct"];
        }
        $getAllNames->close();

        if(in_array($prodName, $allowedNames)){
            $getProductData = $mysqli->prepare("
                SELECT pv.idVersion, pv.priceProduct, pv.availability, pd.altName, pv.sizeProduct, pd.printName
                FROM product_version AS pv JOIN product_data AS pd ON pv.idProduct = pd.idProduct
                WHERE nameProduct = ?
                LIMIT 1
            ");

            $getProductData->bind_param("s",$prodName);
            $getProductData->execute();

            $productData = $getProductData->get_result();
            $productData = $productData->fetch_assoc();
            $getProductData->close();
            switch($productData["availability"]){
                case "indisponivel":
                    header("Location: productView.php?id={$productData["altName"]}&outOfOrder=1");
                    exit();
                default:
                    // verify if the product is already at the cart
                    $check = $mysqli->prepare("
                        SELECT amount FROM product_order WHERE idOrder = ? AND idProduct = ?
                    ");
                    $check->bind_param("ii", $_SESSION["idOrder"], $productData["idVersion"]);
                    $check->execute();
                    $result = $check->get_result();
                    if($result->num_rows > 0){
                        // update product at the cart
                        $update = $mysqli->prepare("
                            UPDATE product_order
                            SET amount = ?, totPrice = ?
                            WHERE idOrder = ? AND idProduct = ?
                        ");
                        $update->bind_param("idii", $amount, $productData["priceProduct"], $_SESSION["idOrder"], $productData["idVersion"]);
                        $update->execute();
                        $update->close();
                    }else{
                        // insert new product at the cart
                        $totalPrice = $productData["priceProduct"] * $amount;

                        $inserOrder = $mysqli->prepare("INSERT INTO product_order (idOrder, idProduct, amount, singlePrice, totPrice) VALUES (?, ?, ?, ?, ?)");
                        $inserOrder->bind_param(
                            "iiidd",
                            $_SESSION["idOrder"], 
                            $productData["idVersion"], 
                            $amount, 
                            $productData["priceProduct"], 
                            $totalPrice
                        );

                        if($inserOrder->execute()){
                            $inserOrder->close();
                            
                        }
                    }
                    $check->close();
                    header("Location: products.php?prodAdd=1&id={$productData['printName']}&size={$productData['sizeProduct']}");
                    exit();
            }
        }
    }

    if(isset($_GET['size'], $_GET['amount'])){
        add2Cart($_GET['size'], $_GET['amount']);
    }

    $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);
    $productName = $_GET["id"];

    $getAllNames = $mysqli->query("SELECT altName FROM product_data");
    $allowedNames = [];
    while($allNames = $getAllNames->fetch_assoc()){
        $allowedNames[] = $allNames["altName"];
    }
    $getAllNames->close();

    if(in_array($_GET["id"], $allowedNames)){
        // returning all the data that match with the product with the name above
        $getProductData = $mysqli->prepare("
            SELECT pd.printName, pd.brandProduct, pv.priceProduct, pv.imageURL, pd.altName, pd.altName
            FROM product_data AS pd 
                JOIN product_version AS pv ON pd.idProduct = pv.idProduct
            WHERE pd.altName = ? 
        ");
        $getProductData->bind_param("s", $productName);

        $realName = "";
        // returning the prices of the products with the name that matches the one above
        if($getProductData->execute()){
            $result = $getProductData->get_result();
            while($productData = $result->fetch_assoc()){
                $realName   = $productData["altName"];
                $printName  = $productData["printName"];
                $brand      = $productData["brandProduct"];
                $price      = $productData["priceProduct"];
                $image      = $productData["imageURL"];
                $linkName   = $productData["altName"];
            }
        }else{
            echo"
                <div class='errorText'>
                    <small>
                        <i class=\"fa-solid fa-triangle-exclamation\"></i> 
                        <p>Erro ao tentar imprimir o Produto, tente novamente mais tarde</p>
                    </small>
                </div>
            ";
        }
        $getProductData->close();
         
        function getOptions($nameProd){
            // returning the options that's gonna be inside the select HTML tag
            global $defaultMoney, $mysqli;
            $getOptions = $mysqli->prepare("
                SELECT pv.sizeProduct, pv.priceProduct, pv.nameProduct, pv.flavor
                FROM product_version AS pv
                    JOIN product_data AS pd ON pd.idProduct = pv.idProduct
                WHERE  pd.altName = ?
            ");

            $getOptions->bind_param("s", $nameProd);
            $getOptions->execute();
            $result = $getOptions->get_result();
            
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
            $getOptions->close();
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("1", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "productVersion") ?>">
    
    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&display=swap" rel="stylesheet"> 

    <script src="../JS/generalScripts.js"></script>

    <?php displayFavicon()?>

    <script>
        // Script to update the prices of each product selected in real time
        document.addEventListener('DOMContentLoaded', function () {
            const priceElements = document.querySelectorAll('.product-price-value');
            const sizeSelectors = document.querySelectorAll('.product-size-selector');

            function updatePrices() {
                sizeSelectors.forEach((selector, index) => {
                    const selectedOption = selector.options[selector.selectedIndex];
                    const price = selectedOption.dataset.preco || 'Preço indisponível';
                    if (priceElements[index]) {
                        priceElements[index].textContent = price;
                    }
                });
            }
            updatePrices();

            sizeSelectors.forEach(selector => {
                selector.addEventListener('change', updatePrices);
            });
        });
    </script>

    <title>Açaí e Polpas Amazônia - <?php echo $printName?></title>

</head>
<body>
    <?php displayHeader(1)?>
    <main>
        <?php 
            if(isset($_GET["outOfOrder"])){
                displayPopUp("outOfOrder", $_GET['id']);
            }
            
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
        ?>
    </main>
    <?php displayFooter();?>
</body>
</html>