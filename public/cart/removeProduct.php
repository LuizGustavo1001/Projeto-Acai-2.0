<?php 
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";

    if(isset($_GET["name"])){
        // mapping the allowed product variants
        $getAllProducts = $mysqli->query("SELECT nameProduct FROM product_version") or die("var getAllProducts (removeProduct.php)". $mysqli->errno);
        $allowedProducts = [];
        while($allProducts = $getAllProducts->fetch_assoc()){ $allowedProducts[] = $allProducts["nameProduct"]; }

        $getAllProducts->close();

        if(in_array($_GET["name"], $allowedProducts)){
            $removeProd = $mysqli->prepare("
                DELETE FROM product_order 
                WHERE idVersion = (
                    SELECT idVersion 
                    FROM product_version
                    WHERE nameProduct = ?
                )
                LIMIT 1;
            ") or die("var removeProd (removeProduct.php): " . $mysqli->errno);

            $removeProd->bind_param("s", $_GET["name"]);

            $removeProd->execute();
            $removeProd->close();

            setCookies("prodRem", "cart.php", 1);
        }
    }else{
        header("location: cart.php");
        exit;
    }