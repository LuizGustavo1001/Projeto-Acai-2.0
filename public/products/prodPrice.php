<?php 

// chamar função que adiciona produtos ao carrinho
if(isset($_GET['size']) && isset($_GET['amount-product']) && isset($_GET['formType'])){
    if($_GET['formType'] === 'mobile'){
        add2Cart($_GET['size'], $_GET['amount-product']);
    } else if($_GET['formType'] === 'desktop'){
        add2Cart($_GET['size'], $_GET['amount-product']);
    }
}

function add2Cart($prodName, $amount){
    global $mysqli;

    if(!isset($_SESSION['clientName'])){
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
            "amendoimTriturado1","ovomaltine1", "gotasChocolate1", "chocoball1", "jujuba500", "confete1"
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
                "
                    INSERT INTO product_order (idOrder, idProd, amount, singlePrice, totPrice) VALUES
                        (?, ?, ?, ?, ?)
                "
            );

            $query->bind_param("iiidd", $_SESSION["idOrder"], $idProd, $amount, $result['price'], $totalPrice);

            if($query->execute()){
                $urlName = matchProductLink($prodName);
                header("Location: $urlName.php?prodAdd=1");

            }
        }
    }

}

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
        "amendoimTriturado1","ovomaltine1", "gotasChocolate1", "chocoball1", "jujuba500","confete1"
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
            exit();
        }

    }else{
        return null;

    }
}