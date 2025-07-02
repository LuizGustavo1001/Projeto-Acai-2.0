<?php 
    include "../databaseConnection.php";
    include "../generalPHP.php";

    if(! isset($_SESSION)){
        session_start();

    }

    if(! isset($_SESSION["clientMail"])){
        header("location: ../account/account.php");
        exit();
    }

    function GetSubtotal(){
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT SUM(totPrice) as subtotal FROM product_order WHERE idOrder = ?");
        $stmt->bind_param("i", $_SESSION['idOrder']);

        if($stmt->execute()){
            $result = $stmt->get_result()->fetch_assoc();
            $subtotal = $result["subtotal"] !== null ? number_format($result["subtotal"], 2, ',', '.') : '00,00';
            echo "<span>R$ {$subtotal}</span>";
        }
    }


    checkSession();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080377/iconeAcai_mj7dqy.ico" type="image/x-icon">

    <link rel="stylesheet" href="../styles/general-style.css">
    <link rel="stylesheet" href="../styles/cart.css">

    <title>Açaí Amazônia Ipatinga - Carrinho</title>
</head>
<body>

    <header>
        <ul class="left-header">
            <li class="acai-icon">
                <a href="../index.php">
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079683/acai-icon-white_fll4gt.png" class="item-translate" alt="Açaí Icon">
                </a>
                <p>Açaí Amazônia Ipatinga</p>
            </li>
        </ul>

        <ul class="right-header">
            <li>
                <a href="../account/account.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>

                    <p><span>Minha</span> Conta</p>
                </a>
            </li>
            <li>
                <a href="../products/products.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>

                    <p>Produtos</p>
                </a>
            </li>
            <li>
                <a href="cart.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>

                <p>Carrinho</p>
                </a>
                <?php verifyCartAmount();?>
            </li>
        </ul>

    </header>

    <main class="mobile-main">
        <section class="cart-header section-header-title">
            <h1>Carrinho</h1>
            <p>
                Caso Algum de seus Dados Pessoas abaixo estejam incorretos clique no botão 
                <strong>"Editar"</strong>
            </p>
            <p style="padding-top: 0.5em;">Clique em <strong>Confirmar Pedido</strong> para efetuá-lo</p>
        </section>

        <section class="order-info section-bg">
            <div class="order-info-header">
                <h1>Informações do Cliente</h1>
                <a href="../account/account.php">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Editar
                    </div>
                </a>
            </div>
        
            <div class="order-info-content">
                <ol>
                    <li>
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475315/user_iqkn7x.png" alt="user icon">
                        <ul class="list-item-text">
                            <li>Cliente:</li>
                            <li> <span><?php echo $_SESSION["clientName"]?></span> </li>
                        </ul>
                    </li>

                    <li>
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475314/pin_zqdhx7.png" alt="maps pin icon">
                        <ul class="list-item-text">
                            <li>Endereço:</li>
                            <li> 
                                <span>
                                    <?php echo $_SESSION["street"] . ", " . $_SESSION["localNum"] . " - " . $_SESSION['city'] . "<br> <em>". $_SESSION["referencePoint"] . "</em>"?> 
                                </span> 
                            </li>
                        </ul>
                    </li>

                    <li>
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475315/phone_plvmle.png" alt="phone icon">
                        <ul class="list-item-text">
                            <li>Telefone:</li>
                            <li> <span><?php echo $_SESSION["clientNumber"]?></span> </li>
                        </ul>
                    </li>
                </ol>
            </div>
         </section>
        
        <section class="order-review section-bg">
            <h1>Revisão dos Itens</h1>  
            <ol>
                <li>
                    <div style="position: relative; display: inline-block;">
                        <div class="item-amount">Q</div>
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="">
                    </div>
                    <ul>
                        <li><strong>Nome Produto</strong></li>
                        <li class="price">R$ 00,00</li>
                        <li class="price"><strong>Total: R$00,00</strong></li>
                    </ul>
                    <a href="">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </a>
                </li>
            </ol>
        </section>

        <section class="order-summary section-bg">
                <h1>Resumo do Pedido</h1>
                <ol>
                    <li class="list-item-text">
                        <ul>
                            <li>Subtotal:</li>
                            <?php GetSubtotal();?>
                        </ul>
                    </li>
                    <li class="list-item-text">
                        <ul>
                            <li>Taxa de Entrega:</li>
                            <li><span>R$ 00,00</span></li>
                        </ul>
                    </li>
                    <li class="list-item-text">
                        <ul>
                            <li><strong>Total:</strong></li>
                            <li class="priceTot"><strong>R$ 00,00</strong></li>
                        </ul>
                    </li>
                </ol>

                <button><a href="">Confirmar Pedido</a></button>

        </section>


    </main>

    <main class="desktop-main">
        <section class="cart-header section-header-title">
            <h1>Carrinho</h1>
            <p>
                Caso Algum de seus Dados Pessoas abaixo estejam incorretos clique no botão 
                <strong>"Editar"</strong>
            </p>
            <p style="padding-top: 0.5em;">Clique em <strong>Confirmar Pedido</strong> para efetuá-lo</p>
        </section>

        <section class="desktop-hero">
            <div>
                <div class="order-info section-bg">
                    <div class="order-info-header">
                        <h1>Informações do Cliente</h1>
                        <a href="../account/account.php">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                Editar
                            </div>
                        </a>
                    </div>
                
                    <div class="order-info-content">
                        <ol>
                            <li>
                                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475315/user_iqkn7x.png" alt="user icon">
                                <ul class="list-item-text">
                                    <li>Cliente:</li>
                                    <li> <span><?php echo $_SESSION["clientName"]?></span> </li>
                                </ul>
                            </li>
                            
                            <li>
                                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475314/pin_zqdhx7.png" alt="maps pin icon">
                                <ul class="list-item-text">
                                    <li>Endereço:</li>
                                    <li> 
                                        <span>
                                            <?php echo $_SESSION["street"] . ", " . $_SESSION["localNum"] . " - " . $_SESSION['city'] . "<br> <em>". $_SESSION["referencePoint"] . "</em>"?> 
                                        </span> 
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475315/phone_plvmle.png" alt="phone icon">
                                <ul class="list-item-text">
                                    <li>Telefone:</li>
                                    <li> <span><?php echo $_SESSION["clientNumber"]?></span> </li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="order-review section-bg">
                    <h1>Revisão dos Itens</h1>
                    <ol>
                        <li>
                            <div style="position: relative; display: inline-block;">
                                <div class="item-amount">Q</div>
                                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="">
                            </div>
                            <ul>
                                <li><strong>Nome Produto</strong></li>
                                <li class="price">R$ 00,00</li>
                                <li class="price"><strong>Total: R$00,00</strong></li>
                            </ul>
                            <a href="">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </a>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="section-bg order-summary">
                <div class="">
                    <h1>Resumo do Pedido</h1>
                    <ol>
                        <li class="list-item-text">
                            <ul>
                                <li>Subtotal:</li>
                                <?php GetSubtotal();?>
                            </ul>
                        </li>
                        <li class="list-item-text">
                            <ul>
                                <li>Taxa de Entrega:</li>
                                <li><span>R$ 00,00</span></li>
                            </ul>
                        </li>
                        <li class="list-item-text">
                            <ul>
                                <li><strong>Total:</strong></li>
                                <li><strong>R$ 00,00</strong></li>
                            </ul>
                        </li>
                    </ol>
                </div>
                <div class="button-submit">
                    <button>Confirmar Pedido</button>
                </div>
            </div>

        </section>

    </main>


    <!--

    <main class="desktop-main">
        <section class="cart-header">
            <h1>Carrinho</h1>
            <p>
                Caso Algum de seus Dados Pessoas abaixo estejam incorretos clique no botão 
                <strong>"Editar"</strong>
            </p>
        </section>
        
    </main>
-->
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