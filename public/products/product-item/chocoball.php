<?php
    include "../../../databaseConnection.php";
    include "../../generalPHP.php";
    include "../prodPrice.php";
    include "../../footerHeader.php";

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../CSS/general-style.css">
    <link rel="stylesheet" href="../../CSS/productView-style.css">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&display=swap" rel="stylesheet"> 

    <?php faviconOut(); ?>

    <script src="../../JS/generalScripts.js"></script>

    <?php
        // Definir os preços usando a função prodPrice do PHP
        $price = returnPrice('chocoball1');
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const priceElements = document.querySelectorAll('.product-price-value');
            const sizeSelectors = document.querySelectorAll('.product-size-selector');

            const prices = {
                chocoball1: "<?= $price; ?>",
            };

            function updatePrices() {
                sizeSelectors.forEach((selector, index) => {
                    const selectedSize = selector.value;
                    const priceText = prices[selectedSize] || 'Preço indisponível';
                    if (priceElements[index]) {
                        priceElements[index].textContent = priceText;
                    }
                });
            }

            updatePrices();

            sizeSelectors.forEach(selector => {
                selector.addEventListener('change', updatePrices);
            });
        });
    </script>

    <title>Açaí e Polpas Amazônia - Produtos</title>

</head>
<body>
    <?php 
        if(isset($_GET["prodAdd"])){
            echo
            "<section class= \"popup-box show\">
                    <div class=\"popup-div\">
                        <div><h1>Carrinho Atualizado</h1></div>
                        <div>
                            <p>Produto Adicionado com sucesso ao Carrinho</p>
                            <p>Clique no botão abaixo para fechar esta janela</p>
                            <button class=\"popup-button\">Fechar</button>
                        </div>
                    </div>
            </section>";
        }
    ?>

    <?php headerOut(2)?>
    

    <main class="mobile-main">
        <div>
            <a href="../products.php" class="back-button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Voltar
            </a>
        </div>

        <section class="product-hero">
            <div class="product-main">
                <div class="product-main-img">
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png" alt="Product Image">
                </div>

                <div class="product-main-text">
                    <h1>Chocoball - 1kg</h1>
                    <p class="product-price-value"> ---- </p>
                </div>
            </div>

            <form method="get" class="product-forms">
                <div class="forms-text">
                    <div class="forms-item product-size">
                        <label for="isize">Tamanho: </label>
                        <select name="size" id="isize" class="product-size-selector">
                            <option value="chocoball1">1 kg</option>
                        </select>
                    </div>
                    <div class="forms-item product-amount">
                        <label for="iamount-product">Quantidade: </label>
                        <input type="number" name="amount-product" id="iamount-product" value="1" max="150" min="1">
                    </div>
                </div>

                <input type="hidden" name="formType" value="mobile">

                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    Adicionar Ao Carrinho
                </button>
            </form>

        </section>

    </main>

    <main class="desktop-main">
        <div>
            <a href="../products.php" class="back-button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Voltar
            </a>
        </div>

        <section>
            <div class="product-main-img">
                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755353352/acai_doaqvb.png" alt="Product Image">
            </div>

            <div class="product-forms-div">
                <h1>Chocoball - 1kg</h1>
                <p class="product-price-value"> ---- </p>

                <form method="get" class="product-forms">
                    <div class="forms-text">
                        <div class="forms-item product-size">
                            <label for="isize">Tamanho: </label>
                            <select name="size" id="isize" class="product-size-selector">
                                <option value="chocoball1">1 kg</option>
                            </select>
                        </div>
                        <div class="forms-item product-amount">
                            <label for="iamount-product">Quantidade: </label>
                            <input type="number" name="amount-product" id="iamount-product" value="1" max="150" min="1">
                        </div>
                    </div>
                    
                    <input type="hidden" name="formType" value="desktop">

                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        Adicionar Ao Carrinho
                    </button>
                </form>

            </div>
        </section>
    
    </main>

    <?php footerOut();?>
    
</body>
</html>