<?php 
    include "databaseConnection.php";
    include "generalPHP.php";
    
    function featureItens(){
        global $mysqli;

        $totalQuery = $mysqli->prepare("SELECT COUNT(*) FROM product");

        if($totalQuery->execute()){
            $totalResult = $totalQuery->get_result();
            $total = $totalResult->fetch_row()[0];
            $totalQuery->close();

            $randomIdVec = [];

            while (count($randomIdVec) < 4) {
                $randomId = rand(1, $total);
                if (!in_array($randomId, $randomIdVec)) {
                    $randomIdVec[] = $randomId;
                }
            }

            $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

            for($j = 0; $j < 4; $j++){
                $query = $mysqli->prepare(
                    "SELECT nameProd, brand, price, priceDate, imageURL FROM product WHERE idProd = ?"
                );
                
                $query->bind_param("s", $randomIdVec[$j]);
                if($query->execute()){
                
                    $result = $query->get_result()->fetch_assoc();

                    $name      = $result['nameProd'];
                    $brand     = $result['brand'];

                    if($brand == "Other Brand"){
                        $brand = "Marca Não Cadastrada";
                    }

                    $price     = numfmt_format_currency($defaultMoney, $result['price'], "BRL");
                    $priceDate = $result['priceDate'];
                    $image     = $result['imageURL'];
                    $name = matchNames($name); // atualizar o nome do produto (mais legível)
                    $linkName = matchProductLinkName($result['nameProd']);

                    echo "
                        <li class=\"feature-item item-translate-alt\">  
                            <a href=\"products/product-item/$linkName.php\">
                                <img src=\"$image\" alt=\"Product Image\">
                                <p>$brand</p>
                                <h2>$name</h2>
                                <p class=\"price\">$price</p>
                                <p>Preço Atualizado em: $priceDate</strong></p>
                            </a>
                        </li>
                    ";
                }else{
                    header("location: errorPage.php");
                }
                
            }
        }else{
            header("location: errorPage.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles/general-style.css">
    <link rel="stylesheet" href="styles/index.css">

    <link rel="shortcut icon" href="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080377/iconeAcai_mj7dqy.ico" type="image/x-icon">

    <script src="scripts/generalScripts.js"></script>
    
    <title>Açaí Amazônia Ipatinga</title>

</head>
<body>

    <header>
        <ul class="left-header">
            <li class="acai-icon">
                <a href="index.php">
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079683/acai-icon-white_fll4gt.png" class="item-translate" alt="Açaí Icon">
                </a>
                <p>Açaí Amazônia Ipatinga</p>
            </li>
        </ul>

        <form method="get" class="header-search">
            <input type="text" name="nameProd" placeholder="Nome do Produto aqui" required> 
            <button>Pesquisar</button>
            <?php 
                if(isset($_GET["nameProd"])){
                    header("location: products/products.php?nameProd=" . $_GET["nameProd"]);
                }
            ?>
        </form>
        
        <ul class="right-header">
            <li>
                <a href="account/account.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                    <p><span>Minha</span> Conta</p>
                </a>
            </li>
            <li>
                <a href="products/products.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>

                    <p>Produtos</p>
                </a>
            </li>
            
            <li >
                <a href="cart/cart.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>

                    <p>Carrinho</p>
                </a>
                <?php verifyCartAmount();?>
            </li>
        </ul>

    </header>

    <main>
        <?php 
            if(isset($_GET["orderConfirmed"])){
                echo "
                    <section class= \"popup-box show\">
                        <div class=\"popup-div\">
                            <div><h1>Pedido Confirmado</h1></div>
                            <div>
                                <p>Pedido no nome de <strong>$_SESSION[clientName]</strong> foi enviado para nossa central</p>
                                <p>Clique no botão abaixo para fechar esta janela</p>
                                <button class=\"popup-button\">Fechar</button>
                            </div>
                        </div>
                    </section>
                ";
                if(isset($_SESSION["subTotal"])){
                    unset($_SESSION["subTotal"]);
                }
                verifyOrders();
            }   

            if(isset($_GET["loginSuccess"])){
                echo "
                    <section class= \"popup-box show\">
                        <div class=\"popup-div\">
                            <div><h1>Login Realizado com Sucesso</h1></div>
                            <div>
                                <p>Agora voce pode navegar pelo site e fazer compras em seu nome</p>
                                <p>Clique no botão abaixo para fechar esta janela</p>
                                <button class=\"popup-button\">Fechar</button>
                            </div>
                        </div>
                    </section>
                ";
            }
        ?>

        <section class="main-section">
            <div class="left-content">
                <h1>
                    Açaí
                    <br>
                    <span style="color: #cc0088">Amazônia</span>
                </h1>
                <p>Qualidade Superior, Preço Inferior</p>
                <div>
                    <a href="products/products.php">
                        <button>
                            Compre Agora
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" style="width: 25px;">
                                <path fill-rule="evenodd" d="M2 10a.75.75 0 0 1 .75-.75h12.59l-2.1-1.95a.75.75 0 1 1 1.02-1.1l3.5 3.25a.75.75 0 0 1 0 1.1l-3.5 3.25a.75.75 0 1 1-1.02-1.1l2.1-1.95H2.75A.75.75 0 0 1 2 10Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </a>
                </div>
            </div>
            <div class="right-content" style="margin-top: 5vh;"></div>
        </section>

        <section class="about-us-section " style="margin-top: 6em;">
            <div class="index-title">
                <h1>
                    Sobre Nós
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6" style="width: 40px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                    </svg>
                </h1>
            </div>
            <ul>
                <li class="about-us-item">
                    <div class="about-us-item-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-5.5-2.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10 12a5.99 5.99 0 0 0-4.793 2.39A6.483 6.483 0 0 0 10 16.5a6.483 6.483 0 0 0 4.793-2.11A5.99 5.99 0 0 0 10 12Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="about-us-item-text">
                        <h1>Quem Somos?</h1>
                        <p>Açaí Amazônia Ipatinga, distribuidora de <strong>Cremes e Polpas de Açaí</strong>.</p>
                        <p>
                            Vendemos também
                            <strong>Picolés</strong>, <strong>Sorvetes</strong>, <strong>Adicionais Variados para Açaí e Sorvete</strong>
                            e <strong>Outros tipos de Cremes.</strong>
                        </p>
                        <p>Ofereçemos apenas produtos com qualidade comprovada.</p>
                    </div>
                </li>

                <li class="about-us-item">
                    <div class="about-us-item-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path d="M14.916 2.404a.75.75 0 0 1-.32 1.011l-.596.31V17a1 1 0 0 1-1 1h-2.26a.75.75 0 0 1-.75-.75v-3.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.5a.75.75 0 0 1-.75.75h-3.5a.75.75 0 0 1 0-1.5H2V9.957a.75.75 0 0 1-.596-1.372L2 8.275V5.75a.75.75 0 0 1 1.5 0v1.745l10.404-5.41a.75.75 0 0 1 1.012.319ZM15.861 8.57a.75.75 0 0 1 .736-.025l1.999 1.04A.75.75 0 0 1 18 10.957V16.5h.25a.75.75 0 0 1 0 1.5h-2a.75.75 0 0 1-.75-.75V9.21a.75.75 0 0 1 .361-.64Z" />
                    </svg>
                    </div>
                    <div class="about-us-item-text">
                        <h1>Nossa Produção</h1>
                        <p>Nossa Fábrica está localizada na cidade de <strong>*******</strong>.</p>
                        <p>
                            Contamos com todas as <strong>Alvarás e Licenças </strong> exigidos pela 
                            <strong>Vigilância Sanitária</strong>, <strong>Ministério da Agricultura</strong> 
                            e demais orgãos reguladores.
                        </p>
                        <p>
                            Nossa Produção é <strong>supervisionada</strong> por <strong>Engenheiro de Alimentos</strong> altamente qualificado.
                        </p>
                    </div>
                </li>

                <li class="about-us-item">
                    <div class="about-us-item-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="m9.69 18.933.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 0 0 .281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 1 0 3 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 0 0 2.273 1.765 11.842 11.842 0 0 0 .976.544l.062.029.018.008.006.003ZM10 11.25a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="about-us-item-text">
                        <h1>Localização</h1>
                        <p>Nosso Depósito está localizado na <strong>Rua ******, *** - ******, ****, ****</strong>.</p>
                    </div>
                </li>

                <li class="about-us-item">
                    <div class="about-us-item-svg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path d="M6.5 3c-1.051 0-2.093.04-3.125.117A1.49 1.49 0 0 0 2 4.607V10.5h9V4.606c0-.771-.59-1.43-1.375-1.489A41.568 41.568 0 0 0 6.5 3ZM2 12v2.5A1.5 1.5 0 0 0 3.5 16h.041a3 3 0 0 1 5.918 0h.791a.75.75 0 0 0 .75-.75V12H2Z" />
                            <path d="M6.5 18a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3ZM13.25 5a.75.75 0 0 0-.75.75v8.514a3.001 3.001 0 0 1 4.893 1.44c.37-.275.61-.719.595-1.227a24.905 24.905 0 0 0-1.784-8.549A1.486 1.486 0 0 0 14.823 5H13.25ZM14.5 18a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                        </svg>
                    </div>
                    <div class="about-us-item-text">
                        <h1>Entregas</h1>
                        <p>
                            Atendemos e Entregamos em toda Região do  <strong>**** ** ***</strong>, <strong>**** ** *** ****</strong>, <strong></strong> e <strong>Região</strong>.
                        </p>
                        <p>Realizamos entregas tanto para <strong>Sua Loja</strong> como para <strong>Consumo Próprio</strong>*.</p>
                        
                    </div>
                </li>
            </ul>

            <p style="text-align: center; margin-top: 1em;">
                * Entrega em Domicílio Apenas em <strong>********</strong>.
            </p>

        </section>

        <section class="feature-products">
            <div class="index-title feature-list-title">
                <h1>Produtos em <br> Destaque</h1>
            </div>
            <ul class="feature-box">
                <?php 
                    featureItens();
                ?>
                
            </ul>
        </section>

    </main>
    
    <footer>
        <ul>
            <li>
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Endereço: 
                </strong>
                <a href="#">
                    <p>Endereço Google Maps</p>
                </a>
            </li>

            <li>
                <strong>
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079684/instagram-icon_wjguqu.png" alt="instagram logo">
                    Instagram: 
                </strong>
                <a href="#" target="_blank">
                    <p>@Instagram</p>
                </a>
            </li>

            <li>
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    Telefone: 
                </strong>
                <a href="tel:31957401232" target="_blank">
                    <p>Telefone Aqui</p>
                </a>
            </li>

            <li>
                <strong>
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079686/whatsapp-icon_ketibs.png" alt="whatsapp logo">
                    WhatsApp: 
                </strong>
                <a href="#" target="_blank">
                    <p>WhatsApp Aqui</p>
                </a>
            </li>

            <li style="color: rgb(238, 224, 250); opacity: 0.8;">
                2025 &copy; Açaí Amazônia Ipatinga. <br> Todos os direitos reservados
            </li>
            <li>
                <strong>
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079684/github-icon_xab4gh.png" alt="">
                    Desenvolvido Por:
                </strong>
                <a href="github.com/luizgustavo1001" target="_blank">
                    <p>Luiz Gustavo</p>
                </a>
            </li>
        </ul>
    </footer>
</body>
</html>
