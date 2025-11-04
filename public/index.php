<?php 
    include "../databaseConnection.php";
    include "generalPHP.php";
    include "footerHeader.php";
    include "printStyles.php";
    function featureItems(){
        // feature 4 random products from Database
        global $mysqli;

        // query to avoid products without versions
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
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?php printStyle("0", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("0", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("0", "index") ?>">
    
    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="JS/generalScripts.js"></script>
    
    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia</title>

</head>
<body>
    <?php displayHeader(0)?>
    <main>
    <!-- Pop Up Box -->
    <?php 
        if(isset($_GET["orderConfirmed"])){
            displayPopUp("orderConfirmed", "");
            verifyOrders();
        }else if(isset($_GET["loginSuccess"]))
            displayPopUp("loginSuccess", "");
        else if(isset($_GET["notAdmin"]))
            displayPopUp("notAdmin", "");
    ?>
    <!-- Pop Up Box -->

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
                    <?php featureItems()?>
                    
                </ul>
            </div>
        </section>
    </main>
    

    <?php displayFooter()?>
</body>
</html>