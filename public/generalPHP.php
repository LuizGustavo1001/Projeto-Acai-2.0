<?php

date_default_timezone_set('America/Sao_Paulo');

if(! isset($_SESSION)){
    session_start();

}

if(isset($_SESSION["passwordToken"])){
    unset($_SESSION["passwordToken"]);

}

function getProductByName($prodName, $page){
    global $mysqli;
    $allowedNames =
    [
        "caixaAcai", "colheres", "cremesFrutados", "acaiZero10", "acaiNinho", 
        "morango1", "leiteEmPo1", "granola1.5", "granola1", "pacoca150", 
        "farofaPacoca1", "amendoimTriturado1", "ovomaltine1", "gotasChocolate1", "chocoball1", 
        "jujuba500", "confete1", "cremesSaborazzi", "polpas"
    ];

    if(in_array($prodName, $allowedNames)){
        // produtos multivalorados
        if( $prodName == "caixaAcai"        || 
            $prodName == "cremesFrutados"   || 
            $prodName == "colheres"         || 
            $prodName == "cremesSaborazzi"  ||
            $prodName == "acaiNinho"        ||
            $prodName == "polpas"
        ){
            $prodName = match($prodName){
                    "caixaAcai"         => "acaiT%",
                    "cremesSaborazzi"   => "saborazzi%",
                    "cremesFrutados"    => "creme%",
                    "polpas"            => "polpa%",
                    "colheres"          => "colher%",
                    "acaiNinho"         => "acaiNinho%",
                    default             => $prodName

                };
            $query = $mysqli->prepare("
                    SELECT idProduct, nameProduct, brandProduct, imageURL, priceProduct, priceDate
                    FROM product
                    WHERE priceProduct = (
                        SELECT MIN(p.priceProduct)
                        FROM product AS p 
                        WHERE p.nameProduct LIKE ?
                    )
                    LIMIT 1;
            ");
            
        }else{
            $query = $mysqli->prepare("
                SELECT idProduct, nameProduct, brandProduct, imageURL, priceProduct, priceDate
                FROM product
                WHERE nameProduct = ?
                LIMIT 1;
            ");
        }

        $query->bind_param("s", $prodName);
        if($query->execute()){
            $result = $query->get_result();
            if($result->num_rows <= 0){
                echo "
                    <li class=\"products-item item-translate-alt\">
                        <a>
                            <p><em>Nenhum produto encontrado com o nome selecionado</em></p>
                        </a>
                    </li>
                ";
            }else{
                $row = $result->fetch_assoc();

                $link = "productView.php?id={$prodName}";
                $imageURL       = htmlspecialchars($row['imageURL']);
                $brand          = htmlspecialchars($row['brandProduct']);
                $name           = matchDisplayNamesAlt($row['nameProduct']);
                $priceDate      = htmlspecialchars          ($row['priceDate']);
                $price          = numfmt_format_currency(numfmt_create("pt-BR", NumberFormatter::CURRENCY), $row['priceProduct'], "BRL");

                if($brand == "Other Brand"){
                    $brand = "Marca Não Cadastrada";
                }
                
                if($page == "index"){
                    $link = "products/productView.php?id={$prodName}";
                }

                echo "
                    <li class=\"products-item item-translate-alt\">
                        <a href=\"{$link}\">
                            <img src=\"{$imageURL}\" alt=\"{$name} Image\">
                            <hr>
                            <div>
                                <p>{$brand}</p>
                                <h2>{$name}</h2>
                                <p class=\"price\">
                                    <em>A partir de</em>: 
                                    <span style=\"font-size: 1.3em;\">{$price}</span>
                                </p>
                                <p style=\"color: var(--primary-clr)\">
                                    <small>
                                        Preço Atualizado em:
                                        <strong>{$priceDate}</strong>
                                    </small>
                                </p>
                            </div>
                        </a>
                    </li>
                ";
            }
            
            $query->close();
        }else{
            $query->close();
            header("location: errorPage.php");
            exit;
        }
    }else{
       echo "
        <li class=\"products-item item-translate-alt\">
                <a>
                    <p><em>Nenhum produto encontrado com o nome selecionado -> <strong>" . $prodName . " </strong></em></p>
                </a>
            </li>
        ";

    }
}

function prodSearchOutput($prodName){ // search bar result
    global $mysqli;

    $query = $mysqli->prepare("
        SELECT nameProduct, priceProduct, priceDate, brandProduct, imageURL
        FROM product 
        WHERE nameProd LIKE ?
    ");

    $likeProdName = "%{$prodName}%";
    $query->bind_param("s", $likeProdName);

    if($query->execute()){
        $result = $query->get_result();
        $amount = $result->num_rows;

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
                    <div style=\"font-weight: normal;\">
                        <h1> 
                            Produtos Encontrados com o filtro:
                            <strong style=\"color: var(--secondary-clr)\"><em>$prodName</em></strong>
                        </h1>
                    </div>

                    <ul class=\"products-list\"> 
                ";

                while ($row = $result->fetch_assoc()) {
                    getProductByName(matchProductName($row["nameProduct"]), "product");
                }
                echo "</ul>";
            break;
        }
    }
}

function matchDisplayNames($name){ // Products names that the user see
    return match($name){
        // Açaí
        "acaiT10"               => "Caixa de Açaí - 10l",
        "acaiT5"                => "Caixa de Açaí - 5l",
        "acaiT1"                => "Caixa de Açaí - 1l",
        "acaiZero10"            => "Caixa de Açaí Zero - 10l",
        "acaiNinho1"            => "Açaí c/ Ninho - 1l",
        "acaiNinho250"          => "Açaí c/ Ninho - 250ml",

        // Cremes frutados
        "cremeCupuacu10"        => "Creme de Cupuaçu - 10l",
        "cremeMorango10"        => "Creme de Morango - 10l",
        "cremeNinho10"          => "Creme de Ninho - 10l",
        "cremeMaracuja10"       => "Creme de Maracujá - 10l",

        // Outros produtos
        "colher200"             => "Colher Longa P/Açaí - 200 Unidades",
        "colher500"             => "Colher Reforçada P/Açaí - 500 Unidades",
        "colher800"             => "Colher P/Sorvete - 800 unidades",
        "morango1"              => "Morango Congelado - 1kg",
        "leiteEmPo1"            => "Leite em Pó - 1 kg",
        "granola1.5"            => "Granola Tia Sônia<sup>&copy</sup> - 1.5kg",
        "granola1"              => "Granola Genérica - 1kg",
        "pacoca150"             => "Caixa de Paçoca - 150 unidades",
        "farofaPacoca1"         => "Farofa de Paçoca - 1kg",
        "amendoimTriturado1"    => "Amendoim Triturado - 1kg",
        "ovomaltine1"           => "Ovomaltine<sup>&copy</sup> - 750g",
        "gotasChocolate1"       => "Gotas de Chocolate - 1kg",
        "chocoball1"            => "Chocoball - 1kg",
        "jujuba500"             => "Jujuba - 500g",
        "confete1"              => "Confetes Coloridos - 1kg",

        // Cremes Saborazzi
        "saborazziChocomalt"    => "Creme Chocomaltine - 5kg ",
        "saborazziCocada"       => "Creme Cocada Cremosa - 5kg ",
        "saborazziCookies"      => "Creme Cookies Brancos - 5kg ",
        "saborazziAvelaP"       => "Creme Avelã <em>Premium</em> - 5kg ",
        "saborazziAvelaT"       => "Creme Avelã <em>Tradicional</em> - 5kg ",
        "saborazziLeitinho"     => "Creme Leitinho - 5kg ",
        "saborazziPacoca"       => "Creme Paçoca Cremosa  - 5kg",
        "saborazziSkimoL"       => "Creme Skimo ao Leite - 5kg",
        "saborazziSkimoB"       => "Creme Skimo  Branco - 5kg",
        "saborazziWafer"        => "Creme Wafer Cremoso - 5kg",

        // Polpas
        "polpaAbac"             => "Polpa de Abacaxi - Unidade",
        "polpaAbacHort"         => "Polpa de Abacaxi c/Hortelã - Unidade",
        "polpaAcai"             => "Polpa de Açaí - Unidade",
        "polpaAcrl"             => "Polpa de Acerola - Unidade",
        "polpaAcrlMamao"        => "Polpa de Acerola c/Mamão - Unidade",
        "polpaCacau"            => "Polpa de Cacau - Unidade",
        "polpaCaja"             => "Polpa de Caja - Unidade",
        "polpaCaju"             => "Polpa de Caju - Unidade",
        "polpaCupuacu"          => "Polpa de Cupuaçú - Unidade",
        "polpaGoiaba"           => "Polpa de Goiaba - Unidade",
        "polpaGraviola"         => "Polpa de Graviola - Unidade",
        "polpaMamao"            => "Polpa de Mamão - Unidade",
        "polpaMamaoMrcj"        => "Polpa de Mamão c/ Maracujá - Unidade",
        "polpaManga"            => "Polpa de Manga - Unidade",
        "polpaMangaba"          => "Polpa de Mangaba - Unidade",
        "polpaMaracuja"         => "Polpa de Maracujá - Unidade",
        "polpaMorango"          => "Polpa de Morango - Unidade",
        "polpaPitanga"          => "Polpa de Pitanga - Unidade",
        "polpaTangerina"        => "Polpa de Tangerina - Unidade",
        "polpaUmbu"             => "Polpa de Umbu - Unidade",
        "polpaUva"              => "Polpa de Uva - Unidade",

        // Default
        default                 => "Produto Desconhecido",
        
    };

}

function matchDisplayNamesAlt($name){ // Products names that the user see
    return match($name){
        // Açaí
        "acaiT10", "acaiT5", "acaiT1"                       => "Caixa de Açaí",
        "acaiZero10"                                        => "Açaí Zero",
        "acaiNinho1", "acaiNinho250"                        => "Açaí c/ Ninho",

        // Utensílios
        "colher200", "colher500", "colher800"               => "Colheres p/ Açaí e Sorvete",

        // Cremes frutados
        "cremeCupuacu10", "cremeMorango10",
        "cremeNinho10", "cremeMaracuja10"                   => "Cremes Frutados",

        // Outros produtos
        "morango1"                                          => "Morango Congelado",
        "leiteEmPo1"                                        => "Leite em Pó",
        "granola1.5"                                        => "Granola Tia Sônia<sup>&copy</sup>",
        "granola1"                                          => "Granola Genérica",
        "pacoca150"                                         => "Caixa de Paçoca",
        "farofaPacoca1"                                     => "Farofa de Paçoca",
        "amendoimTriturado1"                                => "Amendoim Triturado",
        "ovomaltine1"                                       => "Ovomaltine<sup>&copy</sup>",
        "gotasChocolate1"                                   => "Gotas de Chocolate",
        "chocoball1"                                        => "Chocoball",
        "jujuba500"                                         => "Jujuba",
        "confete1"                                          => "Confetes",

        // Cremes Saborazzi
        "saborazziChocomalt", "saborazziCocada", 
        "saborazziCookies","saborazziAvelaP", 
        "saborazziAvelaT", "saborazziLeitinho",
        "saborazziPacoca", "saborazziSkimoL", 
        "saborazziSkimoB","saborazziWafer"                  => "Cremes Saborazzi<sup>&copy</sup>",

        // Polpas
        "polpaAbac", "polpaAbacHort", "polpaAcai", 
        "polpaAcrl", "polpaAcrlMamao","polpaCacau", 
        "polpaCaja", "polpaCaju", "polpaCupuacu", 
        "polpaGoiaba","polpaGraviola", "polpaMamao",
        "polpaMamaoMrcj", "polpaManga","polpaMangaba", 
        "polpaMaracuja", "polpaMorango", "polpaPitanga",
        "polpaTangerina", "polpaUmbu", "polpaUva"           => "Polpas de Frutas - Unidade",

        // Default
        default                                             => "Produto Desconhecido",
    };
}

function matchProductName($name){ // Products names for the searching process
    return match($name){
        // Açaí
        "acaiT10", "acaiT5", "acaiT1"                   => "caixaAcai",
        "acaiZero10"                                    => "acaiZero10",
        "acaiNinho1", "acaiNinho250"                    => "acaiNinho",
        
        // Colheres
        "colher200", "colher500", "colher800"           => "colheres",
        
        // Cremes Frutados
        "cremeCupuacu10", "cremeMorango10",
        "cremeNinho10", "cremeMaracuja10"               => "cremesFrutados",

        // Cremes Saborazzi
        "saborazziChocomalt", "saborazziCocada", 
        "saborazziCookies","saborazziAvelaP", 
        "saborazziAvelaT", "saborazziLeitinho",
        "saborazziPacoca", "saborazziSkimoL", 
        "saborazziSkimoB","saborazziWafer"              => "cremesSaborazzi",

        // Polpas
        "polpaAbac", "polpaAbacHort", "polpaAcai", 
        "polpaAcrl", "polpaAcrlMamao","polpaCacau", 
        "polpaCaja", "polpaCaju", "polpaCupuacu", 
        "polpaGoiaba","polpaGraviola", "polpaMamao", 
        "polpaMamaoMrcj", "polpaManga","polpaMangaba", 
        "polpaMaracuja", "polpaMorango","polpaPitanga",
        "polpaTangerina", "polpaUmbu", "polpaUva"       => "polpas",

        // Default
        default                                         => $name,
    };
}

function matchProductLink($name){ // Page Name for each product
    return match($name){
        // Açaí
        "acaiT10", "acaiT5", "acaiT1"                => "caixaAcai",
        "acaiZero10"                                 => "acaiZero",
        "acaiNinho1", "acaiNinho250"                 => "acaiNinho",
        
        // Colheres
        "colher200", "colher500", "colher800"        => "colheres",
        
        // Cremes Frutados
        "cremeCupuacu10", "cremeMorango10",
        "cremeNinho10", "cremeMaracuja10"            => "cremesFrutados",

        // Outros Produtos
        "morango1"                                   => "morango",
        "leiteEmPo1"                                 => "leiteEmPo",
        "granola1.5"                                 => "granolaTiaSonia",
        "granola1"                                   => "granolaTradicional",
        "pacoca150"                                  => "pacoca",
        "farofaPacoca1"                              => "farofaPacoca",
        "amendoimTriturado1"                         => "amendoim",
        "ovomaltine1"                                => "ovomaltine",
        "gotasChocolate1"                            => "gotasChocolate",
        "chocoball1"                                 => "chocoball",
        "jujuba500"                                  => "jujuba",
        "confete1"                                   => "confetes",

        // Cremes Saborazzi
        "saborazziChocomalt", "saborazziCocada", "saborazziCookies",
        "saborazziAvelaP", "saborazziAvelaT", "saborazziLeitinho",
        "saborazziPacoca", "saborazziSkimoL", "saborazziSkimoB",
        "saborazziWafer"                             => "cremesSaborazzi",

        // Polpas
        "polpaAbac", "polpaAbacHort", "polpaAcai", "polpaAcrl", "polpaAcrlMamao",
        "polpaCacau", "polpaCaja", "polpaCaju", "polpaCupuacu", "polpaGoiaba",
        "polpaGraviola", "polpaMamao", "polpaMamaoMrcj", "polpaManga",
        "polpaMangaba", "polpaMaracuja", "polpaMorango", "polpaPitanga",
        "polpaTangerina", "polpaUmbu", "polpaUva"    => "polpas",

        // Default
        default                                      => "desconhecido",
    };

}


function checkSession($local){
    if(isset($_SESSION['lastActivity'])){

        $maxInactivity = 3600; // 1 hora

        $elapsed = time() - $_SESSION['lastActivity'];

        if($elapsed > $maxInactivity){
            session_unset();
            session_destroy();
            match($local){
                "all-product"   => $local = "../account/login.php",
                "cart"          => $local = "../account/login.php",
                "insideAccount" => $local = "../login.php",
                default         => $local = "login.php"
            };

            header("Location: $local?timeout=1");
            exit;
        }
    }
}


function verifyOrders(){ // remover pedidos que não foram confirmados a mais de um dia
    global $mysqli;

    // Seleciona os pedidos com mais de 2 dias e sem produtos associados
    $stmt = $mysqli->prepare("
        SELECT od.idOrder 
        FROM order_data AS od
        LEFT JOIN product_order AS po ON od.idOrder = po.idOrder
        WHERE od.orderDate < (NOW() - INTERVAL 2 DAY) AND po.idOrder IS NULL
    ");

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $idOrder = $row["idOrder"];

        // Deleta o pedido (não há produtos associados, então não precisa deletar da product_order)
        $deleteOrder = $mysqli->prepare("DELETE FROM order_data WHERE idOrder = ?");

        $deleteOrder->bind_param("i", $idOrder);
        $deleteOrder->execute();
        $deleteOrder->close();
        
    }

    $stmt->close();
}

function optionSelect($local, $option){
    if($_SESSION[$local] == "$option"){
        echo "selected";
    }
}


function verifyCartAmount(){ // dar saída na quantidade de produtos no carrinho do cliente logado
    global $mysqli;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION["idOrder"])){
        $stmt = $mysqli->prepare("SELECT COUNT(*) as itemCount FROM product_order WHERE idOrder = ?");
        $stmt->bind_param("i", $_SESSION["idOrder"]);
        
        if($stmt->execute()){
            $result = $stmt->get_result();
            if ($result) {
                $row = $result->fetch_assoc();
                $cartAmount = $row["itemCount"];
                echo "<p class=\"numberItens\">$cartAmount</p>";
            } else {
                echo "<p class=\"numberItens\">0</p>";
            }
        }else{
            echo "erro ao executar o stmt";
        }
    }
}