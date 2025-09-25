<?php
include "../generalPHP.php";

$defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

if (!isset($_SESSION["isAdmin"])) {
    header("location: ../index.php?notAdmin=1");
    exit();

}

checkSession("cart");

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
            SELECT u.idUser, u.userName, u.userMail, u.userPhone, u.city, u.district, u.street, u.localNum, u.referencePoint, u.state, a.adminPicture
            FROM user_data AS u 
                JOIN admin_data AS a ON a.idAdmin = u.idUser
        ",
        "users" => "
            SELECT u.idUser, u.userName, u.userMail, u.userPhone, u.city, u.district, u.street, u.localNum, u.referencePoint, u.state, c.idClient
            FROM user_data AS u 
                JOIN client_data AS c ON c.idClient = u.idUser
        ",
        "orders" => "
            SELECT od.idOrder, od.idClient AS idPerson, ud.userName, od.orderDate, 
                    od.orderHour, od.orderStatus, po.idProduct, p.nameProduct, po.amount, po.totPrice 
            FROM order_data AS od 
                JOIN user_data AS ud ON od.idClient = ud.idUser
                JOIN product_order AS po ON od.idOrder = po.idOrder 
                JOIN product AS p ON po.idProduct = p.idProduct
        ",
    };
    $getItem = $mysqli->query($query);
    if ($getItem){
        $amount = $getItem->num_rows;
        $_SESSION[$item] = $amount;
        if($amount != 0){
            while ($row = $getItem->fetch_assoc()){
                printTable($row, $amount);
            }
        }
    }
}

function printTable($row, $amount)
{   
    if($amount == 0){
        echo "<p>Nenhum Item Encontrado para a Categoria Selecionada</p>";
    }else{
        global $defaultMoney, $mysqli;

        echo "<tr>";

        if(isset($row["brandProduct"])){
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
            echo "<td>" . $row["nameProduct"] . "</td>";
            echo "<td>" . $brandProduct . "</td>";
            echo "<td>" . numfmt_format_currency($defaultMoney, $row["priceProduct"], "BRL") . "</td>";
            echo "<td>" . $row["priceDate"] . "</td>";
            echo "<td>" . $availability . "</td>";
            echo "<td>" . $typeProduct . "</td>";
            echo "
                <td>
                    <abbr title=\"Clique Aqui para Alterar o Item em Questão\">
                        <a href=\"changeItem.php?Category=product&id=".$row["idProduct"]."\">
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                            </svg>
                        </a>
                    </abbr>
                </td>
            ";

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
            echo "
                <td>
                    <abbr title=\"Clique Aqui para Alterar o Item em Questão\">
                        <a href=\"changeItem.php?Category=changes&id=".$row["idChange"]."\">
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                            </svg>
                        </a>
                    </abbr>
                </td>
            ";

        }else if (isset($row["adminPicture"])){
            $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";

            echo "<td>" . $row["idUser"] . "</td>";
            echo "<td class=\"center-td\"><img src=\"".$row["adminPicture"]."\" alt=\"Admin Picture\"></td>";
            echo "<td>" . $row["userName"] . "</td>";
            echo "<td>" . $row["userMail"] . "</td>";
            echo "<td>" . $row["userPhone"] . "</td>";
            echo "<td>" . $address . "</td>";
            echo "
                <td>
                    <abbr title=\"Clique Aqui para Alterar o Item em Questão\">
                        <a href=\"changeItem.php?Category=admin&id=".$row["idUser"]."\">
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                            </svg>
                        </a>
                    </abbr>
                </td>
            ";

        }else if (isset($row["idClient"])){
            $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";

            echo "<td>" . $row["idUser"] . "</td>";
            echo "<td>" . $row["userName"] . "</td>";
            echo "<td>" . $row["userMail"] . "</td>";
            echo "<td>" . $row["userPhone"] . "</td>";
            echo "<td>" . $address . "</td>";
            echo "
                <td>
                    <abbr title=\"Clique Aqui para Alterar o Item em Questão\">
                        <a href=\"changeItem.php?Category=client&id=".$row["idUser"]."\">
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                            </svg>
                        </a>
                    </abbr>
                </td>
            ";

        }else if (isset($row["orderDate"])){
            $getAllProducts = $mysqli->prepare("
                SELECT po.idProduct, pd.printName, pv.sizeProduct, po.amount, po.totPrice
                FROM product_order AS po 
                    JOIN product_version AS pv ON po.idProduct = pv.idVersion
                    JOIN product_data AS pd ON pv.idProduct = pd.idProduct
                WHERE po.idOrder = ?
            ");

            $getAllProducts->bind_param("s", $row["idOrder"]);
            $getAllProducts->execute();
            $product = "";
            $result = $getAllProducts->get_result();
            while($product = $result->fetch_assoc()){
                //$prodName = matchDisplayNames($prod['nameProduct']);
                $prodName = "";
                if($product['flavor'] != null){
                    $prodName = "{$product["printName"]} - {$product["flavor"]}, {$product["sizeProduct"]}";
                }else{
                    $prodName = "{$product["printName"]} - {$product["sizeProduct"]}";
                }
                $product .= "({$product['amount']})" . $prodName . " - " .numfmt_format_currency($defaultMoney, $product['totPrice'], "BRL") . " | ";
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
            echo "<td>" . $row["userName"]."</td>";
            echo "<td>" . $date_hour . "</td>";
            echo "<td>" . $product."</td>";
            echo "<td class=\"$class\"><p>" . $orderStatus ."</p></td>";
            echo "
                <td>
                    <abbr title=\"Clique Aqui para Alterar o Item em Questão\">
                        <a href=\"changeItem.php?Category=order&id=".$row["idOrder"]."\">
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                            </svg>
                        </a>
                    </abbr>
                </td>
            ";

        }
        echo "</tr>";
    }
    
}