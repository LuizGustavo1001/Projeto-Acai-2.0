<?php require_once __DIR__ . '/../src/controller/indexController.php'; ?>

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