<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";

    checkSession("all-product");

    function categoryItens($type, $filter){
        global $mysqli;

        $allowedTypes = ["Cream", "Additional", "Other"];
        if(in_array($type, $allowedTypes)){
            $query = $query = $mysqli->prepare("SELECT nameProduct FROM product WHERE typeProduct = ?");

            match($filter){
                "nameAsc"       => $query = $mysqli->prepare("SELECT nameProduct FROM product WHERE typeProduct = ? ORDER BY nameProduct  asc"),
                "nameDesc"      => $query = $mysqli->prepare("SELECT nameProduct FROM product WHERE typeProduct = ? ORDER BY nameProduct  desc"),
                "priceAsc"      => $query = $mysqli->prepare("SELECT nameProduct FROM product WHERE typeProduct = ? ORDER BY priceProduct asc"),
                "priceDesc"     => $query = $mysqli->prepare("SELECT nameProduct FROM product WHERE typeProduct = ? ORDER BY priceProduct desc"),
                default         => $query = $mysqli->prepare("SELECT nameProduct FROM product WHERE typeProduct = ?"),
            };

            $query->bind_param("s", $type);
            
            if($query->execute()){
                $vector = [];
                $result = $query->get_result();
                while($row = $result->fetch_assoc()){
                    $nameProd = matchProductName($row["nameProduct"]);
                    $vector[] = $nameProd;
                }
                $vector = array_unique($vector); // remover duplicatas

                foreach($vector as $name){
                    getProductByName($name, "");
                }
            }
        }
    };

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../CSS/general-style.css">
    <link rel="stylesheet" href="../CSS/products-style.css">
    
    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <?php faviconOut(); ?>
    
    <title>Açaí e Polpas Amazônia - Produtos</title>
    
</head>
<body>
    
    <?php headerOut(1)?>

    <main>

        <section class="header-feature">
            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1754316874/feature_xabuwx.png" alt="feature Products Image">
        </section>

        <section class="products-header">
            <div class="section-header-title">
                <h1>Nossos Produtos</h1>
                <p>Adicione Produtos ao carrinho para realizar sua compra</p>
                <p>*Preços podem ser modificados com o tempo</p>
                <p><em><strong>AVISO:</strong> Até o presente momento, os filtros por ordem alfabética podem não aparecer corretamente,<br>uma vez que os nomes no Banco de Dados se diferem dos reais</em></p>
            </div>
            
            <div class="products-search-div">
                <form method="get" class="products-search-item">
                    <div class="products-search-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <label for="inameProd">Pesquisar pelo Nome</label>
                    </div>
                    <div class="search-input generic-button">
                        <input type="text" name="nameProd" id="inameProd" placeholder="Nome do Produto">
                        <button>Pesquisar</button>
                    </div>          
                </form>

                <form method="get" class="products-search-item">
                    <div class="products-search-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                        </svg>
                        <label for="ifilter">Filtrar Por</label>
                    </div>
                    <div class="search-input generic-button">
                        <select name="filter" id="ifilter">
                            <option value="idProd" selected>
                                Id
                            </option>
                            <option value="nameDesc">
                                Ordem Alfabética(A-Z)
                            </option>
                            <option value="nameAsc">
                                Ordem Alfabética(Z-A)
                            </option>
                            <option value="priceDesc">
                                Maior Preço
                            </option>
                            <option value="priceAsc">
                                Menor Preço
                            </option>
                        </select>
                        <button>Filtar</button>
                    </div>
                </form>

            </div>
        </section>

        <section class="products-main">
            <?php 
                if(isset($_GET["nameProd"])){
                    echo prodSearchOutput($_GET["nameProd"]);
                }
            ?>

            <div class="index-title">
                <h1>Cremes</h1>
            </div>

            <ul class="products-list">
                <?php 
                    if(isset($_GET["filter"])){
                        categoryItens("Cream", $_GET["filter"]);
                    }else{
                        categoryItens("Cream", "noFilter");
                    }
                ?>
                
                <?php ?>
            </ul>

            <div class="index-title">
                <h1>Adicionais</h1>
            </div>

            <ul class="products-list">
                <?php 
                    if(isset($_GET["filter"])){
                        categoryItens("Additional", $_GET["filter"]);
                    }else{
                        categoryItens("Additional", "noFilter");
                    }
                ?>          
            </ul>

            <div class="index-title">
                <h1>Outros</h1>
            </div>

            <ul class="products-list">
                <?php 
                    if(isset($_GET["filter"])){
                        categoryItens("Other", $_GET["filter"]);
                    }else{
                        categoryItens("Other", "noFilter");
                    }
                ?> 
            </ul>
        </section>
    </main>

    <?php footerOut();?>

</body>
</html>