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
                        <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.75 7.5C17.2017 7.5 20 10.2982 20 13.75M20.8235 20.8186L26.25 26.25M23.75 13.75C23.75 19.2729 19.2729 23.75 13.75 23.75C8.22715 23.75 3.75 19.2729 3.75 13.75C3.75 8.22715 8.22715 3.75 13.75 3.75C19.2729 3.75 23.75 8.22715 23.75 13.75Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </label>

                    <input type="text" name="nameProd" id="inameProd" placeholder="<?= htmlspecialchars($_GET['nameProd'] ?? 'Nome do Produto') ?>">
                </form>
                <details class="sort-btn outer-btn">
                    <summary>
                        <svg viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.4154 18.125H9.58284C8.8508 18.125 8.48479 18.125 8.31531 18.2698C8.16825 18.3954 8.09021 18.5838 8.10538 18.7767C8.12287 18.9989 8.38168 19.2577 8.89929 19.7752L13.8155 24.6916C14.0549 24.9308 14.1745 25.0504 14.3124 25.0953C14.4337 25.1347 14.5644 25.1347 14.6857 25.0953C14.8237 25.0504 14.9434 24.9308 15.1826 24.6916L20.0988 19.7752C20.6165 19.2577 20.8753 18.9989 20.8928 18.7767C20.9079 18.5838 20.8299 18.3954 20.6828 18.2698C20.5134 18.125 20.1474 18.125 19.4154 18.125Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9.58284 10.875H19.4154C20.1474 10.875 20.5134 10.875 20.6828 10.7302C20.83 10.6046 20.9079 10.4162 20.8928 10.2234C20.8753 10.0012 20.6165 9.74241 20.0988 9.22478L15.1826 4.30852C14.9434 4.06926 14.8237 3.94963 14.6857 3.90482C14.5644 3.86539 14.4337 3.86539 14.3124 3.90482C14.1745 3.94963 14.0549 4.06926 13.8155 4.30852L8.89929 9.22478C8.38168 9.74239 8.12287 10.0012 8.10538 10.2234C8.09021 10.4162 8.16825 10.6046 8.31531 10.7302C8.48479 10.875 8.8508 10.875 9.58284 10.875Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
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