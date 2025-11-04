<?php
    date_default_timezone_set('America/Sao_Paulo');

    if(! isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION["passwordToken"])){
        unset($_SESSION["passwordToken"]);
    }

    function getProductByName($prodName, $page): void{
        // print the product data with the $prodName above
        global $mysqli;

        $getAllNames = $mysqli->query("SELECT altName FROM product_data");
        $allowedNames = [];
        while($allNames = $getAllNames->fetch_assoc()){
            $allowedNames[] = $allNames["altName"];
        }
        $getAllNames->close();

        if(in_array($prodName, $allowedNames)){
            $getProducts = $mysqli->prepare("
                SELECT pd.idProduct, pd.printName, pd.altName, pd.brandProduct, pv.imageURL, 
                    MIN(pv.priceProduct) AS priceProduct, pv.priceDate
                    FROM product_version AS pv
                        INNER JOIN product_data AS pd ON pv.idProduct = pd.idProduct
                WHERE pd.altName = ?
                GROUP BY pd.idProduct
            ");

            $getProducts->bind_param("s", $prodName);
            if($getProducts->execute()){
                $products = $getProducts->get_result();
                $getProducts->close();
                if($products->num_rows > 0){
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
                        <li class='product-view' id='{$prodName}'>
                            <a href='{$link}' class='translate'>
                                <div class='product-img'><img src='{$imageURL}' alt='{$name} Image'></div>
                                
                                <div class='product-text'>
                                    <p><span>{$brand}</span></p>
                                    <h1>{$name}</h1>
                                    <p class='product-price'><small>a partir de:</small> <span>{$price}</span></p>
                                    <p><span>Preço Atualizado: <strong>{$priceDate}</strong></span></p>
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

    function checkSession($local){
        // check the session expiration

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
                exit();
            }
        }
    }
    
    function verifyOrders(){
        // remove orders that wasn't confirmed(Pendente/Pending) for more then 1 day
        global $mysqli;

        $getOrders = $mysqli->prepare("
            SELECT idOrder
            FROM order_data AS od
            WHERE 
                od.orderDate < NOW() - INTERVAL 1 DAY
                AND od.orderStatus <> 'Pendente'
                AND NOT EXISTS (
                    SELECT 1 FROM product_order WHERE idOrder = od.idOrder
                );
        ");

        $getOrders->execute();
        $result = $getOrders->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $idOrder = $row["idOrder"];

            $deleteOrder = $mysqli->prepare(query: "DELETE FROM order_data WHERE idOrder = ?");

            $deleteOrder->bind_param("i", $idOrder);
            $deleteOrder->execute();
            $deleteOrder->close();
        }
        $getOrders->close();
    }

/**
 * @param $local
 * @param $option
 * @return string
 */
function optionSelect($local, $option){
        // display if the option is "selected" or not at <option> using the special variable $_SESSION
        if(isset($_SESSION[$local]) && $_SESSION[$local] == $option)
            return " selected";
        return "";
    }

    function optionSelectAlt($row, $local, $option){
        // display if the option is "selected" or not at <option> using regular variables
        if(isset($row[$local]) && $row[$local] == $option)
            return " selected";
        return "";
    }

    function displayPopUp($item, $variable){
        // displays a popup box with a warning on the screen
        
        $title = match($item){
            "revAdd", "revMod", "revRem" => "Alteração",
            "orderConfirmed"        => "Pedido Confirmado",
            "loginSuccess"          => "Login Realizado com Sucesso",
            "notAdmin", "adminNotAllowed", "noItem", "outOfOrder" => "Erro",
            "prodAdd"               => "Carrinho Atualizado",
            "makeClient", "removeS", "addProduct", "addVersion", "makeAdmin"  => "Atualização",
            default => "Erro",
        };

        $mainMessage = match($item){
            "revAdd"            => "Reversão de adição <strong>realizada com sucesso</strong>.",
            "revMod"            => "Reversão de modificação <strong>realizada com sucesso</strong>.",
            "revRem"            => "Reversão de remoção <strong>realizada com sucesso</strong>.",
            "orderConfirmed"    => "Pedido no nome de <strong>$_SESSION[userMail]</strong> foi enviado para nossa central.",
            "loginSuccess"      => "Agora voce pode navegar pelo site e fazer compras em seu nome.",
            "notAdmin"          => "É preciso fazer <strong>login como administrador</strong> para acessar a página de gerenciamento.",
            "prodAdd"           => "Produto <strong style='color: var(--secondary-clr)'>{$variable}</strong> foi adicionado com sucesso ao <strong>carrinho</strong>.",
            "adminNotAllowed"   => "É preciso fazer <strong>login como cliente</strong> para acessar A página anterior.",
            "makeClient"        => "<strong>Novo cliente adicionado</strong> com sucesso.",
            "removeS"           => "Sucesso ao <strong>remover um item</strong> no banco de dados.",
            "addProduct"        => "Sucesso ao <strong>adicinar produto</strong> no banco de dados.",
            "addVersion"        => "Sucesso ao <strong>adicinar versão de um produto</strong> ao banco de dados.",
            "makeAdmin"         => "<strong>Novo administrador Adicionado</strong> com sucesso.",
            "noItem"            => "É preciso adicionar algum produto ao carrinho para concluir a compra.",
            "outOfOrder"        => "Versão do Produto <strong>{$variable}</strong> selecionado está indisponível.",
            ""                  => "Função ainda em <strong>desenvolvimento</strong> <br> <a href='changes.php'>clique aqui</a> para retornar a página principal.",
            default             => "...",
        };

        echo "
            <div class='popup-box show'>
                <div class='popup-div'>
                    <div class='popup-title'><h1>{$title}</h1></div>
                    <div class='popup-body'>
                        <p>{$mainMessage}</p>
                        <p>Clique no botão abaixo para fechar a janela.</p>
                        <button class=' popup-button regular-button'>Fechar</button>
                    </div>
                </div>
            </div>
        ";
    }