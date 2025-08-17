<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";

    $allowedProducts = [
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

    if(in_array($_GET["name"], $allowedProducts)){
        $query = $mysqli->prepare("
            DELETE FROM product_order 
            WHERE idProd = (
                SELECT idProd 
                FROM product
                WHERE nameProd = ?
            )
            LIMIT 1;
        ");

        $query->bind_param("s", $_GET["name"]);

        if($query->execute()){
            $query->close();
            header("location: cart.php");
            exit;
        }else{
            $query->close();
            header("../errorPage.php");
            exit;
        }
    }else{
        header("../errorPage.php");
        exit;

    }