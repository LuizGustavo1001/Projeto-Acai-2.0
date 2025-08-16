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

    <link rel="stylesheet" href="../../styles/general-style.css">
    <link rel="stylesheet" href="../../styles/productView-style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&display=swap" rel="stylesheet"> 

    <link rel="shortcut icon" href="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080377/iconeAcai_mj7dqy.ico" type="image/x-icon">

    <script src="../../scripts/generalScripts.js"></script>


    <?php
        // Definir os preços usando a função prodPrice do PHP
        $preco1  = returnPrice('polpaAbac');
        $preco2  = returnPrice('polpaAbacHort');
        $preco3  = returnPrice('polpaAcrl');
        $preco4  = returnPrice('polpaAcrlMamao');
        $preco5  = returnPrice('polpaCacau');
        $preco6  = returnPrice('polpaCaja');
        $preco7  = returnPrice('polpaCaju');
        $preco8  = returnPrice('polpaCupuacu');
        $preco9  = returnPrice('polpaGoiaba');
        $preco10 = returnPrice('polpaGraviola');
        $preco11 = returnPrice('polpaManga');
        $preco12 = returnPrice('polpaMangaba');
        $preco13 = returnPrice('polpaMaracuja');
        $preco14 = returnPrice('polpaMorango');
        $preco15 = returnPrice('polpaUva');
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const priceElements = document.querySelectorAll('.product-price-value');
            const sizeSelectors = document.querySelectorAll('.product-size-selector');

            const prices = {
                polpaAbac:      "<?= $preco1; ?>",
                polpaAbacHort:  "<?= $preco2; ?>",
                polpaAcrl:      "<?= $preco3; ?>",
                polpaAcrlMamao: "<?= $preco4; ?>",
                polpaCacau:     "<?= $preco5; ?>",
                polpaCaja:      "<?= $preco6; ?>",
                polpaCaju:      "<?= $preco7; ?>",
                polpaCupuacu:   "<?= $preco8; ?>",
                polpaGoiaba:    "<?= $preco9; ?>",
                polpaGraviola:  "<?= $preco10; ?>",
                polpaManga:     "<?= $preco11; ?>",
                polpaMangaba:   "<?= $preco12; ?>",
                polpaMaracuja:  "<?= $preco13; ?>",
                polpaMorango:   "<?= $preco14; ?>",
                polpaUva:       "<?= $preco15; ?>",
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

    <title>Açaí Amazônia - Produtos</title>

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
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg" alt="Product Image">
                </div>

                <div class="product-main-text">
                    <h1>Polpa de Frutas Sabor Natural<small><sup>&copy;</sup></small> - unidade</h1>
                    <p class="product-price-value"> ---- </p>
                </div>
            </div>

            <form method="get" class="product-forms">
                <div class="forms-text">
                    <div class="forms-item product-size">
                        <label for="isize">Sabores: </label>
                        <select name="size" id="isize" class="product-size-selector">
                            <option value="polpaAbac">Abacaxi</option>
                            <option value="polpaAbacHort">Abacaxi c/ Hortelã</option>
                            <option value="polpaAcrl">Acerola</option>
                            <option value="polpaAcrlMamao">Acerola c/ Mamão</option>
                            <option value="polpaCacau">Cacau</option>
                            <option value="polpaCaja">Caja</option>
                            <option value="polpaCaju">Caju</option>
                            <option value="polpaCupuacu">Cupuaçu</option>
                            <option value="polpaGoiaba">Goiaba</option>
                            <option value="polpaGraviola">Graviola</option>
                            <option value="papaya">Mamão</option>
                            <option value="papaPashion">Mamão c/ Maracujá</option>
                            <option value="polpaManga">Manga</option>
                            <option value="polpaMangaba">Mangaba</option>
                            <option value="polpaMaracuja">Maracujá</option>
                            <option value="polpaMorango">Morango</option>
                            <option value="polpaUva">Uva</option>
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
                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg" alt="Product Image">
            </div>

            <div class="product-forms-div">
                <h1>Polpa de Frutas Sabor Natural<small><sup>&copy;</sup></small> - unidade</h1>
                <p class="product-price-value"> ---- </p>

                <form method="get" class="product-forms">
                    <div class="forms-text">
                        <div class="forms-item product-size">
                            <label for="isize">Sabores: </label>
                            <select name="size" id="isize" class="product-size-selector">
                                <option value="polpaAbac">Abacaxi</option>
                                <option value="polpaAbacHort">Abacaxi c/ Hortelã</option>
                                <option value="polpaAcrl">Acerola</option>
                                <option value="polpaAcrlMamao">Acerola c/ Mamão</option>
                                <option value="polpaCacau">Cacau</option>
                                <option value="polpaCaja">Caja</option>
                                <option value="polpaCaju">Caju</option>
                                <option value="polpaCupuacu">Cupuaçu</option>
                                <option value="polpaGoiaba">Goiaba</option>
                                <option value="polpaGraviola">Graviola</option>
                                <option value="papaya">Mamão</option>
                                <option value="papaPashion">Mamão c/ Maracujá</option>
                                <option value="polpaManga">Manga</option>
                                <option value="polpaMangaba">Mangaba</option>
                                <option value="polpaMaracuja">Maracujá</option>
                                <option value="polpaMorango">Morango</option>
                                <option value="polpaUva">Uva</option>
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