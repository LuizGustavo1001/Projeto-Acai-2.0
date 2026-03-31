<?php 
    require_once __DIR__ . '/../databaseConnection.php';
    require_once "generalPHP.php";
    require_once "footerHeader.php";
    require_once "printStyles.php";

    // feature 4 random products from Database
    function featureItems(){
        global $mysqli;

        // just products with versions
        $query = $mysqli->query("
            SELECT pd.idProduct, pd.altName
            FROM product_data AS pd 
                JOIN product_version AS pv ON pd.idProduct = pv.idProduct
            GROUP BY pd.idProduct
            ORDER BY RAND() LIMIT 4
        ");
        if($query){
            // verify if the product version exists on the database
            while($row = $query->fetch_assoc()){
                $verifyVersions = $mysqli->prepare("SELECT nameProduct FROM product_version WHERE idProduct = ?");
                $verifyVersions->bind_param("i", $row["idProduct"]);
                $verifyVersions->execute();

                $result = $verifyVersions->get_result();
                $amountVersion = $result->num_rows;
                if($amountVersion > 0)
                    getProductByName($row["altName"], "index");
            }
        }else
            header("location: errorPage.php");
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("index") ?>">
    <!--
    <link rel="stylesheet" href="<?php printStyle("general") ?>">
    -->
    <script src="/js/generalScripts.js"></script>
    
    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia</title>

</head>
<body>
    <div class="dazzles-bg mobile"></div>

    <?php displayHeader();?>

    <main>
        <div class="hero">
            <div class="title">
                <h1>Açaí e Polpas <br> <span>Amazônia</span></h1>
                <p>Qualidade Superior, preço inferior</p>
                <a href="products/products.php" class="regular-btn">Compre Agora</a>
            </div>

            <div class="image">
                <div class="top">
                    <span>
                        <svg viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1" y="1" width="39" height="39" rx="19.5" fill="currentColor"/>
                            <rect x="1" y="1" width="39" height="39" rx="19.5" stroke="#currentColor" stroke-width="2"/>
                            <g clip-path="url(#clip0_572_1978)">
                                <path d="M16.2852 14.2369L17.0862 18.6794L21.8573 18.4704C21.8573 18.4704 21.7438 14.4643 22.0314 12.5122C22.3191 10.56 22.9049 9.60239 24.2777 8C20.5656 8.31311 18.1058 8.69353 16.2852 14.2369Z" fill="#89475F"/>
                                <path d="M11.4961 10.3867C9.48729 15.1368 8.77213 17.1725 13.7075 20.5261L18.8791 19.6202C17.9213 16.7628 17.839 14.7929 15.0482 13.4181C13.0268 12.6362 12.2092 11.9982 11.4961 10.3867Z" fill="#89475F"/>
                                <path d="M19.1229 32.5884C23.4506 32.5884 26.9588 29.0802 26.9588 24.7526C26.9588 20.425 23.4506 16.9167 19.1229 16.9167C14.7953 16.9167 11.2871 20.425 11.2871 24.7526C11.2871 29.0802 14.7953 32.5884 19.1229 32.5884Z" fill="#89475F"/>
                                <path d="M24.9383 25.6058C28.3042 25.6058 31.0328 22.8772 31.0328 19.5113C31.0328 16.1454 28.3042 13.4167 24.9383 13.4167C21.5724 13.4167 18.8438 16.1454 18.8438 19.5113C18.8438 22.8772 21.5724 25.6058 24.9383 25.6058Z" fill="#89475F"/>
                            </g>
                        </svg>
                        <p>@acai.amazonia</p>
                    </span>
                    <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_572_1981)">
                            <path d="M14.9995 6.45598C16.7822 6.45598 18.2275 5.01076 18.2275 3.22799C18.2275 1.44522 16.7822 0 14.9995 0C13.2167 0 11.7715 1.44522 11.7715 3.22799C11.7715 5.01076 13.2167 6.45598 14.9995 6.45598Z" fill="currentColor"/>
                            <path d="M14.9995 18.2279C16.7822 18.2279 18.2275 16.7827 18.2275 15C18.2275 13.2172 16.7822 11.772 14.9995 11.772C13.2167 11.772 11.7715 13.2172 11.7715 15C11.7715 16.7827 13.2167 18.2279 14.9995 18.2279Z" fill="currentColor"/>
                            <path d="M14.9995 29.9999C16.7822 29.9999 18.2275 28.5547 18.2275 26.7719C18.2275 24.9892 16.7822 23.5439 14.9995 23.5439C13.2167 23.5439 11.7715 24.9892 11.7715 26.7719C11.7715 28.5547 13.2167 29.9999 14.9995 29.9999Z" fill="currentColor"/>
                        </g>
                    </svg>
                </div>
                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1774993222/box_t5uo6g.png" alt="asdasdsad">
                <div class="bottom">
                    <span>
                        <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.1328 22.8086C6.62274 20.1042 2.5 16.9278 2.5 11.4214C2.5 5.34276 9.3752 1.03189 15 6.87585L17.5 9.37416C17.8661 9.74021 18.4598 9.7401 18.8259 9.37391C19.1919 9.00774 19.1917 8.41415 18.8256 8.0481L16.4106 5.63396C21.7106 1.75393 27.5 5.84338 27.5 11.4214C27.5 16.9278 23.3772 20.1042 19.8671 22.8086C19.5024 23.0897 19.1441 23.3657 18.7979 23.6387C17.5 24.6618 16.25 25.6251 15 25.6251C13.75 25.6251 12.5 24.6618 11.2022 23.6387C10.8559 23.3657 10.4977 23.0897 10.1328 22.8086Z" fill="currentColor"/>
                        </svg>
                        
                        <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.25 4.17228C19.4114 3.10871 17.2768 2.5 15 2.5C8.09644 2.5 2.5 8.09644 2.5 15C2.5 16.9996 2.96952 18.8895 3.80432 20.5656C4.02617 21.011 4.10001 21.5201 3.9714 22.0007L3.22689 24.7834C2.90369 25.9913 4.00876 27.0963 5.21669 26.7731L7.99924 26.0286C8.47991 25.9 8.98901 25.9739 9.43441 26.1956C11.1105 27.0305 13.0004 27.5 15 27.5C21.9035 27.5 27.5 21.9035 27.5 15C27.5 12.7232 26.8913 10.5886 25.8278 8.75" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25 5L16.25 26.25L12.5 17.5M25 5L15 9.11765M25 5L12.5 17.5M12.5 17.5L3.75 13.75L8.75 11.6912" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>

                    <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.75 13.8719V20.1136C3.75 23.9844 3.75 25.9198 4.66764 26.7654C5.10528 27.1688 5.65769 27.4221 6.24615 27.4894C7.48003 27.6306 8.92094 26.3561 11.8027 23.8073C13.0765 22.6806 13.7135 22.1173 14.4504 21.9689C14.8132 21.8958 15.1868 21.8958 15.5496 21.9689C16.2865 22.1173 16.9235 22.6806 18.1973 23.8073C21.0791 26.3561 22.52 27.6306 23.7539 27.4894C24.3424 27.4221 24.8947 27.1688 25.3324 26.7654C26.25 25.9198 26.25 23.9844 26.25 20.1136V13.8719C26.25 8.51114 26.25 5.83075 24.6025 4.16538C22.955 2.5 20.3032 2.5 15 2.5C9.6967 2.5 7.04505 2.5 5.39752 4.16538C4.3885 5.18534 3.99745 6.58601 3.8459 8.75" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M18.75 7.5H11.25" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="horizontal-line"></div>

    </main>


    <?php displayFooter(); ?>

    <!--
    <?php //displayHeader(0)?>
    <main>

    <?php 
        if(isset($_GET["orderConfirmed"])){
            displayPopUp("orderConfirmed", "");
            verifyOrders();
        }else if(isset($_GET["loginSuccess"]))
            displayPopUp("loginSuccess", "");
        else if(isset($_GET["notAdmin"]))
            displayPopUp("notAdmin", "");
    ?>



        <section class='index-title'>
            <div class='title-text'>
                <h1>Açaí e Polpas <br> <span>Amazônia</span></h1>
                <p>Qualidade Superior, Preço Inferior</p>
                <a href="products/products.php" class="link-button"><strong>Compre Agora</strong></a>
            </div>

            <div class='title-img'></div>
        </section>


        <section class='index-about-us'>
            <div class='section-title'>
                <h1>Sobre Nós</h1>
            </div>

            <div class='section-hero'>
                <ul>
                    <li>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path d='M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z'/>
                        </svg>
                        <div class='list-text'>
                            <h1>Quem Somos?</h1>
                            <p>
                                Açaí Amazônia Ipatinga, distribuidora de <span>Cremes</span> e <span>Polpas Variadas</span>.
                                <br>
                                Vendemos também <span>Picolés</span>, <span>Sorvetes</span>, <span>Adicionais Variados para Açaí</span> e <span>Sorvete</span> e <span>Outros</span> tipos de <span>Cremes</span>. 
                                <br>
                                Ofereçemos apenas produtos com qualidade comprovada.
                            </p>
                        </div>
                    </li>

                    <li>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path d='M19.006 3.705a.75.75 0 1 0-.512-1.41L6 6.838V3a.75.75 0 0 0-.75-.75h-1.5A.75.75 0 0 0 3 3v4.93l-1.006.365a.75.75 0 0 0 .512 1.41l16.5-6Z'/>
                            <path fill-rule='evenodd' d='M3.019 11.114 18 5.667v3.421l4.006 1.457a.75.75 0 1 1-.512 1.41l-.494-.18v8.475h.75a.75.75 0 0 1 0 1.5H2.25a.75.75 0 0 1 0-1.5H3v-9.129l.019-.007ZM18 20.25v-9.566l1.5.546v9.02H18Zm-9-6a.75.75 0 0 0-.75.75v4.5c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-.75-.75H9Z' clip-rule='evenodd'/>
                        </svg>
                        <div class='list-text'>
                            <h1>Nossa Produção</h1>
                            <p>
                                Nossa Fábrica está localizada na cidade de <span>*******</span>.
                                <br>
                                Contamos com todas as <span>Alvarás e Licenças</span> exigidos pela 
                                <span>Vigilância Sanitária</span>, <span>Ministério da Agricultura </span>e demais orgãos reguladores.
                                <br>
                                Nossa Produção é <span>supervisionada</span> por <span>Engenheiro de Alimentos</span> altamente qualificado.
                            </p>
                        </div>
                    </li>

                    <li>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path d='M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z'/>
                            <path d='M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0ZM15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z'/>
                            <path d='M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z' />
                        </svg>
                        <div class='list-text'>
                            <h1>Entregas</h1>
                            <p>
                                Atendemos e Entregamos no <span>**** ** ***</span>, <span>**** ** *** ****</span>, e <span>Região</span>.
                                <br>
                                Realizamos entregas tanto para <span>Sua Loja</span> como para <span>Consumo Próprio*</span>.
                            </p>
                        </div>
                    </li>

                    <li>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path fill-rule='evenodd' d='m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z' clip-rule='evenodd' />
                        </svg>
                        <div class='list-text'>
                            <h1>Localização</h1>
                            <p>
                                Nosso Depósito está localizado na <span>Rua ******, *** - ******, ****, ****</span>.
                                <br>
                                Venha nos visitar e comprar pessoalmente!
                            </p>
                        </div>
                    </li>
                </ul>
                <p class='disclaimer'>* Entregas Domiciliares Apenas em ********.</p>
            </div>
        </section>


        <section class='index-feature'>
            <div class='section-title'>
                <h1>Destaques</h1>
            </div>

            <div class='section-hero'>
                <ul class='product-list'>
                    <?php //featureItems()?>
                    
                </ul>
            </div>
        </section>
    </main>
    

    <?php //displayFooter()?>
    -->

    <script src="/js/script.js"></script>
</body>
</html>
