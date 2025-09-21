<?php
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "prodPrice.php";
    include "../footerHeader.php";

    $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

    $productName = $_GET["id"];

    $getProductData = $mysqli->prepare("SELECT nameProduct, brandProduct, priceProduct, imageURL FROM product WHERE nameProduct LIKE ?");
    $getProductData->bind_param("s", $productName);
    $amount = 0;

    $prices = [];
    $products = [];

    if($getProductData->execute()){
        $result = $getProductData->get_result();
        $amount = $result->num_rows;

        while($productData = $result->fetch_assoc()){
            $prices[] = $productData["priceProduct"];
            $products[] = $productData["nameProduct"];

            $name = matchDisplayNamesAlt($productData["nameProduct"]);
            $brand = $productData["brandProduct"];

            if($productData["brandProduct"] == "Other Brand"){
                $brand = "Marca não Registrada";
            }

            $price = $productData["priceProduct"];
            $image = $productData["imageURL"];

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

    function getOptions($prices, $products){
        global $defaultMoney;
        for($i = 0 ; $i < sizeof($prices); $i++){
            $name = match($products[$i]){
                "acaiT10","acaiZero10"  => "10 litros",
                "acaiT5"                => "5 litros",
                "acaiT1","acaiNinho1"   => "1 litro",
                "cremeMorango10"        => "Morango - 10l",
                "cremeCupuacu10"        => "Cupuaçu - 10l",
                "cremeMaracuja10"       => "Maracujá - 10l",
                "cremeNinho10"          => "Ninho - 10l",
                "acaiNinho250"          => "250 ml",
                "saborazziChocomalt"    => "Chocomaltine - 5kg",
                "saborazziCocada"       => "Cocada Cremosa - 5kg",
                "saborazziCookies"      => "Cookies Brancos - 5kg",
                "saborazziAvelaP"       => "Creme de Avelã Premium - 5kg",
                "saborazziAvelaT"       => "Creme de Avelã Tradicional - 5kg",
                "saborazziLeitinho"     => "Leitinho - 5kg",
                "saborazziPacoca"       => "Paçoca Cremosa - 5kg",
                "saborazziSkimoL"       => "Skimo ao Leite - 5kg",
                "saborazziSkimoB"       => "Skimo Branco - 5kg",
                "saborazziWafer"        => "Wafer Cremoso - 5kg",
                "morango1", "leiteEmPo1",
                "granola1", "farofaPacoca1", 
                "amendoimTriturado1", "ovomaltine1", 
                "gotasChocolate1", "chocoball1", 
                "confete1"              => "1 kg",
                "granola1.5"            => "1.5 kg",
                "jujuba500"             => "500 g",
                "pacoca150"             => "150 unidades",
                "colher200"             => "200 unidades",
                "colher500"             => "500 unidades",
                "colher800"             => "800 unidades",
                "polpaAbac"             => "Abacaxi",
                "polpaAbacHort"         => "Abacaxi c/ Hortelã",
                "polpaAcrl"             => "Acerola",
                "polpaAcrlMamao"        => "Acerola c/ Mamão",
                "polpaCacau"            => "Cacau",
                "polpaCaja"             => "Caja",
                "polpaCaju"             => "Caju",
                "polpaCupuacu"          => "Cupuaçu",
                "polpaGoiaba"           => "Goiaba",
                "polpaGraviola"         => "Graviola",
                "papaya"                => "Mamão",
                "papaPashion"           => "Mamão c/ Maracujá",
                "polpaManga"            => "Manga",
                "polpaMangaba"          => "Mangaba",
                "polpaMaracuja"         => "Maracujá",
                "polpaMorango"          => "Morango",
                "polpaUva"              => "Uva",
                default                 => "Opção Inválida",

            };
            $price = numfmt_format_currency($defaultMoney, $prices[$i], "BRL");;
            echo " <option value='{$products[$i]}' data-preco='{$price}'>{$name}</option>";
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../CSS/general-style.css">
    <link rel="stylesheet" href="../CSS/productView.css">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&display=swap" rel="stylesheet"> 

    <?php faviconOut(); ?>

    <script>
        // Script para atualizar os preços em tempo real de cada produto
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

            // Atualiza logo ao carregar
            updatePrices();

            // Atualiza quando mudar o select
            sizeSelectors.forEach(selector => {
                selector.addEventListener('change', updatePrices);
            });
        });
    </script>

    <title>Açaí e Polpas Amazônia - <?php echo $name?></title>

</head>
<body>
    <?php headerOut(1)?>

    <main>
        <a href="products.php" class="back-button">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Voltar
        </a>

        <section>
            <div class="product-main-img">
                <img src="<?php echo $image?>" alt="Product Image">
            </div>

            <div class="product-forms-div">
                <div>
                    <p><small><?php echo $brand?></small></p>
                    <h1><?php echo $name?></h1>
                    <p class="product-price-value"> ---- </p>
                </div>

                <form method="get" class="product-forms">
                    <div class="forms-text">
                        <div class="forms-item product-size">
                            <label for="isize">Tamanho: </label>
                            <select name="size" id="isize" class="product-size-selector">
                                <?php getOptions($prices,  $products)?>
                            </select>
                        </div>
                        <div class="forms-item product-amount">
                            <label for="iamount-product">Quantidade: </label>
                            <input type="number" name="amount-product" id="iamount-product" value="1" max="150" min="1">
                        </div>
                    </div>

                    <?php 
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
                    ?>
                </form>

            </div>
        </section>
    
    </main>

    <?php footerOut();?>
    
</body>
</html>