<?php 

$defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

add2Cart($_GET['size'], $_GET['amount-product']);
function add2Cart($prodName, $amount){
    global $mysqli;

    if(! isset($_SESSION['userName'])){
        header("Location: ../account/login.php?unkUser=1");
        exit();
        
    }
    
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
        $query = $mysqli->prepare("
            SELECT idProduct, priceProduct, availability 
            FROM product 
            WHERE nameProduct = ?;
        ");

        $query->bind_param("s",$prodName);
        $query->execute();

        $result = $query->get_result();
        $result = $result->fetch_assoc();
        $urlName = matchProductLink($prodName);
        switch($result["availability"]){
            case "0":
                header("Location: $urlName.php?outOfOrder=1");
                exit();
                
            default:
                $totalPrice = $result["priceProduct"] * $amount;
                $query->close();

                $query = $mysqli->prepare(
                    "
                        INSERT INTO product_order (idOrder, idProduct, amount, singlePrice, totPrice) VALUES
                            (?, ?, ?, ?, ?);
                    ");
                $query->bind_param("iiidd", $_SESSION["idOrder"], $result["idProduct"], $amount, $result["priceProduct"], $totalPrice);

                if($query->execute()){
                    header("Location: products.php?prodAdd=1&id={$prodName}");
                    exit();
                }
                break;
        }
    }
}

function returnPrice($nameProd){
    global $mysqli, $defaultMoney;

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
        $query = $mysqli->prepare("SELECT priceProduct FROM product WHERE nameProduct = ?");
        $query->bind_param("s",$nameProd);

        if($query->execute()){
            $result = $query->get_result()->fetch_assoc();
            return numfmt_format_currency($defaultMoney, $result['priceProduct'], "BRL");
        }else{
            header("location: ../errorPage.php");
            exit();
        }
    }else{
        return null;
    }
}