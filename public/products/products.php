<?php 
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";

    checkSession();
    function prodSearchOutput($prodName){ 
        // search bar result at products.php
        global $mysqli;
        if($prodName !== ""){
            $getSearchReturn = $mysqli->prepare("
                SELECT 
                    pd.idProduct, 
                    pd.printName, 
                    pd.altName, 
                    pd.brandProduct, 
                    ANY_VALUE(pv.imageURL) AS imageURL,
                    MIN(pv.priceProduct) AS priceProduct,
                    MIN(pv.priceDate) AS priceDate
                FROM product_version AS pv
                INNER JOIN product_data AS pd 
                    ON pv.idProduct = pd.idProduct
                WHERE pd.printName LIKE ?
                GROUP BY 
                    pd.idProduct, pd.printName, pd.altName, pd.brandProduct
            ") or die($mysqli->errno);

            $likeProdName = "%{$prodName}%";
            $getSearchReturn->bind_param("s", $likeProdName);

            $getSearchReturn->execute();

            $searchResult = $getSearchReturn->get_result();
            $amount = $searchResult->num_rows;
            $getSearchReturn->close();

            echo "<li class='products-category'>";

            switch($amount){
                case 0:
                    echo "
                        <div style=\"font-weight: normal;\" class='search-result'>
                            <h1> 
                                Nenhum Produto Encontrado com o Nome:
                                <strong style=\"color: var(--secondary-clr)\"><em>$prodName</em></strong>
                            </h1>
                        </div>
                    ";
                    break;
                
                default:
                    echo "
                        <div style='font-weight: normal; margin-bottom: 2em;'class='search-result'>
                            <h1> 
                                Produtos Encontrados com o filtro:
                                <strong style='color: var(--secondary-clr)'><em>$prodName</em></strong>
                            </h1>
                        </div>
                        <ul class='products'>
                    ";

                    while ($row = $searchResult->fetch_assoc()) {
                        getProductByName($row["altName"], "product");
                    }
                    echo "</ul>";
                break;
            }
        }
    }
    
    function categoryItens($type, $filter){
        // print the products based on the selected filter on HTML
        global $mysqli;

        $getAllTypes = $mysqli->query("SELECT typeProduct FROM product_data") or die($mysqli->errno);
        $allowedTypes = [];
        while($allTypes = $getAllTypes->fetch_assoc()){ $allowedTypes[] = $allTypes["typeProduct"]; }

        $getAllTypes->close();

        if(in_array($type, $allowedTypes)){
            $query = match($filter){
                "nameAsc"       => "SELECT altName FROM product_data WHERE typeProduct = ? ORDER BY altName ASC",
                "nameDesc"      => "SELECT altName FROM product_data WHERE typeProduct = ? ORDER BY altName DESC",
                "priceAsc"      => "SELECT DISTINCT pd.altName, pv.priceProduct FROM product_data AS pd JOIN product_version AS pv ON pd.idProduct = pv.idProduct WHERE typeProduct = ? ORDER BY pv.priceProduct ASC",
                "priceDesc"     => "SELECT DISTINCT pd.altName, pv.priceProduct FROM product_data AS pd JOIN product_version AS pv ON pd.idProduct = pv.idProduct WHERE typeProduct = ? ORDER BY pv.priceProduct DESC",
                default         => "SELECT altName FROM product_data WHERE typeProduct = ?",
            };

            $getProductFilter = $mysqli->prepare($query) or die($mysqli->errno);
            $getProductFilter->bind_param("s", $type);

            $getProductFilter->execute();

            $vector = [];
            $result = $getProductFilter->get_result();
            $getProductFilter->close();

            while($row = $result->fetch_assoc()){
                $vector[] = $row["altName"];
            }
            $vector = array_unique($vector); // removing duplicates

            foreach($vector as $name){ getProductByName($name, ""); }
        }else{
            echo "
            <div class='errorText'>
                <i class=\"fa-solid fa-triangle-exclamation\"></i>
                <p>Erro: Nenhum Produto encontrado com o Tipo Inserido</p>
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
                <form method="GET" class="search-input">
                    <label for="inameProd">
                        <?php echo getIcon("search") ?>
                    </label>

                    <input type="text" name="nameProd" id="inameProd" placeholder="<?= htmlspecialchars($_GET['nameProd'] ?? 'Nome do Produto') ?>">
                </form>
                <details class="sort-btn outer-btn">
                    <summary>
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
                    if(isset($_GET["nameProd"])){
                        echo prodSearchOutput($_GET["nameProd"]);
                        echo "</li>";
                    }
                ?>

                <div class="category">
                    <div class='section-title'>
                        <h1>Cremes</h1>
                    </div>

                    <div class="products">
                        <?php categoryItens("Creme", $_GET["filter"] ?? "noFilter") ?> 
                    </div>
                </div>

                <div class="category">
                    <div class='section-title'>
                        <h1>Adicionais</h1>
                    </div>

                    <div class="products">
                        <?php categoryItens("Adicional", $_GET["filter"] ?? "noFilter") ?> 
                    </div>
                </div>

                <div class="category">
                    <div class='section-title'>
                        <h1>Outros</h1>
                    </div>

                    <div class="products">
                        <?php categoryItens("Outro", $_GET["filter"] ?? "noFilter") ?> 
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php displayFooter()?>

    <script src="/js/script.js"></script>
</body>
</html>