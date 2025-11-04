<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    include "../printStyles.php";

    checkSession("all-product");
    function prodSearchOutput($prodName){ 
        // search bar result at products.php
        global $mysqli;
        if($prodName !== ""){
            $getSearchReturn = $mysqli->prepare("
                SELECT pd.idProduct, pd.printName, pd.altName, pd.brandProduct, pv.imageURL, 
                    MIN(pv.priceProduct) AS priceProduct, pv.priceDate
                    FROM product_version AS pv
                        INNER JOIN product_data AS pd ON pv.idProduct = pd.idProduct
                    GROUP BY pd.idProduct
                WHERE pd.printName LIKE ?
            ");

            $likeProdName = "%{$prodName}%";
            $getSearchReturn->bind_param("s", $likeProdName);

            if($getSearchReturn->execute()){
                $searchResult = $getSearchReturn->get_result();
                $amount = $searchResult->num_rows;
                $getSearchReturn->close();

                echo "<li class='products-category'>";

                switch($amount){
                    case 0:
                        echo "
                            <div style=\"font-weight: normal;\">
                                <h1> 
                                    Nenhum Produto Encontrado com o Nome:
                                    <strong style=\"color: var(--secondary-clr)\"><em>$prodName</em></strong>
                                </h1>
                            </div>
                        ";
                        break;
                    
                    default:
                        echo "
                            <div style='font-weight: normal; margin-bottom: 2em;'>
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

    }

    function returnSelected($filter){
        if( isset($_GET["filter"])){
            if($_GET['filter'] == $filter){
                echo " selected";
            }
            else{
                echo " ";
            }
        }
    }
    
    function categoryItens($type, $filter){
        // print the products based on the selected filter on HTML
        global $mysqli;

        $getAllTypes = $mysqli->query("SELECT typeProduct FROM product_data");
        $allowedTypes = [];
        while($allTypes = $getAllTypes->fetch_assoc()){
            $allowedTypes[] = $allTypes["typeProduct"];
        }
        $getAllTypes->close();

        if(in_array($type, $allowedTypes)){
            $query = match($filter){
                "nameAsc"       => "SELECT altName FROM product_data WHERE typeProduct = ? ORDER BY altName ASC",
                "nameDesc"      => "SELECT altName FROM product_data WHERE typeProduct = ? ORDER BY altName DESC",
                "priceAsc"      => "SELECT DISTINCT pd.altName FROM product_data AS pd JOIN product_version AS pv ON pd.idProduct = pv.idProduct WHERE typeProduct = ? ORDER BY priceProduct ASC",
                "priceDesc"     => "SELECT DISTINCT pd.altName FROM product_data AS pd JOIN product_version AS pv ON pd.idProduct = pv.idProduct WHERE typeProduct = ? ORDER BY priceProduct DESC",
                default         => "SELECT altName FROM product_data WHERE typeProduct = ?",
            };

            $getProductFilter = $mysqli->prepare($query);
            $getProductFilter->bind_param("s", $type);

            if($getProductFilter->execute()){
                $vector = [];
                $result = $getProductFilter->get_result();
                while($row = $result->fetch_assoc()){
                    $vector[] = $row["altName"];
                }
                $vector = array_unique($vector); // removing duplicates

                foreach($vector as $name){
                    getProductByName($name, "");
                }
            }
            $getProductFilter->close();
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

    <link rel="stylesheet" href="<?php printStyle("1", "universal") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "products") ?>">
    
    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <?php displayFavicon()?>
    
    <title>Açaí e Polpas Amazônia - Produtos</title>
</head>
<body>
    
    <?php displayHeader(1)?>

    <main>
        <!-- Pop Up Box -->
        <?php 
            if(isset($_GET["prodAdd"])){
                $name = "{$_GET['id']} - {$_GET['size']}";
                displayPopUp("prodAdd", $name);
                verifyOrders();
            }
        ?>
        <!-- Pop Up Box -->

        <section class="header-feature">
            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1754316874/feature_xabuwx.png" alt="feature Products Image">
        </section>

        <section class="products-title">
            <div class="title">
                <h1>Nossos Produtos</h1>
                <p>Adicione Produtos ao carrinho para realizar sua compra</p>
                <p>*Preços podem ser modificados com o tempo</p>
            </div>

            <div class="search-form">
                <form method="GET">
                    <div class="search-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <label for="inameProd">Pesquisar pelo Nome</label>
                    </div>
                    <div class="search-input regular-input">
                        <input type="text" name="nameProd" id="inameProd" placeholder="<?= htmlspecialchars($_GET['nameProd'] ?? 'Nome do Produto') ?>">
                        <button class="regular-button">Pesquisar</button>
                    </div>
                </form>

                <form method="GET">
                    <div class="search-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                        </svg>
                        <label for="ifilter">Filtrar Por</label>
                    </div>
                    <div class="search-input regular-input">
                        <select name="filter" id="ifilter">
                            <option value="idProd"     <?php returnSelected("idProd")?>>Id</option>
                            <option value="nameAsc"    <?php returnSelected("nameAsc")?>>Ordem Alfabética(A-Z)</option>
                            <option value="nameDesc"   <?php returnSelected("nameDesc")?>>Ordem Alfabética(Z-A)</option>
                            <option value="priceDesc"  <?php returnSelected("priceDesc")?>>Maior Preço</option>
                            <option value="priceAsc"   <?php returnSelected("priceAsc")?>>Menor Preço</option>
                        </select>
                        <button class="regular-button">Filtrar</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="products-hero">
            <ul class="products-list">
                <?php 
                if(isset($_GET["nameProd"])){
                    echo prodSearchOutput($_GET["nameProd"]);
                    echo "</li>";
                }
                ?>
                <li class="products-category">
                    <div class='section-title'>
                        <h1>Cremes</h1>
                    </div>

                    <ul class="products">
                        <?php 
                            if(isset($_GET["filter"])){
                                categoryItens("Creme", $_GET["filter"]);
                            }else{
                                categoryItens("Creme", "noFilter");
                            }
                        ?>
                    </ul>
                </li>
                <li class="products-category">
                    <div class='section-title'>
                        <h1>Adicionais</h1>
                    </div>

                    <ul class="products">
                        <?php
                            if(isset($_GET["filter"])){
                                categoryItens("Adicional", $_GET["filter"]);
                            }else{
                                categoryItens("Adicional", "noFilter");
                            }
                        ?> 
                    </ul>
                </li>
                <li class="products-category">
                    <div class='section-title'>
                        <h1>Outros</h1>
                    </div>

                    <ul class="products">
                        <?php
                            if(isset($_GET["filter"])){
                                categoryItens("Outro", $_GET["filter"]);
                            }else{
                                categoryItens("Outro", "noFilter");
                            }
                        ?> 
                    </ul>
                </li>
            </ul>
        </section>
    </main>
    <?php displayFooter();?>
</body>
</html>