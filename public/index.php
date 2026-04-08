<?php 
    require_once __DIR__ . '/../databaseConnection.php';
    require_once "generalPHP.php";
    require_once "footerHeader.php";
    require_once "printStyles.php";

    // feature 6 random products from Database
    function featureItems(){
        global $mysqli;

        // just products with versions
        $query = $mysqli->query("
            SELECT pd.idProduct, pd.altName
            FROM product_data AS pd 
                JOIN product_version AS pv ON pd.idProduct = pv.idProduct
            GROUP BY pd.idProduct
            ORDER BY RAND() LIMIT 6
        ") or die($mysqli->errno);
        
        // verify if the product version exists on the database
        while($row = $query->fetch_assoc()){
            $verifyVersions = $mysqli->prepare("SELECT nameProduct FROM product_version WHERE idProduct = ?") or die($mysqli->errno);
            $verifyVersions->bind_param("i", $row["idProduct"]);
            $verifyVersions->execute();

            $result = $verifyVersions->get_result();
            $amountVersion = $result->num_rows;

            if($amountVersion > 0) getProductByName($row["altName"], "index");
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("index") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Página Inicial</title>
</head>
<body>
    <div class="dazzles-bg fade-in mobile"></div>

    <?php displayHeader() ?>

    <main>
        <section class="hero rise-above">
            <div class="title">
                <h1>Açaí e Polpas <br> <span>Amazônia</span></h1>
                <p>Qualidade Superior, preço inferior</p>
                <a href="products/products.php" class="regular-btn">Compre Agora</a>
            </div>

            <div class="image">
                <div class="top">
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9999 6C9.79077 6 7.99991 7.79086 7.99991 10C7.99991 12.2091 9.79077 14 11.9999 14C14.209 14 15.9999 12.2091 15.9999 10C15.9999 7.79086 14.209 6 11.9999 6ZM17.1115 15.9974C17.8693 16.4854 17.8323 17.5491 17.1422 18.1288C15.7517 19.2966 13.9581 20 12.0001 20C10.0551 20 8.27215 19.3059 6.88556 18.1518C6.18931 17.5723 6.15242 16.5032 6.91351 16.012C7.15044 15.8591 7.40846 15.7251 7.68849 15.6097C8.81516 15.1452 10.2542 15 12 15C13.7546 15 15.2018 15.1359 16.3314 15.5954C16.6136 15.7102 16.8734 15.8441 17.1115 15.9974Z" fill="currentColor"/>
                        </svg>
                        <p>@acai.amazonia</p>
                    </span>
                    <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <?php echo getIcon("3-points")?>
                    </svg>
                </div>
                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1774993222/box_t5uo6g.png" alt="asdasdsad">
                <div class="bottom">
                    <span>
                        <?php 
                            echo getIcon("heartFilled");
                            echo getIcon("chat");
                            echo getIcon("send");
                        ?>
                    </span>
                    <?php echo getIcon("bookmark") ?>
                </div>
            </div>
        </section>

        <div class="horizontal-line"></div>

        <section class="about-us">
            <div class="section-title">
                <h1>Sobre nós</h1>
            </div>

            <div class="container">
                <div class="item">
                    <div class="title">
                        <div class="icon-box">
                            <?php echo getIcon("iconBg")?>
                            <?php echo getIcon("userLove")?>
                        </div>
                        <p>Quem Somos?</p>
                    </div>
                    <div class="text">
                        <p>Açaí Amazônia Ipatinga, distribuidora de <strong>cremes e polpas variados</strong>.</p>
                        <p>Vendemos também <strong>picolés, sorvetes, adicionais variados para açaí e sorvete e outros tipos de cremes</strong>. <br></p>
                        <p>Ofereçemos apenas produtos com qualidade comprovada.</p>
                    </div>
                </div>

                <div class="item">
                    <div class="title">
                        <div class="icon-box">
                            <?php echo getIcon("iconBg")?>
                            <?php echo getIcon("industry")?>
                        </div>
                        <p>Produção</p>
                    </div>
                    <div class="text">
                        <p>Nossa fábrica está localizada na cidade de <strong>*******</strong>.</p>
                        <p>
                            Contamos com todas as <strong>alvarás e licenças</strong> exigidos pela <strong>Vigilância Sanitária</strong>, <strong>Ministério da Agricultura</strong> e demais orgãos reguladores.
                        </p>
                        <p>Nossa produção é <strong>supervisionada por engenheiro de alimentos</strong> altamente qualificado.</p>
                    </div>
                </div>

                <div class="item">
                    <div class="title">
                        <div class="icon-box">
                            <?php echo getIcon("iconBg")?>
                            <?php echo getIcon("truck")?>
                            <svg class='icon-bg' viewBox='0 0 250 250' fill='none' xmlns='http://www.w3.org/2000/svg' aria-label='icon-bg'>
                                <path d='M77.4903 246.66C83.6098 244.613 89.1887 241.685 95.3897 239.168C109.056 233.67 124.206 233.131 138.226 237.643C149.445 241.389 160.021 246.23 171.75 248.205C186.763 250.743 203.204 251.409 217.442 244.531C232.241 237.366 243.491 222.955 247.948 207.204C254.73 183.223 242.736 159.969 235.709 137.606C231.313 123.655 236.025 112.673 241.43 99.9103C248.682 82.8076 253.047 60.1883 247.387 42.0928C244.408 32.8762 239.299 24.4977 232.476 17.6421C225.653 10.7864 217.311 5.64808 208.13 2.6471C191.037 -2.68533 169.537 1.01974 153.188 7.58037C146.61 10.221 140.449 14.0079 133.504 15.6455C126.055 17.3106 118.276 16.6279 111.229 13.6907C89.923 5.04209 64.3335 -4.83468 41.0286 2.6471C26.3113 7.37567 13.899 18.3066 6.68828 31.9294C-2.14412 48.4896 -1.17521 67.2197 3.53677 84.916C7.11665 98.3853 16.408 111.312 16.3978 125.293C16.3978 133.88 13.0627 139.95 9.85 147.646C2.30268 165.711 -3.40881 189.528 2.60865 208.78C5.43498 217.752 10.4064 225.894 17.0891 232.495C23.7718 239.096 31.9626 243.955 40.947 246.649C49.8764 249.377 59.2899 250.124 68.5355 248.84C71.5815 248.394 74.5794 247.664 77.4903 246.66Z' fill='currentColor'/>
                            </svg>
                            
                        </div>
                        <p>Entregas</p>
                    </div>
                    <div class="text">
                        <p>Atendemos e entregamos no <strong>**** ** ***, **** ** *** ****</strong>, e região.</p>
                        <p>Realizamos entregas tanto para <strong>sua loja</strong> como para <strong>consumo próprio*</strong>.</p>
                    </div>
                </div>

                <div class="item">
                    <div class="title">
                        <div class="icon-box">
                            <?php echo getIcon("iconBg")?>
                            <?php echo getIcon("maps")?>
                        </div>
                        <p>Endereço</p>
                    </div>
                    <div class="text">
                        <p>Nosso depósito está localizado na <strong>Rua ******, *** - ******, ****, ****</strong>.</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="horizontal-line"></div>

        <section class="features">
            <div class="section-title">
                <h1>Destaques</h1>
            </div>

            <div class='container'>
                <?php featureItems()?>
            </div>
        </section>
    </main>

    <?php displayFooter()?>

    <script src="/js/script.js"></script>
</body>
</html>
