<?php
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../generalPHP.php";
    require_once "../footerHeader.php";
    require_once "../printStyles.php";
    
    //require '../composer/vendor/autoload.php';

    if (isset($_SESSION["isAdmin"])){ setCookies("adminNotAllowed", "../mannager/admin.php", 0); }

    if(! isset($_SESSION["userMail"])){ setCookies("unkUser", "../account/login.php", 0); }

    $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

    // path to the JSON credentials file downloaded from Google Cloud
    /*
    $client = new Google_Client();
    $client->setAuthConfig(__DIR__ . '/../../projetoacai-472803-2e77e7899901.json');
    $client->addScope(Google_Service_Sheets::SPREADSHEETS);

    // spreadsheet ID (come from Google Sheets URL)
    $spreadsheetId = "1xJdM0OgynL5SKLoJ5gxH91abtQ18SY7Xp2dsMVkPvKk"; 
    */

    // return the total price on the cart
    function getCartTotal($clientOrder){
        global $mysqli, $defaultMoney;

        $getTotalPrice = $mysqli->prepare("
            SELECT SUM(totPrice) AS totalPrice
            FROM product_order 
            WHERE idOrder = ?
        ") or die("var getTotalPrice (cart.php): " . $mysqli->errno);
        $getTotalPrice->bind_param("i", $clientOrder);

        $getTotalPrice->execute();

        $result = $getTotalPrice->get_result();
        $getTotalPrice->close();
        $price = $result->fetch_assoc();

        $totalPrice = numfmt_format_currency($defaultMoney, $price['totalPrice'], "BRL");

        return $price["totalPrice"] ? $totalPrice : "R$ 00,00";
    }

    // print all the products in the cart
    function GetCartProd(){
        global $mysqli;

        $getProdsFromCart = $mysqli->prepare("
            SELECT *
            FROM product_order 
            WHERE idOrder = ?
        ") or die("var getProdsFromCart(cart.php): " . $mysqli->errno);

        $getProdsFromCart->bind_param("i", $_SESSION['idOrder']);
        
        $getProdsFromCart->execute();
        $result = $getProdsFromCart->get_result();
        $getProdsFromCart->close();

        $amount = $result->num_rows;
        
        switch($amount){
            case 0:
                echo "<small style=\"text-align:center\">Nenhum Produto no Seu Carrinho ainda</small>";
                break;
            
            default:
                while($row = $result->fetch_assoc()){
                    $rescueProd = $mysqli->prepare("
                        SELECT pd.printName, pv.nameProduct, pv.imageURL, pv.sizeProduct, pv.flavor
                        FROM product_data AS pd 
                            JOIN product_version AS pv ON pv.idProduct = pd.idProduct 
                        WHERE pv.idVersion = ?
                    ") or die("var rescueProd(cart.php): " . $mysqli->errno);
                    $rescueProd->bind_param("i" ,$row["idVersion"]);

                    $totalPrice = isset($row["totPrice"]) ? (float)$row["totPrice"] : 0.0;
                    
                    $rescueProd->execute();
                    $prodResult = $rescueProd->get_result();
                    $rescueProd->close();

                    $prodData = $prodResult->fetch_assoc();

                    $prodName = $prodData["flavor"] == null ? $prodName = $prodData["printName"] . " - " . $prodData["sizeProduct"] : $prodName = $prodData["printName"] . " - " . $prodData["flavor"];

                    $trashBin = getIcon("trash-bin");
                    $totalPrice = numfmt_format_currency(numfmt_create("pt-BR", NumberFormatter::CURRENCY), $totalPrice, "BRL");

                    echo "
                        <li>
                            <div>
                                <img src='{$prodData['imageURL']}' alt='product image'>
                                <span>
                                    <p class='line-clamp'><strong>{$prodName}</strong></p>
                                    <p class='line-clamp'>Quantidade: <strong>{$row["amount"]}</strong></p>
                                    <p class='line-clamp'>Total: <strong>{$totalPrice}</strong></p>
                                </span>
                            </div>
                            <a href='removeProduct.php?name={$prodData['nameProduct']}' aria-label='Remover item do carrinho'> {$trashBin} </a>
                        </li>
                    ";
                }
        }
    }
    
    if(isset($_GET["orderConfirmed"])){
        // adding the cart to the spreadsheet
        $total =  getCartTotal($_SESSION["idOrder"]);
        if($total == 'R$ 00,00'){ setCookies("noItem", "cart.php", 0); }

        $Address = $_SESSION["street"]  . ", " . $_SESSION["localNum"] . ", " . $_SESSION["district"] . " - " . $_SESSION["city"];
        $currentDate = date("Y-m-d");
        $currentHour = date("H:i:s");

        // get the products that are on the selected order
        $rescueProd = $mysqli->prepare("
            SELECT pd.printName as name, o.amount, o.totPrice, pv.sizeProduct, pv.flavor
            FROM product_version AS pv 
                JOIN product_order AS o ON pv.idVersion = o.idVersion
                JOIN product_data AS pd ON pv.idProduct = pd.idProduct
            WHERE idOrder = ?;
        ") or die("var rescueProd(cart.php): ". $mysqli->errno);
        $rescueProd->bind_param("i", $_SESSION["idOrder"]);

        $rescueProd->execute();
        $rescueProd = $rescueProd->get_result();
        
        $allProd = "";
        while($row = $rescueProd->fetch_assoc()){

            $name = $row["flavor"] == null ? $row["name"] . " - " . $row["sizeProduct"] : $row["name"] . " - " . $row["flavor"];

            $allProd .= "($name / {$row['amount']} / R$ {$row['totPrice']} ) \n";
        }
        $rescueProd->close();
        
        // Adding the datas to the spreadsheet
        $service = new Google_Service_Sheets($client);
        $range = "Página1!A8"; // location where you can start to write on the spreadsheet
        $values = [
            [
            $_SESSION["userName"], $_SESSION["userMail"],$Address, $_SESSION["referencePoint"], $_SESSION["userPhone"], 
            $allProd, "{$currentDate} {$currentHour}", "R$ 00,00", $total
            ]
        ];
        $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'RAW'];
        $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);

        // creating a new order after confirming the last one
        $ordeStatus = "Finalizado";
        $newOrder = $mysqli->prepare("INSERT INTO order_data (idClient, orderDate, orderHour, orderStatus) VALUES (?, ?, ?, ?);") or die("var newOrder(cart.php): ". $mysqli->errno);
        $newOrder->bind_param("isss", $_SESSION["idUser"], $currentDate,  $currentHour, $ordeStatus);
        
        $newOrder->execute();
        $newIdOrder = $mysqli->insert_id;
        $newOrder->close();

        $_SESSION["idOrder"] = $newIdOrder;

        setCookies("orderConfirmed", "/index.php", 1);
    }
    checkSession();

    $address = "{$_SESSION['street']}, {$_SESSION['localNum']} - {$_SESSION['district']}, {$_SESSION['city']} - {$_SESSION['state']} <br> <em>{$_SESSION['referencePoint']}</em>";

    function fillCostumerInfo($icon, $label, $value){
        $icon = getIcon($icon);
        $iconBg = getIcon("iconBg");

        echo "
            <li>
                <div class='icon-box'>
                    {$iconBg}
                    {$icon}
                </div>

                <div class='text'>
                    <p class='line-clamp'><strong>{$label}</strong></p>
                    <p class='line-clamp'>{$value}</p>
                </div>
            </li>
        ";
    }

    function fillOrderOverview($label, $value){
        echo "
            <li>
                <div class='label line-clamp'>{$label}</div>
                <p class='text line-clamp'><strong>{$value}</strong></p>
            </li>
        ";
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("cart") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Carrinho</title>
</head>
<body>
    <div class="dazzles-bg fade-in mobile"></div>

    <?php displayHeader("cart")?>

    <main>
        <section class="title-hero rise-above" style="--time-outer: 0.5s">
            <h1>Carrinho</h1>
            <p>
                Caso algum de seus <strong>dados pessoais</strong> estejam incorretos, clique no botão <strong>Editar</strong>. <br>
                Clique em <strong>confirmar pedido</strong> para finalizar a compra.
            </p>
        </section>

        <section class="hero rise-above">
            <div class="left container">
                <div class="item costumer">
                    <div class="title"><h1>Informações do Cliente</h1></div>

                    <ul class="content">
                        <?php 
                            fillCostumerInfo("userFilled", "Cliente", $_SESSION["userName"]);
                            fillCostumerInfo("maps_outer", "Endereço", $address);
                            fillCostumerInfo("phone", "Telefone", $_SESSION["userPhone"]);
                        ?>
                        <a href="../account/account.php" class="regular-btn">Editar dados pessoais</a>
                    </ul>
                </div>

                <div class="item overview">
                    <div class="title"><h1>Resumo do Pedido</h1></div>

                    <ul class="content">
                        <?php 
                            fillOrderOverview("Subtotal", getCartTotal($_SESSION["idOrder"]));
                            fillOrderOverview("Taxa de entrega", "R$ 00,00");
                            fillOrderOverview("Total", getCartTotal($_SESSION["idOrder"]));
                        ?>
                        <a href="cart.php?orderConfirmed=1" class="regular-btn">Confirmar Pedido</a>
                    </ul>
                </div>
            </div>

            <div class="right container">
                <div class="item review">
                    <div class="title"><h1>Revisão dos Itens</h1></div>

                    <ul class="content">
                        <?php GetCartProd() ?>
                    </ul>
                </div>
            </div>
        </section>
    </main>

                <!--
    <main>
        <section class="title">
            <h1>Carrinho</h1>
            <p>Clique em <strong>Confirmar Pedido</strong> para efetuá-lo</p>
            <p>
                Caso Algum de seus Dados Pessoas abaixo estejam incorretos clique no botão 
                <strong>"Editar"</strong>
            </p>
        </section>

        <section class="hero-section">
            <div class="hero-top">
                <div class="order-info section-bg">
                    <div class="order-info-header">
                        <h1>Informações do Cliente</h1>

                        <a href="../account/account.php">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                Editar
                            </div>
                        </a>
                    </div>
                
                    <div class="order-info-content">
                        <ol>
                            <li>
                                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475315/user_iqkn7x.png" alt="user icon">
                                
                                <ul class="list-item-text">
                                    <li><strong>Cliente:</strong></li>
                                    <li><span><?php echo $_SESSION["userName"]?></span></li>
                                </ul>
                            </li>
                            
                            <li>
                                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475314/pin_zqdhx7.png" alt="maps pin icon">
                                
                                <ul class="list-item-text">
                                    <li><strong>Endereço:</strong></li>
                                    <li> 
                                        <span>
                                            <?php echo "{$_SESSION['street']}, {$_SESSION['localNum']} - {$_SESSION['district']}, {$_SESSION['city']} - {$_SESSION['state']} <br> <em>{$_SESSION['referencePoint']}</em>"?> 
                                        </span>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1751475315/phone_plvmle.png" alt="phone icon">
                                
                                <ul class="list-item-text">
                                    <li><strong>Telefone:</strong></li>
                                    <li> <span><?php echo $_SESSION["userPhone"]?></span> </li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="order-review section-bg">
                    <h1>Revisão dos Itens</h1>
                    <ol style="overflow: auto;height: 400px">
                        <?php GetCartProd()?>
                    </ol>
                </div>
            </div>

            <div class="section-bg order-summary">
                <div>
                    <h1>Resumo do Pedido</h1>
                    <ol>
                        <li class="list-item-text">
                            <ul>
                                <li>Subtotal:</li>
                                <?php 
                                    $total = getCartTotal($_SESSION["idOrder"]);
                                    echo $total;
                                ?>
                            </ul>
                        </li>
                        <li class="list-item-text">
                            <ul>
                                <li>Taxa de Entrega:</li>
                                <li><span>R$ -----</span></li>
                            </ul>
                        </li>
                        <li class="list-item-text">
                            <ul style="color: var(--secondary-clr); font-weight: bold;">
                                <li>Total:</li>
                                <?php 
                                    $total = getCartTotal($_SESSION["idOrder"]);
                                    echo $total;
                                ?>
                            </ul>
                        </li>
                    </ol>
                </div>
                <a href="cart.php?orderConfirmed=1" class="link-button">Confirmar Pedido</a>
            </div>
        </section>
    </main>
            -->
    <?php displayFooter()?>

    <script src="/js/general.js"></script>
    <script src="/js/script.js"></script>
</body>
</html>