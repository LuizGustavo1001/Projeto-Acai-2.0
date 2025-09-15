<?php
include "../generalPHP.php";

$defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

if (!isset($_SESSION["isAdmin"])) {
    header("location: ../index.php?notAdmin=1");
    exit();

}



$getPhotoURL = $mysqli->prepare("SELECT adminPicture FROM admin_data WHERE idAdmin = ?");
$getPhotoURL->bind_param("i", $_SESSION["idUser"]);
$getPhotoURL->execute();
$result = $getPhotoURL->get_result();
$photoData = $result->fetch_assoc();
$_SESSION["adminPicture"] = $photoData["adminPicture"];

function GetTableMannager($item)
{
    global $mysqli;
    $query = match ($item) {
        "products" => "
            SELECT idProduct, nameProduct, brandProduct, priceProduct, priceDate, imageURL, availability, typeProduct
            FROM product 
        ",
        "changes" => "
            SELECT idChange, title, changeDate, changeHour, changeDesc, changeStatus 
            FROM admin_changes
        ",
        "admin" => "
            SELECT ad.idAdmin, ad.adminName, ad.adminMail, ad.adminPicture, ad.adminPhone, 
                    aa.city, aa.district, aa.street, aa.localNum, aa.referencePoint, aa.state
            FROM admin_data AS ad 
                JOIN admin_address AS aa ON ad.idAdmin = aa.idAdmin
        ",
        "users" => "
            SELECT cd.idClient, cd.clientName, cd.clientMail, cd.clientPhone, 
                    ca.city, ca.district, ca.street, ca.localNum, ca.referencePoint, ca.state
            FROM client_data AS cd 
                JOIN client_address AS ca ON cd.idClient = ca.idClient",
        "orders" => "
            SELECT od.idOrder, od.idClient , cd.clientName, od.orderDate, 
                    od.orderHour, od.orderStatus, po.idProduct, p.nameProduct, po.amount, po.totPrice 
            FROM order_data AS od 
                JOIN client_data AS cd ON od.idClient = cd.idClient 
                JOIN product_order AS po ON od.idOrder = po.idOrder 
                JOIN product AS p ON po.idProduct = p.idProduct
        ",
    };
    $getItem = $mysqli->query($query);
    if ($getItem){
        $amount = $getItem->num_rows;
        $_SESSION[$item] = $amount;
        if($amount == 0){
            printTable("", 0);
        }else{
            while ($row = $getItem->fetch_assoc()){
                printTable($row);
            }
        } 
    }
}

function printTable($row, $amount = 1)
{
    if($amount == 0){
        echo "<tr><small>Nenhum Item Encontrado para a Categoria Selecionada</small></tr>";
    }else{
        global $defaultMoney, $mysqli;

        echo "<tr>";

        if(isset($row["brandProduct"])){
            $name = matchDisplayNames($row["nameProduct"]);

            $typeProduct = match($row["typeProduct"]) {
                "Cream"         =>"Cremes",
                "Other"         =>"Outros",
                "Additional"    =>"Adicionais",
                default         =>"Outros",
            };
            $brandProduct = match($row["brandProduct"]){
                "Other Brand"   => "Outra Marca",
                default         => $row["brandProduct"],
            };

            $availability = match ($row["availability"]) {
                "1"             =>"Disponível",
                default         =>"Indisponível",
            };

            echo "<td>" . $row["idProduct"] . "</td>";
            echo "<td class=\"center-td\"> <img src=\"" . $row["imageURL"] . "\" alt=\"Product Image\"></td>";
            echo "<td>" . $name . "</td>";
            echo "<td>" . $brandProduct . "</td>";
            echo "<td>" . numfmt_format_currency($defaultMoney, $row["priceProduct"], "BRL") . "</td>";
            echo "<td>" . $row["priceDate"] . "</td>";
            echo "<td>" . $availability . "</td>";
            echo "<td>" . $typeProduct . "</td>";
            echo "<td><abbr title=\"Clique Aqui para Alterar o Item em Questão\"><a href=\"changeItem.php?Category=product&id=".$row["idProduct"]."\">+</a></abbr></td>";

        }else if (isset($row["idChange"])){
            $class = "";
            $changeStatus = "";
            switch($row["changeStatus"]){
                case "accepted":
                    $changeStatus = "Aceita";
                    $class = "accepted";
                    break;
                case "pending":
                    $changeStatus = "Pendente";
                    $class = "pending";
                    break;
                default:
                    $changeStatus = "Rejeitada";
                    $class = "rejected";
                    break;
            }

            echo "<td>" . $row["idChange"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";
            echo "<td>" . $row["changeDate"] . "</td>";
            echo "<td>" . $row["changeHour"] . "</td>";
            echo "<td>" . $row["changeDesc"] . "</td>";
            echo "<td class=\"$class\">" . $changeStatus . "</td>";
            echo "<td><abbr title=\"Clique Aqui para Alterar o Item em Questão\"><a href=\"changeItem.php?Category=changes&id=".$row["idChange"]."\">+</a></abbr></td>";

        }else if (isset($row["adminPhone"])){
            $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";

            echo "<td>" . $row["idAdmin"] . "</td>";
            echo "<td class=\"center-td\"><img src=\"".$row["adminPicture"]."\" alt=\"Admin Picture\"></td>";
            echo "<td>" . $row["adminName"] . "</td>";
            echo "<td>" . $row["adminMail"] . "</td>";
            echo "<td>" . $row["adminPhone"] . "</td>";
            echo "<td>" . $address . "</td>";
            echo "<td><abbr title=\"Clique Aqui para Alterar o Item em Questão\"><a href=\"changeItem.php?Category=admin&id=".$row["idAdmin"]."\">+</a></abbr></td>";

        }else if (isset($row["clientPhone"])){
            $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";

            echo "<td>" . $row["idClient"] . "</td>";
            echo "<td>" . $row["clientName"] . "</td>";
            echo "<td>" . $row["clientMail"] . "</td>";
            echo "<td>" . $row["clientPhone"] . "</td>";
            echo "<td>" . $address . "</td>";
            echo "<td><abbr title=\"Clique Aqui para Alterar o Item em Questão\"><a href=\"changeItem.php?Category=client&id=".$row["idClient"]."\">+</a></abbr></td>";

        }else if (isset($row["orderDate"])){
            $getAllProducts = $mysqli->prepare("
                SELECT po.idProduct, p.nameProduct, po.amount, po.totPrice
                FROM product_order AS po 
                    JOIN product AS p ON po.idProduct = p.idProduct
                WHERE po.idOrder = ?
            ");

            $getAllProducts->bind_param("s", $row["idOrder"]);
            $getAllProducts->execute();
            $product = "";
            $result = $getAllProducts->get_result();
            while($prod = $result->fetch_assoc()){
                $prodName = matchDisplayNames($prod['nameProduct']);
                $product .= "({$prod['amount']})" . $prodName . " - " .numfmt_format_currency($defaultMoney, $prod['totPrice'], "BRL") . " | ";
            }

            $date_hour = $row["orderDate"] . ", " . $row["orderHour"];

            $class = "";
            $orderStatus = "";
            switch($row["orderStatus"]){
                case "Finished":
                    $orderStatus = "Finalizado";
                    $class = "finished";
                    break;
                default:
                    $orderStatus = "Pendente";
                    $class = "pending";
                    break;  
            }

            echo "<td>" . $row["idOrder"]."</td>";
            echo "<td>" . $row["clientName"]."</td>";
            echo "<td>" . $date_hour . "</td>";
            echo "<td>" . $product."</td>";
            echo "<td class=\"$class\"><p>" . $orderStatus ."</p></td>";
            echo "<td><abbr title=\"Clique Aqui para Alterar o Item em Questão\"><a href=\"changeItem.php?Category=order&id=".$row["idOrder"]."\">+</a></abbr></td>";

        }
        echo "</tr>";
    }
    
}