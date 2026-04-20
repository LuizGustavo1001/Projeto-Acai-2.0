<?php require_once __DIR__ . '/../../src/controller/products/productsController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("products") ?>">

    <?php displayFavicon()?>
    
    <title>Açaí e Polpas Amazônia | Produtos</title>
</head>
<body>
    <div class="dazzles-bg fade-in mobile"></div>

    <?php displayHeader("product")?>

    <main>
        <section class="title-hero rise-above" style="--time-outer: 0.5s">
            <h1>Nossos Produtos</h1>
            <p>
                Clique em <strong>Adicionar ao carrinho</strong> para realizar sua compra <br>
                <em>Preços podem variar com o tempo</em>.
            </p>
        </section>

        <section class="hero rise-above">
            <div class="filter-area">

                <form method="GET" class="outer-input-box">
                    <label for="inameProd"> <?php echo getIcon("search") ?> </label>
                    <input type="text" name="nameProd" id="inameProd" placeholder="<?= htmlspecialchars($_GET['nameProd'] ?? 'Nome do Produto') ?>">
                </form>

                <details class="regular-details" aria-label="sort button">
                    <summary class="icon-btn outer-color" aria-label="clique aqui para filtrar os produtos"> 
                        <?php echo getIcon("sort") ?> 
                    </summary>

                    <nav>
                        <a href="products.php?filter=idProd">Identificador</a>
                        <a href="products.php?filter=nameAsc">(A-Z)</a>
                        <a href="products.php?filter=nameDesc">(Z-A)</a>
                        <a href="products.php?filter=priceAsc">Menor preço</a>
                        <a href="products.php?filter=priceDesc">Maior preço</a>
                    </nav>
                </details>
            </div>

            <div class="container">
                <?php 
                    if(isset($_GET["nameProd"])){ echo searchOutput($_GET["nameProd"]); } 

                    displayCategories($mapCategories);
                ?>
            </div>
        </section>
    </main>

    <?php displayFooter()?>

    <script src="/js/general.js"></script>
</body>
</html>