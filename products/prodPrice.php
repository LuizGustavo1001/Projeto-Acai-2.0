<?php 

function returnPrice($nameProd){
    global $mysqli;

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
        "amendoimTriturado1","ovomaltine1", "gotaChocolate1", "chocoball1", "jujuba500","disquete1"
    ];

    if(in_array($nameProd, $allowedNames)){ // verificar se o nome para pesquisa é um dos produtos cadastrados
        $query = $mysqli->prepare("SELECT price FROM product WHERE nameProd = ?");

        $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

        if (!$query) {
            echo "<em><small>Erro na consulta ao banco de dados</small></em>";
            return;
        }
        $query->bind_param("s",$nameProd);

        $query->execute();
        $result = $query->get_result()->fetch_assoc();
            
        if ($result && isset($result['price'])) {
            return numfmt_format_currency($defaultMoney, $result['price'], "BRL") ; 
        } else {
            return null;
        }

    }else{
        return null;

    }
}

function prodPrice($nameProd){
    global $mysqli;

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

    // Optou-se por adicionar os produtos na página web manualmente para evitar muitos produtos repetidos

    if(in_array($nameProd, $allowedNames)){ // verificar se o nome para pesquisa é um dos produtos cadastrados
        $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

        $query = $mysqli->prepare("SELECT price, priceDate, brand FROM product WHERE nameProd = ?");
        if (!$query) {
            echo "<em><small>Erro na consulta ao banco de dados</small></em>";
            return;
        }
        $query->bind_param("s", $nameProd);

        $query->execute();
        $result = $query->get_result()->fetch_assoc();
            
        if ($result && isset($result['price'])) {
            $price = $result['price'];
            $date = $result['priceDate'];

            echo "<p  style=\"margin-bottom: 1em;\">
                    <span style= \"font-size: 2em; color: var(--secondary-clr);\">" .
                    numfmt_format_currency($defaultMoney, $price, "BRL") .
                    "</span></p>
                ";
            echo "<p style=\"color: var(--primary-clr)\"><small>Preço Atualizado em:<strong> $date</strong></small></p>";
            
        } else {
            echo "<em><small>Preço não disponível</small></em>";
        }

    }else{
        echo "<em><small>Produto não encontrado</small></em>";
    }
}
