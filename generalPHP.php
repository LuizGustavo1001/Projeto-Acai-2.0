<?php

function prodOutput($prodName){ // dar saída nos produtos cadastrados no banco de dados
        global $mysqli;

        $allowedNames = 
        [
            "acaiT", "colheres", "cremeFrutado", "acaiZero10", "acaiNinho1", 
            "acaiNinho250", "morango1", "leiteEmPo1", "granola1.5", "granola1", "pacoca150", 
            "farofaPacoca1", "amendoimTriturado1", "ovomaltine1", "gotaChocolate1", "chocoball1", 
            "jujuba500", "disquete1", "saborazzi", "polpas"
        ];
        
        if(in_array($prodName, $allowedNames)){
            if( // produtos com multiplas versões
                $prodName == "acaiT" or 
                $prodName == "cremeFrutado" or 
                $prodName == "saborazzi" or 
                $prodName == "polpas" or 
                $prodName == "colheres"
                ){ 

                $prodName = match($prodName){
                    "acaiT"         => "acaiT%",
                    "saborazzi"     => "saborazzi%",
                    "cremeFrutado"  => "creme%",
                    "polpas"        => "polpa%",
                    "colheres"      => "colher%",
                    default         => $prodName

                };

                $query = $mysqli->prepare
                    (" SELECT nameProd, price, priceDate, brand, imageURL 
                        FROM product
                        WHERE price = (
                            SELECT MIN(p.price) 
                            FROM product AS p 
                            WHERE p.nameProd LIKE ?
                        )
                        LIMIT 1;"
                    );

            }else{ // produtos com apenas 1 versão
                $query = $mysqli->prepare("SELECT nameProd, price, priceDate, brand, imageURL FROM product WHERE nameProd = ?");
            }

            $query->bind_param("s", $prodName);
            $query->execute();

            $result = $query->get_result();

            if($result->num_rows > 0){ //verificar se algum produto foi encontrado com o fitro selecionado
                while($row = $result->fetch_assoc()) {

                $name     = $row['nameProd'];
                $linkName = matchProductLinkName($row['nameProd']);
                $name     = matchNamesAlt($name);

                if($row['brand'] == "Other Brand"){
                    $row['brand'] = "Outra Marca";
                }

                echo '
                    <a href="product-item/' . $linkName . '.php">
                        <img src="' . $row['imageURL'] . '" alt="' . $name . ' Image">
                        <hr>
                        <div>
                            <p>' . $row['brand'] . '</p>
                            <h2>' . $name . '</h2>
                            <p class=\"price\">
                                <em>A partir de</em>: 
                                <span style="font-size: 1.3em;">' .
                                numfmt_format_currency(numfmt_create("pt-BR", NumberFormatter::CURRENCY), $row['price'], "BRL") .
                                '</span>
                            </p>
                            <p style="color: var(--primary-clr)">
                                <small>
                                    Preço Atualizado em:
                                    <strong> ' . $row['priceDate'] . '</strong>
                                </small>
                            </p>
                        </div>
                    </a>
                ';
                }
            }
            else {
                echo "<p><em>Nenhum produto encontrado</em></p>";
            }
        }
    }

function matchNames($name){
    match($name){
        "acaiT10"               => $name = "Caixa de Açaí - 10l",
        "acaiT5"                => $name = "Caixa de Açaí - 5l",
        "acaiT1"                => $name = "Caixa de Açaí - 1l",
        "colher200"             => $name = "Colher Longa P/Açaí - 200 Unidades",
        "colher500"             => $name = "Colher Reforçada P/Açaí - 500 Unidades",
        "colher800"             => $name = "Colher P/Sorvete - 800 unidades",
        "cremeCupuacu10"        => $name = "Creme de Cupuaçu - 10l",
        "cremeMorango10"        => $name = "Creme de Morango - 10l",
        "cremeNinho10"          => $name = "Creme de Ninho - 10l",
        "cremeMaracuja10"       => $name = "Creme de Maracujá - 10l",
        "acaiZero10"            => $name = "Caixa de Açaí Zero - 10l",
        "acaiNinho1"            => $name = "Açaí c/ Ninho - 1l",
        "acaiNinho250"          => $name = "Açaí c/ Ninho - 250ml",
        "morango1"              => $name = "Morango Congelado - 1kg",
        "leiteEmPo1"            => $name = "Leite em Pó - 1 kg",
        "granola1.5"            => $name = "Granola Tia Sônia<sup>&copy</sup> - 1.5kg",
        "granola1"              => $name = "Granola Genérica - 1kg",
        "pacoca150"             => $name = "Caixa de Paçoca - 150 unidades",
        "farofaPacoca1"         => $name = "Farofa de Paçoca - 1kg",
        "amendoimTriturado1"    => $name = "Amendoim Triturado - 1kg",
        "ovomaltine1"           => $name = "Ovomaltine<sup>&copy</sup> - 750g",
        "gotaChocolate1"        => $name = "Gotas de Chocolate - 1kg",
        "chocoball1"            => $name = "Chocoball - 1kg",
        "jujuba500"             => $name = "Jujuba - 500g",
        "disquete1"             => $name = "Disqueti - 1kg",
        "saborazziChocomalt"    => $name = "Creme Chocomaltine - 5kg ",
        "saborazziCocada"       => $name = "Creme Cocada Cremosa - 5kg ",
        "saborazziCookies"      => $name = "Creme Cookies Brancos - 5kg ",
        "saborazziAvelaP"       => $name = "Creme Avelã <em>Premium</em> - 5kg ",
        "saborazziAvelaT"       => $name = "Creme Avelã <em>Tradicional</em> - 5kg ",
        "saborazziLeitinho"     => $name = "Creme Leitinho - 5kg ",
        "saborazziPacoca"       => $name = "Creme Paçoca Cremosa  - 5kg",
        "saborazziSkimoL"       => $name = "Creme Skimo ao Leite - 5kg",
        "saborazziSkimoB"       => $name = "Creme Skimo  Branco - 5kg",
        "saborazziWafer"        => $name = "Creme Wafer Cremoso - 5kg",
        "polpaAbac"             => $name = "Polpa de Abacaxi - Unidade",
        "polpaAbacHort"         => $name = "Polpa de Abacaxi c/Hortelã - Unidade",
        "polpaAcai"             => $name = "Polpa de Açaí - Unidade",
        "polpaAcrl"             => $name = "Polpa de Acerola - Unidade",
        "polpaAcrlMamao"        => $name = "Polpa de Acerola c/Mamão - Unidade",
        "polpaCacau"            => $name = "Polpa de Cacau - Unidade",
        "polpaCaja"             => $name = "Polpa de Caja - Unidade",
        "polpaCaju"             => $name = "Polpa de Caju - Unidade",
        "polpaCupuacu"          => $name = "Polpa de Cupuaçú - Unidade",
        "polpaGoiaba"           => $name = "Polpa de Goiaba - Unidade",
        "polpaGraviola"         => $name = "Polpa de Graviola - Unidade",
        "polpaMamao"            => $name = "Polpa de Mamão - Unidade",
        "polpaMamaoMrcj"        => $name = "Polpa de Mamão c/ Maracujá - Unidade",
        "polpaManga"            => $name = "Polpa de Manga - Unidade",
        "polpaMangaba"          => $name = "Polpa de Mangaba - Unidade",
        "polpaMaracuja"         => $name = "Polpa de Maracujá - Unidade",
        "polpaMorango"          => $name = "Polpa de Morango - Unidade",
        "polpaPitanga"          => $name = "Polpa de Pitanga - Unidade",
        "polpaTangerina"        => $name = "Polpa de Tangerina - Unidade",
        "polpaUmbu"             => $name = "Polpa de Umbu - Unidade",
        "polpaUva"              => $name = "Polpa de Uva - Unidade",
        default                 => $name = "Produto Desconhecido",

    };

    return $name;

}

function matchNamesAlt($name){
    match($name){
        "acaiT10"               => $name = "Caixa de Açaí",
        "acaiT5"                => $name = "Caixa de Açaí",
        "acaiT1"                => $name = "Caixa de Açaí",
        "colher200"             => $name = "Colheres p/ Açaí e Sorvete",
        "colher500"             => $name = "Colheres p/ Açaí e Sorvete",
        "colher800"             => $name = "Colheres p/ Açaí e Sorvete",
        "cremeCupuacu10"        => $name = "Cremes Frutados - 10l",
        "cremeMorango10"        => $name = "Cremes Frutados - 10l",
        "cremeNinho10"          => $name = "Cremes Frutados - 10l",
        "cremeMaracuja10"       => $name = "Cremes Frutados - 10l",
        "acaiZero10"            => $name = "Açaí Zero - 10l",
        "acaiNinho1"            => $name = "Açaí c/ Ninho",
        "acaiNinho250"          => $name = "Açaí c/ Ninho",
        "morango1"              => $name = "Morango Congelado - 1 kg",
        "leiteEmPo1"            => $name = "Leite em Pó - 1 kg",
        "granola1.5"            => $name = "Granola Tia Sônia<sup>&copy</sup> - 1.5 kg",
        "granola1"              => $name = "Granola Genérica - 1 kg",
        "pacoca150"             => $name = "Caixa de Paçoca - 150 unidades",
        "farofaPacoca1"         => $name = "Farofa de Paçoca - 1 kg",
        "amendoimTriturado1"    => $name = "Amendoim Triturado - 1 kg",
        "ovomaltine1"           => $name = "Ovomaltine<sup>&copy</sup> - 750 g",
        "gotaChocolate1"        => $name = "Gotas de Chocolate - 1 kg",
        "chocoball1"            => $name = "Chocoball - 1 kg",
        "jujuba500"             => $name = "Jujuba - 500 g",
        "disquete1"             => $name = "Disqueti - 1 kg",
        "saborazziChocomalt"    => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg ",
        "saborazziCocada"       => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg ",
        "saborazziCookies"      => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg ",
        "saborazziAvelaP"       => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg ",
        "saborazziAvelaT"       => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg ",
        "saborazziLeitinho"     => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg ",
        "saborazziPacoca"       => $name = "Cremes Saborazzi<sup>&copy</sup>  - 5kg",
        "saborazziSkimoL"       => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg",
        "saborazziSkimoB"       => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg",
        "saborazziWafer"        => $name = "Cremes Saborazzi<sup>&copy</sup> - 5kg",
        "polpaAbac"             => $name = "Polpas de Frutas - Unidade",
        "polpaAbacHort"         => $name = "Polpas de Frutas - Unidade",
        "polpaAcai"             => $name = "Polpas de Frutas - Unidade",
        "polpaAcrl"             => $name = "Polpas de Frutas - Unidade",
        "polpaAcrlMamao"        => $name = "Polpas de Frutas - Unidade",
        "polpaCacau"            => $name = "Polpas de Frutas - Unidade",
        "polpaCaja"             => $name = "Polpas de Frutas - Unidade",
        "polpaCaju"             => $name = "Polpas de Frutas - Unidade",
        "polpaCupuacu"          => $name = "Polpas de Frutas - Unidade",
        "polpaGoiaba"           => $name = "Polpas de Frutas - Unidade",
        "polpaGraviola"         => $name = "Polpas de Frutas - Unidade",
        "polpaMamao"            => $name = "Polpas de Frutas - Unidade",
        "polpaMamaoMrcj"        => $name = "Polpas de Frutas - Unidade",
        "polpaManga"            => $name = "Polpas de Frutas - Unidade",
        "polpaMangaba"          => $name = "Polpas de Frutas - Unidade",
        "polpaMaracuja"         => $name = "Polpas de Frutas - Unidade",
        "polpaMorango"          => $name = "Polpas de Frutas - Unidade",
        "polpaPitanga"          => $name = "Polpas de Frutas - Unidade",
        "polpaTangerina"        => $name = "Polpas de Frutas - Unidade",
        "polpaUmbu"             => $name = "Polpas de Frutas - Unidade",
        "polpaUva"              => $name = "Polpas de Frutas - Unidade",
        default                 => $name = "Produto Desconhecido",

    };
    return $name;

}

function matchProductLinkName($name){

    match($name){
        "acaiT10"               => $name = "caixaAcai",
        "acaiT5"                => $name = "caixaAcai",
        "acaiT1"                => $name = "caixaAcai",
        "colher200"             => $name = "colheres",
        "colher500"             => $name = "colheres",
        "colher800"             => $name = "colheres",
        "cremeCupuacu10"        => $name = "cremesFrutados",
        "cremeMorango10"        => $name = "cremesFrutados",
        "cremeNinho10"          => $name = "cremesFrutados",
        "cremeMaracuja10"       => $name = "cremesFrutados",
        "acaiZero10"            => $name = "acaiZero",
        "acaiNinho1"            => $name = "AcaiNinho",
        "acaiNinho250"          => $name = "AcaiNinho",
        "morango1"              => $name = "morango",
        "leiteEmPo1"            => $name = "leiteEmPo",
        "granola1.5"            => $name = "granolaTiaSonia",
        "granola1"              => $name = "GranolaTradicional",
        "pacoca150"             => $name = "pacoca",
        "farofaPacoca1"         => $name = "farofaPacoca",
        "amendoimTriturado1"    => $name = "amendoim",
        "ovomaltine1"           => $name = "ovomaltine",
        "gotaChocolate1"        => $name = "gotasChocolate",
        "chocoball1"            => $name = "chocoball",
        "jujuba500"             => $name = "jujuba",
        "disquete1"             => $name = "disqueti",
        "saborazziChocomalt"    => $name = "cremesSaborazzi",
        "saborazziCocada"       => $name = "cremesSaborazzi",
        "saborazziCookies"      => $name = "cremesSaborazzi",
        "saborazziAvelaP"       => $name = "cremesSaborazzi",
        "saborazziAvelaT"       => $name = "cremesSaborazzi",
        "saborazziLeitinho"     => $name = "cremesSaborazzi",
        "saborazziPacoca"       => $name = "cremesSaborazzi",
        "saborazziSkimoL"       => $name = "cremesSaborazzi",
        "saborazziSkimoB"       => $name = "cremesSaborazzi",
        "saborazziWafer"        => $name = "cremesSaborazzi",
        "polpaAbac"             => $name = "polpas",
        "polpaAbacHort"         => $name = "polpas",
        "polpaAcai"             => $name = "polpas",
        "polpaAcrl"             => $name = "polpas",
        "polpaAcrlMamao"        => $name = "polpas",
        "polpaCacau"            => $name = "polpas",
        "polpaCaja"             => $name = "polpas",
        "polpaCaju"             => $name = "polpas",
        "polpaCupuacu"          => $name = "polpas",
        "polpaGoiaba"           => $name = "polpas",
        "polpaGraviola"         => $name = "polpas",
        "polpaMamao"            => $name = "polpas",
        "polpaMamaoMrcj"        => $name = "polpas",
        "polpaManga"            => $name = "polpas",
        "polpaMangaba"          => $name = "polpas",
        "polpaMaracuja"         => $name = "polpas",
        "polpaMorango"          => $name = "polpas",
        "polpaPitanga"          => $name = "polpas",
        "polpaTangerina"        => $name = "polpas",
        "polpaUmbu"             => $name = "polpas",
        "polpaUva"              => $name = "polpas",
        default                 => $name = "desconhecido",

    };
    return $name;

}


function checkSession(){
    if(isset($_SESSION['lastActivity'])){


        $maxInactivity = 3600; // 1 hora

        $elapsed = time() - $_SESSION['lastActivity'];

        if($elapsed > $maxInactivity){
            session_unset();
            session_destroy();
            header("Location: login.php?timeout=1");
            exit;

        }
    }

}

function add2Cart($prodName, $amount){

    global $mysqli;

    if(!isset($_SESSION['username'])){
        header("Location: ../../account/login.php?unkUser=1");
    }else{
        $allowedNames = 
        [
            "acaiT10", "acaiT5", "acaiT1", "colher200", "colher500", "colher800", "cremeNinho10",
            "cremeCupuacu10", "cremeMaracuja10", "cremeMorango10", "acaiZero10", "acaiNinho1", 
            "acaiNinho250", "saborazziChocomalt", "saborazziCocada", "saborazziCookies",
            "saborazziAvelaP", "saborazziAvelaT", "saborazziLeitinho", "saborazziPacoca",
            "saborazziSkimoL", "saborazziSkimoB", "saborazziWafer", "polpaAbac", "polpaAbacHort",
            "polpaAcrl", "polpaAcrlMamao", "polpaCacau", "polpaCaja", "polpaCaju", "polpaCupuacu",
            "polpaGoiaba", "polpaGraviola", "polpaManga", "polpaMangaba", "polpaMaracuja", "polpaMorango",
            "polpaUva", "morango1", "leiteEmPo1", "granola1.5", "granola1", "pacoca150", "farofaPacoca1", 
            "amendoimTriturado1","ovomaltine1", "gotaChocolate1", "chocoball1", "jujuba500", "disquete1"
        ];

        if(in_array($prodName, $allowedNames)){
            $query = $mysqli->prepare("SELECT idProd, price FROM product WHERE nameProd = ?");

            $query->bind_param("s",$prodName);
            $query->execute();

            $result = $query->get_result();
            $result = $result->fetch_assoc();

            $totalPrice = $result["price"] * $amount;
            $idProd = $result["idProd"];

            $query->close();

            $query = $mysqli->prepare(
                "INSERT INTO product_order (idProd, amount, singlePrice, totPrice) VALUES
                            (?, ?, ?, ?)"
            );

            $query->bind_param("iidd", $idProd, $amount, $result['price'], $totalPrice);

            if($query->execute()){
                echo "<p class=\"sucess-text\">Produto Adicionado com sucesso ao Carrinho</p>";

            }
        }
    }

    
}

function verifyOrders(){ // verificar se há pedidos "fantasmas" no banco de dados
    // pedidos que não possuem produtos associados a eles, ou seja, pedidos que foram criados sem serem confirmados pelo usuário
    /*
    global $mysqli;

    $stmt = $mysqli->prepare("DELETE FROM client_order WHERE orderDate < NOW() - INTERVAL 1 DAY AND orderHour < CURRENT_TIME() - INTERVAL 1 DAY AND idOrder NOT IN (SELECT idOrder FROM product_order);");
    $stmt->execute();
    $stmt->close();
    */
}

