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
    $getPhotoURL->close();
    $_SESSION["adminPicture"] = $photoData["adminPicture"];

    function GetTableMannager($item){
        global $mysqli;
        $query = match ($item) {
            "products" => "
                SELECT idProduct, printName, altName, brandProduct, typeProduct
                FROM product_data
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
                SELECT od.idOrder, od.orderDate, od.orderHour, od.orderStatus, od.idClient AS idPerson, ud.userName, po.idProduct, pv.nameProduct, pv.flavor, po.amount, po.totPrice
                FROM order_data AS od 
                    JOIN user_data AS ud ON od.idClient = ud.idUser
                    LEFT JOIN product_order AS po ON od.idOrder = po.idOrder
                    LEFT JOIN product_version AS pv ON po.idProduct = pv.idProduct
                ORDER BY od.idOrder
            ",
        };
        $getItem = $mysqli->query($query);
        if($getItem){
            $amount = $getItem->num_rows;
            if($amount != 0){
                while ($row = $getItem->fetch_assoc()){
                    printTable($row, $amount);
                }
                $getItem->close();
            }
        }
    }

    function getAmountItem($type){
        // return the amount of itens on the selected table
        global $mysqli;

        $table = match($type){
            "product" => "product_version",
            "admin"   => "admin_data",
            "client"  => "client_data",
            "order"   => "order_data"

        };

        $returnAmount = $mysqli->query("SELECT COUNT(*) AS amount FROM $table");
        $result = $returnAmount->fetch_assoc();
        $returnAmount->close();
        return $result["amount"];
    }

    function searchColumns($formInput, $table){
        global $mysqli;
        $query = match ($table) {
            "admin" => "
                SELECT u.idUser, u.userName, u.userMail, u.userPhone, u.city, u.district, u.street, u.localNum, u.referencePoint, u.state, a.adminPicture
                FROM user_data AS u 
                    JOIN admin_data AS a ON a.idAdmin = u.idUser
                WHERE u.userMail LIKE ? OR u.userName LIKE ?
            ",
            "user" => "
                SELECT u.idUser, u.userName, u.userMail, u.userPhone, u.city, u.district, u.street, u.localNum, u.referencePoint, u.state, c.idClient
                FROM user_data AS u 
                    JOIN client_data AS c ON c.idClient = u.idUser
                WHERE u.userMail LIKE ? OR u.userName LIKE ?
            ",
            "order" => "
                SELECT od.idOrder, od.orderDate, od.orderHour, od.orderStatus, od.idClient AS idPerson, ud.userName, po.idProduct, pv.nameProduct, pv.flavor, po.amount, po.totPrice
                FROM order_data AS od 
                    JOIN user_data AS ud ON od.idClient = ud.idUser
                    LEFT JOIN product_order AS po ON od.idOrder = po.idOrder
                    LEFT JOIN product_version AS pv ON po.idProduct = pv.idProduct
                WHERE od.idOrder LIKE ? OR ud.userName LIKE ?
                ORDER BY od.idOrder
            ",
            "product" => "
                SELECT idProduct, printName, altName, brandProduct, typeProduct
                FROM product_data
                WHERE printName LIKE ? OR brandProduct LIKE ? 
            ",
        };
        // making the formInput usable for the search
        $input = "%{$formInput}%";

        $getItens = $mysqli->prepare($query);
        $getItens->bind_param("ss", $input, $input);
        $getItens->execute();
        $result = $getItens->get_result();
        $amount = $result->num_rows;
        $getItens->close();

        if($amount != 0){
            while ($itens = $result->fetch_assoc()){
                    printTable($itens, $amount);
                }
        }else{
            echo "<p>Nenhum Item Encontrado com o Filtro digitado</p>";
        }
    }   

    function printTable($row, $amount){
        if($amount == 0){
            echo "<p>Nenhum Item Encontrado</p>";
        }else{
            global $defaultMoney, $mysqli;
            if(isset($row["brandProduct"])){ // product + version
                echo "
                    <tr class='table-tuple'>
                        <td colspan='7'>
                            <details>
                                <summary>
                                    <table class='row-table'>
                                        <tr>
                                            <td class='table-svg align-left-td smaller-td'>
                                                <abbr title='Clique Aqui para mostrar as versões do Produto'>
                                                    <svg class='rotate-icon' xmlns='http://www.w3.org/2000/svg' fill='none'
                                                        viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor'>
                                                        <path stroke-linecap='round' stroke-linejoin='round'
                                                            d='m19.5 8.25-7.5 7.5-7.5-7.5' />
                                                    </svg>
                                                </abbr>
                                            </td>
                                            <td class='smaller-td'>" . $row["idProduct"] . "</td>
                                            <td class='normal-td'>" . $row["printName"] . "</td>
                                            <td class='normal-td'>" . $row["altName"] . "</td>
                                            <td class='normal-td'>" . $row["brandProduct"] . "</td>
                                            <td class='normal-td'>" . $row["typeProduct"] . "</td>
                                            <td class='table-svg align-right-td smaller-td'>
                                                <abbr title='Clique Aqui para Alterar os Dados do Produto ao Lado'>
                                                    <a href='changeItem.php?category=product&id=".$row["idProduct"]."'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                            <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10' />
                                                        </svg>
                                                    </a>
                                                </abbr>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr>
                                </summary>
                ";
            
                $getAllVersions = $mysqli->prepare("
                    SELECT idVersion, nameProduct, priceProduct, priceDate, availability, imageURL 
                    FROM product_version
                    WHERE idProduct = ?
                ");
                $getAllVersions->bind_param("i", $row["idProduct"]);
                $getAllVersions->execute();
                $allVersions = $getAllVersions->get_result();
                $getAllVersions->close();
                $versionsAmount = $allVersions->num_rows;
                if($versionsAmount != 0){
                    echo "
                        <div>
                            <table class='sub-table'>
                                <tr>
                                    <th class='smaller-td'>Id</th>
                                    <th style='width: 50px;'>Imagem</th>
                                    <th class='normal-td'>Nome</th>
                                    <th class='normal-td'>Preço</th>
                                    <th class='normal-td'>Data Preço</th>
                                    <th class='normal-td'>Situação</th>
                                </tr>";
                    

                    while($row = $allVersions->fetch_assoc()){
                        $price = numfmt_format_currency($defaultMoney, $row['priceProduct'], "BRL");
                        echo "
                            <tr>
                                <td class='smaller-td'>" . $row["idVersion"] . "</td>
                                <td class='table-img' style='width: 50px;'>
                                    <img src=\"".$row["imageURL"]."\" alt=\"Version Picture\">
                                </td>
                                <td class='normal-td'>" . $row["nameProduct"] . "</td>
                                <td class='normal-td'>" . $price . "</td>
                                <td class='normal-td'>" . $row["priceDate"] . "</td>
                        ";
                        $availability = match($row["availability"]){
                            "1" => "<td class='accepted normal-td'><p>Disponível</p></td>",
                            default => "<td class='rejected normal-td'><p>Indisponível</p></td>"
                        };
                        echo $availability;

                        echo "  
                            <td class='table-svg align-right-td'>
                                    <abbr title='Clique Aqui para Alterar os Dados da Versão ao Lado'>
                                        <a href='changeItem.php?category=version&id=".$row["idVersion"]."'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10' />
                                            </svg>
                                        </a>
                                    </abbr>
                                </td>
                            </tr>
                        ";     
                    }
                }
                echo " 
                                </details>
                            </td>
                        </tr>
                    </table>
                </div>
            ";
            
            
            }else if(isset($row["adminPicture"])){ // admin
                $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";
                echo "
                    <tr class='table-tuple'>
                        <table class='row-table'>
                            <tr>
                                <td class='smaller-td'>" . $row["idUser"] . "</td>
                                <td class='table-img normal-td'>
                                    <img src=\"".$row["adminPicture"]."\" alt=\"Admin Picture\">
                                </td>
                                <td class='normal-td'>" . $row["userName"] . "</td>
                                <td class='normal-td'>" . $row["userMail"] . "</td>
                                <td class='normal-td'>" . $row["userPhone"] . "</td>
                                <td class='normal-td'>" . $address . "</td>
                                <td class='table-svg align-right-td smaller-td'>
                                    <abbr title='Clique Aqui para Alterar os Dados do Administrador ao lado'>
                                        <a href='changeItem.php?category=admin&id=".$row["idUser"]."'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10' />
                                            </svg>
                                        </a>
                                    </abbr>
                                </td>
                            </tr>
                        </table>
                    </tr>
                ";
            }else if(isset($row["idClient"])){ // client
                $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";
                echo "
                    <tr class='table-tuple'>
                        <table class='row-table'>
                            <tr>
                                <td class='smaller-td'>" . $row["idUser"] . "</td>
                                <td class='normal-td'>" . $row["userName"] . "</td>
                                <td class='normal-td'>" . $row["userMail"] . "</td>
                                <td class='normal-td'>" . $row["userPhone"] . "</td>
                                <td class='normal-td'>" . $address . "</td>
                                <td class='table-svg align-right-td smaller-td'>
                                    <abbr title='Clique Aqui para Alterar os Dados do Cliente ao lado'>
                                        <a href='changeItem.php?category=client&id=".$row["idUser"]."'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10' />
                                            </svg>
                                        </a>
                                    </abbr>
                                </td>
                            </tr>
                        </table>
                    </tr>
                ";
            }else if(isset($row["orderDate"])){ // order
                $getAllProducts = $mysqli->prepare("
                    SELECT po.idProduct, pd.printName, pv.sizeProduct, pv.flavor, po.amount, po.totPrice
                    FROM product_order AS po 
                        JOIN product_version AS pv ON po.idProduct = pv.idVersion
                        JOIN product_data AS pd ON pv.idProduct = pd.idProduct
                    WHERE po.idOrder = ?
                ");

                $getAllProducts->bind_param("s", $row["idOrder"]);
                $getAllProducts->execute();
                $products = "";
                $result = $getAllProducts->get_result();
                $getAllProducts->close();
                while($product = $result->fetch_assoc()){
                    $prodName = "";
                    if($product['flavor'] != null){
                        $prodName = "{$product["printName"]} - {$product["flavor"]}, {$product["sizeProduct"]}";
                    }else{
                        $prodName = "{$product["printName"]} - {$product["sizeProduct"]}";
                    }
                    $products .= "({$product['amount']})" . $prodName . " - " .numfmt_format_currency($defaultMoney, $product['totPrice'], "BRL") . " | ";
                }

                $date_hour = $row["orderDate"] . ", " . $row["orderHour"];
                $class = match($row["orderStatus"]){
                    "Finalizado" => "finished",
                    default      => "pending",

                };

                echo "
                    <tr class='table-tuple'>
                        <table class='row-table'>
                            <tr>
                                <td class='smaller-td'>" . $row["idOrder"]. "</td>
                                <td class='normal-td'>" . $row["userName"]. "</td>
                                <td class='normal-td'>" . $date_hour . "</td>
                                <td class='normal-td'>" . ($products ?: "Nenhum Produto Adicionado") . "</td>
                                <td class=\"$class normal-td\"><p>" . $row["orderStatus"] ."</p></td>
                            </tr>
                        </table>
                    </tr>
                ";
            }
        }
        echo "<hr>";
    }