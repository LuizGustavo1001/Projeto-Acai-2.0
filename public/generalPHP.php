<?php
    date_default_timezone_set('America/Sao_Paulo');

    if(! isset($_SESSION)){
        session_start();
    }

    if(isset($_SESSION["passwordToken"])){
        unset($_SESSION["passwordToken"]);
    }

    function getProductByName($prodName, $page){
        // print the product with the $prodName above
        global $mysqli;

        $getAllNames = $mysqli->query("SELECT altName FROM product_data");
        $allowedNames = [];
        while($allNames = $getAllNames->fetch_assoc()){
            $allowedNames[] = $allNames["altName"];
        }
        $getAllNames->close();

        if(in_array($prodName, $allowedNames)){
            $getProducts = $mysqli->prepare("
                SELECT pd.idProduct, pd.printName, pd.altName, pd.brandProduct, pv.imageURL, MIN(pv.priceProduct) AS priceProduct, pv.priceDate
                FROM product_data AS pd 
                    JOIN product_version AS pv ON pd.idProduct = pv.idProduct
                WHERE pd.altName = ?
            ");

            $getProducts->bind_param("s", $prodName);
            if($getProducts->execute()){
                $products = $getProducts->get_result();
                $getProducts->close();
                if($products->num_rows <= 0){
                    echo "
                        <li class=\"products-item item-translate-alt\">
                            <a>
                                <p><em>Nenhum produto encontrado com o nome selecionado</em></p>
                            </a>
                        </li>
                    ";
                }else{
                    $row = $products->fetch_assoc();
                    $name           = $row["printName"];
                    $link           = "productView.php?id={$row['altName']}";
                    $imageURL       = htmlspecialchars          ($row['imageURL']);
                    $brand          = htmlspecialchars          ($row['brandProduct']);
                    $priceDate      = htmlspecialchars          ($row['priceDate']);
                    $price          = numfmt_format_currency    (numfmt_create("pt-BR", NumberFormatter::CURRENCY), $row['priceProduct'], "BRL");

                    if($page == "index"){
                        $link = "products/productView.php?id={$row['altName']}";
                    }
                    echo "
                        <li class=\"products-item item-translate-alt\" id='{$prodName}'>
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
            }else{
                $getProducts->close();
                header("location: errorPage.php");
                exit();
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

    function prodSearchOutput($prodName){ 
        // search bar result at products.php
        global $mysqli;

        $getSearchReturn = $mysqli->prepare("
            SELECT pd.altName, pv.priceProduct, pv.priceDate, pd.brandProduct, pv.imageURL
            FROM product_data AS pd 
                JOIN product_version AS pv ON pd.idProduct = pv.idProduct
            WHERE pd.printName LIKE ?
        ");

        $likeProdName = "%{$prodName}%";
        $getSearchReturn->bind_param("s", $likeProdName);

        if($getSearchReturn->execute()){
            $searchResult = $getSearchReturn->get_result();
            $amount = $searchResult->num_rows;
            $getSearchReturn->close();

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

                    while ($row = $searchResult->fetch_assoc()) {
                        getProductByName($row["altName"], "product");
                    }
                    echo "</ul>";
                break;
            }
        }
    }

    function add2Cart($prodName, $amount){
        // add a product to the cart in database based on the product selected on productView.php
        global $mysqli;

        if(! isset($_SESSION['userName'])){
            header("Location: ../account/login.php?unkUser=1");
            exit();
        }

        $getAllNames = $mysqli->query("SELECT nameProduct FROM product_version");
        $allowedNames = [];
        
        while($allNames = $getAllNames->fetch_assoc()){
            $allowedNames[] = $allNames["nameProduct"];
        }
        $getAllNames->close();

        if(in_array($prodName, $allowedNames)){
            $getProductData = $mysqli->prepare("
                SELECT pv.idVersion, pv.priceProduct, pv.availability, pd.altName, pv.sizeProduct, pd.printName
                FROM product_version AS pv JOIN product_data AS pd ON pv.idProduct = pd.idProduct
                WHERE nameProduct = ?
                LIMIT 1
            ");

            $getProductData->bind_param("s",$prodName);
            $getProductData->execute();

            $productData = $getProductData->get_result();
            $productData = $productData->fetch_assoc();
            $getProductData->close();
            $urlName = $productData["altName"];
            switch($productData["availability"]){
                case "0":
                    header("Location: $urlName.php?outOfOrder=1");
                    exit();
                default:
                    $totalPrice = $productData["priceProduct"] * $amount;

                    $inserOrder = $mysqli->prepare("INSERT INTO product_order (idOrder, idProduct, amount, singlePrice, totPrice) VALUES (?, ?, ?, ?, ?)");
                    $inserOrder->bind_param(
                        "iiidd",
                        $_SESSION["idOrder"], 
                        $productData["idVersion"], 
                        $amount, 
                        $productData["priceProduct"], 
                        $totalPrice
                    );

                    if($inserOrder->execute()){
                        $inserOrder->close();
                        header("Location: products.php?prodAdd=1&id={$productData['printName']}&size={$productData['sizeProduct']}");
                        exit();
                    }
                    break;
            }
        }
    }

    function checkSession($local){
        if(isset($_SESSION['lastActivity'])){
            $maxInactivity = 3600; // 1 hour
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

    function verifyOrders(){
        // remove orders that wasn't confirmed and doesn't have any product for more then 2 days
        global $mysqli;

        $getOrders = $mysqli->prepare("
            SELECT od.idOrder 
            FROM order_data AS od
            LEFT JOIN product_order AS po ON od.idOrder = po.idOrder
            WHERE od.orderDate < (NOW() - INTERVAL 2 DAY) AND po.idOrder IS NULL
        ");

        $getOrders->execute();
        $result = $getOrders->get_result();
        

        while ($row = $result->fetch_assoc()) {
            $idOrder = $row["idOrder"];

            $deleteOrder = $mysqli->prepare("DELETE FROM order_data WHERE idOrder = ?");

            $deleteOrder->bind_param("i", $idOrder);
            $deleteOrder->execute();
            $deleteOrder->close();
        }

        $getOrders->close();
    }

    function optionSelect($local, $option){ 
        if(isset($_SESSION[$local]) && $_SESSION[$local] == $option){
            return " selected";
        }
        return "";
    }

    function optionSelectAlt($row, $local, $option){
        if(isset($row[$local]) && $row[$local] == $option){
            return " selected";
        }
        return "";
    }