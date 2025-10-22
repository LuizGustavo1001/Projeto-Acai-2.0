<?php
    include "../../databaseConnection.php";
    include "../generalPHP.php";
    include "../footerHeader.php";
    include "../printStyles.php";
    
    require __DIR__ . '/../../composer/vendor/autoload.php';

    if(! isset($_SESSION["userMail"])){
        header("location: ../account/login.php?unkUser=1");
        exit();
    }

    if (isset($_SESSION["isAdmin"])) {
        header("location: ../mannager/admin.php?adminNotAllowed=1");
        exit();
    }

    $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

    // path to the JSON credentials file downloaded from Google Cloud
    $client = new Google_Client();
    $client->setAuthConfig('../../../projetoacai-472803-2e77e7899901.json');
    $client->addScope(Google_Service_Sheets::SPREADSHEETS);

    // spreadsheet ID (come from Google Sheets URL)
    $spreadsheetId = "1xJdM0OgynL5SKLoJ5gxH91abtQ18SY7Xp2dsMVkPvKk"; 

    function getCartTotal($clientOrder){
        // return the total price on the cart
        global $mysqli, $defaultMoney;

        $getTotalPrice = $mysqli->prepare("
            SELECT SUM(totPrice) AS totalPrice
            FROM product_order 
            WHERE idOrder = ?
        ");
        $getTotalPrice->bind_param("i", $clientOrder);
        if($getTotalPrice->execute()){
            $result = $getTotalPrice->get_result();
            $price = $result->fetch_assoc();
            $getTotalPrice->close();
            if($price['totalPrice'] != null){
                return numfmt_format_currency($defaultMoney, $price['totalPrice'], "BRL");
            }else{
                return "R$ 00,00";
            }
        }
    }

    function GetCartProd(){
        // print all the products in the cart
        global $mysqli;

        $getProdsFromCart = $mysqli->prepare("
            SELECT *
            FROM product_order 
            WHERE idOrder = ?
        ");

        $getProdsFromCart->bind_param("i", $_SESSION['idOrder']);
        
        if($getProdsFromCart->execute()){
            $result = $getProdsFromCart->get_result();
            $amount = $result->num_rows;
            $getProdsFromCart->close();
            
            switch($amount){
                case 0:
                    echo "<small style=\"text-align:center\">Nenhum Produto no Seu Carrinho ainda</small>";
                    break;
                
                default:
                    while($row = $result->fetch_assoc()) {
                        $rescueProd = $mysqli->prepare("
                            SELECT pd.printName, pv.nameProduct, pv.imageURL, pv.sizeProduct, pv.flavor
                            FROM product_data AS pd 
                                JOIN product_version AS pv ON pv.idProduct = pd.idProduct 
                            WHERE pv.idVersion = ?
                        ");
                        $rescueProd->bind_param("i" ,$row["idProduct"]);
                        $totalPrice = $row["totPrice"];
                        
                        if($rescueProd->execute()){
                            $prodResult = $rescueProd->get_result();
                            $prodData = $prodResult->fetch_assoc();
                            $rescueProd->close();
                            if($prodData["flavor"] == null){
                                $prodName = $prodData["printName"] . " - " . $prodData["sizeProduct"];
                            }else{
                                $prodName = $prodData["printName"] . " - " . $prodData["flavor"];
                            }

                            echo "
                                <li>
                                    <div style=\"position: relative; display: inline-block;\">
                                        <div class=\"item-amount\"><abbr title=\"Quantidade de Itens Adicionados\">" . $row["amount"] ."</abbr></div>
                                        <img src=" . $prodData["imageURL"] .  ">
                                    </div>
                                    <ul>
                                        <li><strong>". $prodName . "</strong></li>
                                        <li> Quantidade: ". $row["amount"] . "</li>
                                        <li class=\"price\"> Total: "
                                        .numfmt_format_currency(numfmt_create("pt-BR", NumberFormatter::CURRENCY), $totalPrice  , "BRL") ." 
                                        </li>
                                        
                                    </ul>
                                    <a href=\"removeProduct.php?name=" . $prodData["nameProduct"] . "\">
                                        <abbr title=\"Remover Item do Carrinho\">
                                            <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"size-6\">
                                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0\" />
                                            </svg>
                                        </abbr>
                                    </a>
                                </li>
                            ";
                        }else{
                            header("location: ../errorPage.php");
                            exit();
                        }
                    }
            }
        }else{
            header("location: ../errorPage.php");
            exit();
        }
    }
    if(! isset($_SESSION["userMail"])){
        header("location: ../account/account.php");
        exit();
    }
    if(isset($_GET["orderConfirmed"])){
        // adding the cart to the spreadsheet
        $total =  getCartTotal($_SESSION["idOrder"]);
        if($total == 'R$ 00,00'){
            header("location: cart.php?noItem=1");
            exit();
        }

        $Address = $_SESSION["street"]  . ", " . $_SESSION["localNum"] . ", " .
                $_SESSION["district"] . " - " . $_SESSION["city"];
        $currentDate = date("Y-m-d");
        $currentHour = date("H:i:s");

        // get the products that are on the selected order
        $rescueProd = $mysqli->prepare("
            SELECT pd.printName as name, o.amount, o.totPrice, pv.sizeProduct, pv.flavor
            FROM product_version AS pv 
                JOIN product_order AS o ON pv.idVersion = o.idProduct
                JOIN product_data AS pd ON pv.idProduct = pd.idProduct
            WHERE idOrder = ?;
        ");
        $rescueProd->bind_param("i", $_SESSION["idOrder"]);

        $rescueProd->execute();
        $rescueProd = $rescueProd->get_result();
        
        $allProd = "";
        while($row = $rescueProd->fetch_assoc()){
            if($row["flavor"] == null){
                $name = $row["name"] . " - " . $row["sizeProduct"];
            }else{
                $name = $row["name"] . " - " . $row["flavor"];
            }
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
        $newOrder = $mysqli->prepare("INSERT INTO order_data (idClient, orderDate, orderHour, orderStatus) VALUES (?, ?, ?, ?);");
        $newOrder->bind_param("isss", $_SESSION["idUser"], $currentDate,  $currentHour, $ordeStatus);
        $newOrder->execute();
        $newIdOrder = $mysqli->insert_id;
        $newOrder->close();

        $_SESSION["idOrder"] = $newIdOrder;

        header("location: ../index.php?orderConfirmed=1");
        exit();
    }
    checkSession("cart");
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <?php displayFavicon()?>

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <link rel="stylesheet" href="<?php printStyle("1", "general") ?>">
    <link rel="stylesheet" href="<?php printStyle("1", "cart") ?>">

    <title>Açaí e Polpas Amazônia - Carrinho</title>
</head>
<body>

    <?php headerOut(1)?>

    <main>
        <?php 
            if(isset($_GET["noItem"]))
                displayPopUp("noItem", "");
        ?>
        
        <section class="cart-header section-header-title">
            <h1>Carrinho</h1>
            <p>Clique em <strong>Confirmar Pedido</strong> para efetuá-lo</p>
            <p>
                Caso Algum de seus Dados Pessoas abaixo estejam incorretos clique no botão 
                <strong>"Editar"</strong>
            </p>
        </section>

        <section class="hero-div">
            <div class="hero-top-div">
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
                        <?php GetCartProd();?>
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
                                <li><span>R$ 00,00</span></li>
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
                <div class="button-submit">
                    <a href="cart.php?orderConfirmed=1"><button>Confirmar Pedido</button></a>
                </div>
            </div>
        </section>
    </main>
    <?php footerOut();?>
</body>
</html>