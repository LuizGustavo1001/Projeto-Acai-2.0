<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";

    $getAllProducts = $mysqli->query("SELECT nameProduct FROM product_version");
    $allowedProducts = [];
    while($allProducts = $getAllProducts->fetch_assoc()){
        $allowedProducts[] = $allProducts["nameProduct"];
    }

    if(in_array($_GET["name"], $allowedProducts)){
        $query = $mysqli->prepare("
            DELETE FROM product_order 
            WHERE idProduct = (
                SELECT idVersion 
                FROM product_version
                WHERE nameProduct = ?
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