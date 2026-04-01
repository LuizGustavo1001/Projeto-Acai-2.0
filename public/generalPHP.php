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
                SELECT pd.idProduct, pd.printName, pd.altName, pd.brandProduct,
                    pv.imageURL, pv.priceProduct, pv.priceDate
                FROM product_data pd
                JOIN product_version pv 
                    ON pv.idProduct = pd.idProduct
                WHERE pd.altName = ?
                AND pv.priceProduct = (
                    SELECT MIN(pv2.priceProduct)
                    FROM product_version pv2
                    WHERE pv2.idProduct = pd.idProduct
                )
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
                        <div class='product' id='{$prodName}'>
                            <img src='{$imageURL}' alt='{$name} Image'>
                            <div class='content'>
                                <div class='title'>
                                    <p>{$brand}</p>
                                    <h1>{$name}</h1>
                                </div>

                                <div class='price'>A partir de: <span class='value'>{$price}</span></div>

                                <div class='others'>
                                    <span>Atualizado em: <strong>{$priceDate}</strong></span>
                                </div>

                                <a class='regular-btn' href='{$link}'>Adicionar ao Carrinho</a>
                            </div>
                        </div>
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

    
    function FillWarning($item, $variable, $type){
        $smileFace = "
            <svg viewBox='0 0 40 40' fill='none' xmlns='http://www.w3.org/2000/svg'>
                <path d='M15 26.6667C16.4173 27.7172 18.141 28.3334 20 28.3334C21.859 28.3334 23.5827 27.7172 25 26.6667' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
                <path d='M25.0007 20C25.9211 20 26.6673 18.8807 26.6673 17.5C26.6673 16.1193 25.9211 15 25.0007 15C24.0802 15 23.334 16.1193 23.334 17.5C23.334 18.8807 24.0802 20 25.0007 20Z' fill='currentColor'/>
                <path d='M15.0007 20C15.9211 20 16.6673 18.8807 16.6673 17.5C16.6673 16.1193 15.9211 15 15.0007 15C14.0802 15 13.334 16.1193 13.334 17.5C13.334 18.8807 14.0802 20 15.0007 20Z' fill='currentColor'/>
                <path d='M36.6673 19.9999C36.6673 27.8566 36.6673 31.7851 34.2265 34.2258C31.7858 36.6666 27.8573 36.6666 20.0007 36.6666C12.1439 36.6666 8.21553 36.6666 5.77477 34.2258C3.33398 31.7851 3.33398 27.8566 3.33398 19.9999C3.33398 12.1432 3.33398 8.2148 5.77477 5.77404C8.21553 3.33325 12.1439 3.33325 20.0007 3.33325C27.8573 3.33325 31.7858 3.33325 34.2265 5.77404C35.8495 7.39694 36.3933 9.6775 36.5755 13.3333' stroke='currentColor' stroke-width='2' stroke-linecap='round'/>
            </svg>
        ";
        $sadFace = "
            <svg viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'>
                <path d='M9 17C9.85038 16.3697 10.8846 16 12 16C13.1154 16 14.1496 16.3697 15 17' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'/>
                    <ellipse cx='15' cy='10.5' rx='1' ry='1.5' fill='currentColor'/>
                    <ellipse cx='9' cy='10.5' rx='1' ry='1.5' fill='currentColor'/>
                <path d='M22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C21.5093 4.43821 21.8356 5.80655 21.9449 8' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'/>
            </svg>
        ";
        $icon = $sadFace;
        $class = "error";

        if($type == 1){ 
            $icon = $smileFace; 
            $class = "success";
        }

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
            <div class='warning {$class} fade-in'>
                {$icon}
                <span>
                    {$mainMessage} <em>Clique no botão abaixo para fechar esta mensagem</em>.
                </span>
            </div>
        ";
    }