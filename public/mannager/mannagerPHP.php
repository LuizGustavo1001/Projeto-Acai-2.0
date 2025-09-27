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

    function GetTableMannager($item)
    {
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
            echo "<tr>";
            if(isset($row["brandProduct"])){ // product + version
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
                echo "
                    <td style ='cursor: pointer'>
                        <abbr title='Clique Aqui para mostrar as versões do produto em questão'>
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                <path stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/>
                            </svg>
                        </abbr>
                    </td>
                ";
                echo "<td>" . $row["idProduct"] . "</td>";
                echo "<td>" . $row["printName"] . "</td>";
                echo "<td>" . $row["altName"] . "</td>";
                echo "<td>" . $brandProduct . "</td>";
                echo "<td>" . $typeProduct . "</td>";
                echo "
                    <td>
                        <abbr title=\"Clique Aqui para Alterar o Item em Questão\">
                            <a href=\"changeItem.php?category=product&id=".$row["idProduct"]."\">
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                                </svg>
                            </a>
                        </abbr>
                    </td>
                ";
            }else if (isset($row["idChange"])){ // changes
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
                            <a href=\"changeItem.php?category=changes&id=".$row["idChange"]."\">
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                                </svg>
                            </a>
                        </abbr>
                    </td>
                ";

            }else if (isset($row["adminPicture"])){ // admin
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
                            <a href=\"changeItem.php?category=admin&id=".$row["idUser"]."\">
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                                </svg>
                            </a>
                        </abbr>
                    </td>
                ";

            }else if (isset($row["idClient"])){ // client
                $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";

                echo "<td>" . $row["idUser"] . "</td>";
                echo "<td>" . $row["userName"] . "</td>";
                echo "<td>" . $row["userMail"] . "</td>";
                echo "<td>" . $row["userPhone"] . "</td>";
                echo "<td>" . $address . "</td>";
                echo "
                    <td>
                        <abbr title=\"Clique Aqui para Alterar o Item em Questão\">
                            <a href=\"changeItem.php?category=client&id=".$row["idUser"]."\">
                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                    <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                                </svg>
                            </a>
                        </abbr>
                    </td>
                ";

            }else if (isset($row["orderDate"])){ // order
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
                echo "<td>" . ($products ?: "Nenhum Produto Adicionado") . "</td>";
                echo "<td class=\"$class\"><p>" . $orderStatus ."</p></td>";

            }
            echo "</tr>";

            if(isset($row["brandProduct"])){
                // print all the versions of the product
                echo "
                    <tr class='sub-table'>
                        <th>Id</th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Data Preço</th>
                        <th>Situação</th>
                        <th></th>
                    </tr>
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

                while($row = $allVersions->fetch_assoc()){
                    echo "<tr class='sub-table'>";
                    $availability = match ($row["availability"]) {
                        "1"             => "Disponível",
                        default         => "Indisponível",
                    };

                    $price = numfmt_format_currency($defaultMoney, $row['priceProduct'], "BRL");
                    
                    echo "<td>" . $row["idVersion"] . "</td>";
                    echo "
                        <td class=\"center-td\">
                            <img src=\"".$row["imageURL"]."\" alt=\"Version Picture\">
                        </td>
                    ";
                    echo "<td>" . $row["nameProduct"] . "</td>";
                    echo "<td>" . $price . "</td>";
                    echo "<td>" . $row["priceDate"] . "</td>";
                    if($availability == "Disponível"){
                        echo "<td class='accepted'><p>". $availability ."</p></td>";
                    }else{
                        echo "<td class='rejected'><p>". $availability ."</p></td>";
                    }

                    echo "
                        <td>
                            <abbr title=\"Clique Aqui para Alterar o Item em Questão\">
                                <a href=\"changeItem.php?category=version&id=".$row["idVersion"]."\">
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10'/>
                                    </svg>
                                </a>
                            </abbr>
                        </td>
                    ";
                    echo "</tr>";
                }
            }
        }
    }