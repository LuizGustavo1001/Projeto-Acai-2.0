<?php 
    include "../databaseConnection.php";
    include "generalPHP.php";
    include "footerHeader.php";
    function featureItens(){
        // feature 4 random products from Database
        global $mysqli;
        $query = $mysqli->query("SELECT altName FROM product_data ORDER BY RAND() LIMIT 4");
        if($query){
            while($row = $query->fetch_assoc()){
                getProductByName($row["altName"], "index");
            }
        } else {
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

    <link rel="stylesheet" href="CSS/general.css">
    <link rel="stylesheet" href="CSS/index.css">

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="JS/generalScripts.js"></script>
    
    <?php faviconOut()?>
    
    <title>Açaí e Polpas Amazônia</title>

</head>
<body>
    
    <?php headerOut(0)?>
    <main>
        <?php 
            if(isset($_GET["orderConfirmed"])){
                echo "
                    <section class= \"popup-box show\">
                        <div class=\"popup-div\">
                            <div><h1>Pedido Confirmado</h1></div>
                            <div>
                                <p>Pedido no nome de <strong>$_SESSION[userMail]</strong> foi enviado para nossa central</p>
                                <p>Clique no botão abaixo para fechar esta janela</p>
                                <button class=\"popup-button\">Fechar</button>
                            </div>
                        </div>
                    </section>
                ";
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
            
            if(isset($_GET["notAdmin"])){
                echo "
                    <section class= \"popup-box show\">
                        <div class=\"popup-div\">
                            <div><h1>Erro</h1></div>
                            <div>
                                <p>É preciso fazer <strong>Login como Administrador</strong> para acessar a Página de Gerenciamento</p>
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
                    Açaí e Polpas <br>
                    <span style="color: #cc0088">Amazônia</span>
                </h1>
                <p>Qualidade Superior, Preço Inferior</p>
                <div>
                    <button type="button" onclick="window.location.href='products/products.php'">
                        Compre Agora
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" style="width: 25px;">
                            <path fill-rule="evenodd" d="M2 10a.75.75 0 0 1 .75-.75h12.59l-2.1-1.95a.75.75 0 1 1 1.02-1.1l3.5 3.25a.75.75 0 0 1 0 1.1l-3.5 3.25a.75.75 0 1 1-1.02-1.1l2.1-1.95H2.75A.75.75 0 0 1 2 10Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="right-content" style="margin-top: 5vh;"></div>
        </section>

        <section class="about-us-section " style="margin-top: 6em;">
            <div class="index-title">
                <h1>
                    Sobre Nós
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
                        <p>Açaí e Polpas Amazônia, distribuidora de <strong>Cremes e Polpas Variadas</strong>.</p>
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
                * Entregas Domiciliares Apenas em <strong>********</strong>.
            </p>

        </section>

        <section class="feature-products">
            <div class="index-title feature-list-title">
                <h1>Destaques</h1>
            </div>
            <ul class="products-list">
                <?php featureItens() ?>
            </ul>
        </section>
    </main>
    <?php footerOut() ?>
</body>
</html>