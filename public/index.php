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
        ") or die("var query (index.php): " . $mysqli->errno);
        
        // verify if the product version exists on the database
        while($row = $query->fetch_assoc()){
            $verifyVersions = $mysqli->prepare("SELECT nameProduct FROM product_version WHERE idProduct = ?") or die("var verifyVersions (index.php): " . $mysqli->errno);
            $verifyVersions->bind_param("i", $row["idProduct"]);

            $verifyVersions->execute();
            $result = $verifyVersions->get_result();
            $amountVersion = $result->num_rows;

            if($amountVersion > 0) getProductByName($row["altName"], "index");
        }
    }

    $aboutUsMap = [
        [
            "icon" => getIcon("userLove"),
            "title" => "Quem Somos?",
            "text" => [
                "Açaí Amazônia Ipatinga, <strong>distribuidora de cremes e polpas variados</strong>.",
                "Vendemos também <strong>picolés, sorvetes, adicionais variados para açaí e sorvete e outros tipos de cremes</strong>.",
                "Ofereçemos apenas produtos com qualidade comprovada."
            ]
        ],
        [
            "icon" => getIcon("industry"),
            "title" => "Produção",
            "text" => [
                "Nossa fábrica está localizada na cidade de <strong>*******</strong>.",
                "Contamos com todas as <strong>alvarás e licenças</strong> exigidos pela <strong>Vigilância Sanitária</strong>, <strong>Ministério da Agricultura</strong> e demais orgãos reguladores. ",
                "Nossa produção é supervisionada por <strong>engenheiro de alimentos</strong> altamente qualificado."
            ]
        ],
        [
            "icon" => getIcon("truck"),
            "title" => "Entregas",
            "text" => [
                "Atendemos e entregamos no <strong>**** ** ***, **** ** *** ****, e região</strong>.",
                "Realizamos entregas tanto para sua loja como para consumo próprio*.",
            ]
        ],
        [
            "icon" => getIcon("maps"),
            "title" => "Endereço",
            "text" => [
                "Nosso depósito está localizado na <strong>Rua ******, *** - ******, ****, ****</strong>.",
            ]
        ],
    ];

    function fillAboutUs($map){
        foreach($map as $item){
            $iconBg = getIcon("iconBg");
            echo "
                <div class='item'>
                    <div class='title'>
                        <div class='icon-wrapper'>
                            {$iconBg}
                            {$item['icon']}
                        </div>
                        <p>{$item['title']}</p>
                    </div>

                    <div class='text'>";
                        foreach($item['text'] as $text){ echo "<p>{$text}</p>"; }
            echo "  </div>
                </div>
            ";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("index") ?>">

    <?php displayFavicon() ?>

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
                        <?php echo getIcon("userOuter")?>
                        <p>@acai.amazonia</p>
                    </span>
                    <?php echo getIcon("3-points")?>
                </div>

                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1774993222/box_t5uo6g.png" alt="açaí box image">

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
            <div class="section-title"><h1>Sobre nós</h1></div>

            <div class="container">
                <?php fillAboutUs($aboutUsMap) ?>
            </div>
        </section>

        <div class="horizontal-line"></div>

        <section class="features">
            <div class="section-title"><h1>Destaques</h1></div>

            <div class='container'>
                <?php featureItems() ?>
            </div>
        </section>
    </main>

    <?php displayFooter() ?>

    <script src="/js/general.js"></script>
</body>
</html>