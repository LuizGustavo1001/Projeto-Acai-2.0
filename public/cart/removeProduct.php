<?php 
    include "../../databaseConnection.php";
    include "../generalPHP.php";

    if(isset($_GET["name"])){
        $getAllProducts = $mysqli->query("SELECT nameProduct FROM product_version");
        $allowedProducts = [];
        while($allProducts = $getAllProducts->fetch_assoc()){
            $allowedProducts[] = $allProducts["nameProduct"];
        }

        $getAllProducts->close();

        if(in_array($_GET["name"], $allowedProducts)){
            $removeProd = $mysqli->prepare("
                DELETE FROM product_order 
                WHERE idProduct = (
                    SELECT idVersion 
                    FROM product_version
                    WHERE nameProduct = ?
                )
                LIMIT 1;
            ");

            $removeProd->bind_param("s", $_GET["name"]);

            if($removeProd->execute()){
                $removeProd->close();
                header("location: cart.php");
                exit();
            }else{
                $removeProd->close();
                header("../errorPage.php");
                exit();
            }
        }else{
            header("../errorPage.php");
            exit();
        }
    }else{
        header("location: cart.php");
        exit;
    }

    