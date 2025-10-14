<?php
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    include "../printStyles.php";

    if(isset($_GET['size'], $_GET['amount-product'])){
        add2Cart($_GET['size'], $_GET['amount-product']);
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
                <p class='errorText'>
                    <small>
                        <i class=\"fa-solid fa-triangle-exclamation\"></i> 
                        Erro ao tentar imprimir o Produto, tente novamente mais tarde
                    </small>
                </p>
            ";
        }
        $getProductData->close();
         
        function getOptions($nameProd){
            // returning the options that's gonna be inside the select tag at HTML
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

    <link rel="stylesheet" href="<?php printStyle("1", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "productVersion") ?>">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&display=swap" rel="stylesheet"> 

    <?php faviconOut() ?>

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
    <?php headerOut(1)?>

    <main>
        <?php 
            if(in_array($_GET["id"], $allowedNames)){
                echo "
                    <a href='products.php#{$linkName}' class='back-button'>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18'/>
                        </svg>
                        Voltar
                    </a>

                    <section>
                        <div class='product-main-img'>
                            <img src=".$image." alt='Product Image'>
                        </div>

                        <div class='product-forms-div'>
                            <div>
                                <p><small>" .$brand."</small></p>
                                <h1>".$printName ."</h1>
                                <p class='product-price-value'> ---- </p>
                            </div>

                            <form method='get' class='product-forms'>
                                <div class='forms-text'>
                                    <div class='forms-item product-size'>
                                        <label for='isize'>Tamanho: </label>
                                        <select name='size' id='isize' class='product-size-selector'>
                        ";
                        getOptions($realName);
                        echo "
                                        </select>
                                    </div>
                                    <div class='forms-item product-amount'>
                                        <label for='iamount-product'>Quantidade: </label>
                                        <input type='number' name='amount-product' id='iamount-product' value='1' max='150' min='1'>
                                    </div>
                                </div>
                        ";
                        if(! isset($_SESSION["isAdmin"])){
                            echo "
                                <button type=\"submit\">
                                    <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"size-6\">
                                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z\"/>
                                    </svg>
                                    Adicionar Ao Carrinho
                                </button>
                            ";
                        }
                        echo "
                            </form>
                        </div>
                    </section>
                ";
            }else{
                echo "<p class= 'errorText'>Erro: Nenhum Produto encontrado com o Id Selecionado</p>";
            }
        ?>
    </main>
    <?php footerOut();?>
</body>
</html>