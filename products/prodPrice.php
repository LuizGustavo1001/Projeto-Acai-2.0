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
        $query->bind_param("s",$nameProd);

        if($query->execute()){
            $result = $query->get_result()->fetch_assoc();
            
            if ($result && isset($result['price'])) {
                return numfmt_format_currency($defaultMoney, $result['price'], "BRL"); 
            } else {
                return null;
            }
        }else{
            header("location: ../errorPage.php");

        }

    }else{
        return null;

    }
}