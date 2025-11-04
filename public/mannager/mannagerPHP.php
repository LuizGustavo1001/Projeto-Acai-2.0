<?php
    include "../generalPHP.php";

    $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

    if (! isset($_SESSION["isAdmin"])) {
        header("location: ../index.php?notAdmin=1");
        exit();
    }

    checkSession("cart");

    function displayManagerNav($page){
        $pages = [
            "admin"     => "",
            "client"    => "",
            "product"   => "",
            "order"     => "",
            "change"    => "",
        ];

        foreach($pages as $pageClass => $value){
            if($pageClass == $page){
                $pages[$pageClass] = "selected-page";
            }
        }

        echo "
            <nav class='side-bar'>
                <div class='side-bar-top'>
                    <div class='brand-icon'>
                        <a href='../index.php' class='translate-alt'><img src='https://res.cloudinary.com/dw2eqq9kk/image/upload/v1762032794/acai-icon_jsrexi_t30xv5.png' alt='Brand Icon'></a>
                        <a href='../account/logout.php' class='exit-icon mobile'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                <path fill-rule='evenodd' d='M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6Zm-5.03 4.72a.75.75 0 0 0 0 1.06l1.72 1.72H2.25a.75.75 0 0 0 0 1.5h10.94l-1.72 1.72a.75.75 0 1 0 1.06 1.06l3-3a.75.75 0 0 0 0-1.06l-3-3a.75.75 0 0 0-1.06 0Z' clip-rule='evenodd' />
                            </svg>
                        </a>
                    </div>
                    
                    <div class='nav-links'>
                        <a href='admin.php' class='{$pages['admin']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                <path fill-rule='evenodd' d='M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z' clip-rule='evenodd' />
                                <path d='M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z' />
                            </svg>
                        </a>
                        <a href='clients.php' class='{$pages['client']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                <path fill-rule='evenodd' d='M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z' clip-rule='evenodd' />
                            </svg>
                        </a>
                        <a href='orders.php' class='{$pages['order']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                <path d='M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z'/>
                            </svg>
                        </a>
                        <a href='products.php' class='{$pages['product']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                <path d='M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z'/>
                                <path fill-rule='evenodd' d='m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z' clip-rule='evenodd'/>
                            </svg>
                        </a>
                        <a href='changes.php' class='{$pages['change']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                <path d='M21 6.375c0 2.692-4.03 4.875-9 4.875S3 9.067 3 6.375 7.03 1.5 12 1.5s9 2.183 9 4.875Z' />
                                <path d='M12 12.75c2.685 0 5.19-.586 7.078-1.609a8.283 8.283 0 0 0 1.897-1.384c.016.121.025.244.025.368C21 12.817 16.97 15 12 15s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.285 8.285 0 0 0 1.897 1.384C6.809 12.164 9.315 12.75 12 12.75Z' />
                                <path d='M12 16.5c2.685 0 5.19-.586 7.078-1.609a8.282 8.282 0 0 0 1.897-1.384c.016.121.025.244.025.368 0 2.692-4.03 4.875-9 4.875s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.284 8.284 0 0 0 1.897 1.384C6.809 15.914 9.315 16.5 12 16.5Z' />
                                <path d='M12 20.25c2.685 0 5.19-.586 7.078-1.609a8.282 8.282 0 0 0 1.897-1.384c.016.121.025.244.025.368 0 2.692-4.03 4.875-9 4.875s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.284 8.284 0 0 0 1.897 1.384C6.809 19.664 9.315 20.25 12 20.25Z' />
                            </svg>
                        </a>
                    </div>
                </div>
                <a href='../account/logout.php' class='desktop exit-icon'>
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                        <path fill-rule='evenodd' d='M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6Zm-5.03 4.72a.75.75 0 0 0 0 1.06l1.72 1.72H2.25a.75.75 0 0 0 0 1.5h10.94l-1.72 1.72a.75.75 0 1 0 1.06 1.06l3-3a.75.75 0 0 0 0-1.06l-3-3a.75.75 0 0 0-1.06 0Z' clip-rule='evenodd' />
                    </svg>
                </a>
            </nav>
        ";
    }

    function displayPageNav($page){
        $picture = $_SESSION["adminPicture"];
        $name = $_SESSION["userName"];

        switch($page){
            case "admin":
                $pageIcon = "
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                        <path fill-rule='evenodd' d='M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z' clip-rule='evenodd' />
                        <path d='M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z' />
                    </svg>
                ";
                $pageName = "Administradores";
                $pageDesc = "Gerencie os admininstradores cadastrados.";
                break;
            case "client":
                $pageIcon = "
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                        <path fill-rule='evenodd' d='M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z' clip-rule='evenodd'/>
                    </svg>
                ";
                $pageName = "Clientes";
                $pageDesc = "Gerencie os clientes cadastrados.";
                break;
            case "order":
                $pageIcon = "
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                        <path d='M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z'/>
                    </svg>
                ";
                $pageName = "Pedidos";
                $pageDesc = "Gerencie os pedidos.";
                break;
            case "product":
                $pageIcon = "
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                        <path d='M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z'/>
                        <path fill-rule='evenodd' d='m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z' clip-rule='evenodd' />
                    </svg>
                ";
                $pageName = "Produtos";
                $pageDesc = "Gerencie os produtos e suas versões.";
                break;
            case "change":
                $pageIcon = "
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                        <path d='M21 6.375c0 2.692-4.03 4.875-9 4.875S3 9.067 3 6.375 7.03 1.5 12 1.5s9 2.183 9 4.875Z' />
                        <path d='M12 12.75c2.685 0 5.19-.586 7.078-1.609a8.283 8.283 0 0 0 1.897-1.384c.016.121.025.244.025.368C21 12.817 16.97 15 12 15s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.285 8.285 0 0 0 1.897 1.384C6.809 12.164 9.315 12.75 12 12.75Z' />
                        <path d='M12 16.5c2.685 0 5.19-.586 7.078-1.609a8.282 8.282 0 0 0 1.897-1.384c.016.121.025.244.025.368 0 2.692-4.03 4.875-9 4.875s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.284 8.284 0 0 0 1.897 1.384C6.809 15.914 9.315 16.5 12 16.5Z' />
                        <path d='M12 20.25c2.685 0 5.19-.586 7.078-1.609a8.282 8.282 0 0 0 1.897-1.384c.016.121.025.244.025.368 0 2.692-4.03 4.875-9 4.875s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.284 8.284 0 0 0 1.897 1.384C6.809 19.664 9.315 20.25 12 20.25Z' />
                    </svg>
                ";
                $pageName = "Alterações";
                $pageDesc = "Gerencie os alterações realizadas pelos administradores.";
                break;
            default:
                $pageIcon = "
                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                        <path fill-rule='evenodd' d='M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z' clip-rule='evenodd' />
                        <path d='M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z' />
                    </svg>
                ";
                $pageName = "Administradores";
                $pageDesc = "Gerencie os admininstradores.";
                break;
        }
        
        echo "
            <section class='top-section'>
                <ul>
                    <li class='page-data'>
                        {$pageIcon}
                        <div>
                            <h1>{$pageName}</h1>
                            <p>{$pageDesc}</p>
                        </div>
                    </li>
                    
                    <li class='admin-data'>
                        <img src='{$picture}' alt='Profile Picture'>
                        <p>{$name}</p>
                        <a href='settings.php' class='settings-icon'>
                            <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                <path fill-rule='evenodd' d='M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z' clip-rule='evenodd' />
                            </svg>
                        </a>
                    </li>
                </ul>
            </section>
        ";
    }


    $getPhotoURL = $mysqli->prepare("SELECT adminPicture FROM admin_data WHERE idAdmin = ?");
    $getPhotoURL->bind_param("i", $_SESSION["idUser"]);
    $getPhotoURL->execute();
    $result = $getPhotoURL->get_result();
    $photoData = $result->fetch_assoc();
    $getPhotoURL->close();
    $_SESSION["adminPicture"] = $photoData["adminPicture"];

    function getAmountItem($type){
        // return the amount of itens on the selected table
        global $mysqli;

        $table = match($type){
            "product" => "product_data",
            "version" => "product_version",
            "admin"   => "admin_data",
            "client"  => "client_data",
            "order"   => "order_data",
            "change"  => "change_data",
            default   => ""
        };

        if($table == ""){
            return "-";
        }else{
            $returnAmount = $mysqli->query("SELECT COUNT(*) AS amount FROM $table");
            $result = $returnAmount->fetch_assoc();
            $returnAmount->close();
            return $result["amount"];
        }
    }

    function searchColumns($formInput, $table){
        global $mysqli;

        $query = "";

        switch($table){
            case "admin":
                $query = "
                    SELECT u.idUser, u.userName, u.userMail, u.userPhone, u.city, u.district, u.street, u.localNum, u.referencePoint, u.state, a.adminPicture
                    FROM user_data AS u 
                        JOIN admin_data AS a ON a.idAdmin = u.idUser
                    WHERE u.userMail LIKE ? OR u.userName LIKE ?
                ";
                $pageTitle = "Administradores";

                break;

            case "client":
                $query = "
                    SELECT u.idUser, u.userName, u.userMail, u.userPhone, u.city, u.district, u.street, u.localNum, u.referencePoint, u.state, c.idClient
                    FROM user_data AS u 
                        JOIN client_data AS c ON c.idClient = u.idUser
                    WHERE u.userMail LIKE ? OR u.userName LIKE ?
                ";
                $pageTitle = "Clientes";

                break;
            
            case "order":
                $query = "
                    SELECT od.idOrder, od.orderDate, od.orderHour, od.orderStatus, od.idClient AS idPerson, ud.userName, po.idProduct, pv.nameProduct, pv.flavor, po.amount, po.totPrice
                    FROM order_data AS od 
                        JOIN user_data AS ud ON od.idClient = ud.idUser
                        LEFT JOIN product_order AS po ON od.idOrder = po.idOrder
                        LEFT JOIN product_version AS pv ON po.idProduct = pv.idProduct
                    WHERE od.idOrder LIKE ? OR ud.userName LIKE ?
                    ORDER BY od.idOrder
                ";
                $pageTitle = "Pedidos";

                break;
            
            case "product":
                $query = "
                    SELECT idProduct, printName, altName, brandProduct, typeProduct
                    FROM product_data
                    WHERE printName LIKE ? OR brandProduct LIKE ? 
                ";
                $pageTitle = "Produtos";

            break;
            
        }


        if($query == ""){
            echo "<p>Página não encontrada.</p>";
            exit();
        }

        // making the formInput usable for the search
        $input = "%{$formInput}%";

        $getItens = $mysqli->prepare($query);
        $getItens->bind_param("ss", $input, $input);
        $getItens->execute();
        $result = $getItens->get_result();
        $amount = $result->num_rows;
        $getItens->close();
        
        if($amount != 0){
            echo "
                <section class='table-section'>
                    <h1>
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='m15.75 15.75-2.489-2.489m0 0a3.375 3.375 0 1 0-4.773-4.773 3.375 3.375 0 0 0 4.774 4.774ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />
                        </svg>
                        {$pageTitle} encontrados com o filtro <strong>{$formInput}</strong>
                    </h1>
                    <div class='table-container'>
                        <div class='table-header'>
                            <ul>
                                <li class='small-col'>Id</li>
                                <li class='big-col'>Nome</li>
                                <li class='big-col'>Email</li>
                                <li class='regular-col'>Telefone</li>
                                <li class='big-col'>Endereço</li>
                                
                            </ul>
                        </div>
                        <div class='table-body'>

            ";

            while ($itens = $result->fetch_assoc()){
                printTable($itens, $amount);
            }
            echo "</div></div></section>";
        }else{
            echo "<p>Nenhum Item Encontrado com o Filtro digitado</p>";
        }
    }   


    function displayTable($page){
        global $mysqli;

        $query = "";
        switch($page){
            case "product":
                $query = "
                    SELECT idProduct, printName, altName, brandProduct, typeProduct
                    FROM product_data
                ";

                $sectionTitle = "Todos os Produtos e Versões";
                $tableTitle = "
                    <li class='smaller-col'></li>
                    <li class='small-col'>Id</li>
                    <li class='big-col'>Nome</li>
                    <li class='big-col'>Nome Alternativo</li>
                    <li class='regular-col'>Marca</li>
                    <li class='regular-col'>Tipo</li>
                    <li class='regular-col'></li>       
                ";

                break;

            case "change":
                $query = "
                    SELECT cd.idChange, cd.changeDate, cd.changeHour, ud.userName 
                    FROM change_data AS cd JOIN user_data AS ud ON cd.idAdmin = ud.idUser
                ";

                $sectionTitle = "Todos as Alterações";
                $tableTitle = "
                    <li class='smaller-col'></li>
                    <li class='small-col'>Id</li>
                    <li class='big-col'>Administrador</li>
                    <li class='regular-col'>Data-Hora</li>
                    
                ";
                
                break;

            case "admin":
                $query = "
                    SELECT u.idUser, u.userName, u.userMail, u.userPhone, u.city, u.district, u.street, u.localNum, u.referencePoint, u.state, a.adminPicture
                    FROM user_data AS u 
                        JOIN admin_data AS a ON a.idAdmin = u.idUser
                ";
                
                $sectionTitle = "Todos os Administradores";
                $tableTitle = "
                    <li class='small-col'>Id</li>
                    <li class='image-col'>Imagem</li>
                    <li class='big-col'>Nome</li>
                    <li class='big-col'>Email</li>
                    <li class='regular-col'>Telefone</li>
                    <li class='big-col'>Endereço</li>
                    <li class='small-col'></li>
                ";

                break;

            case "client":
                $query = "
                    SELECT u.idUser, u.userName, u.userMail, u.userPhone, u.city, u.district, u.street, u.localNum, u.referencePoint, u.state, c.idClient
                    FROM user_data AS u 
                        JOIN client_data AS c ON c.idClient = u.idUser
                ";
                
                $sectionTitle = "Todos os Clientes";
                $tableTitle = "
                    <li class='small-col'>Id</li>
                    <li class='big-col'>Nome</li>
                    <li class='big-col'>Email</li>
                    <li class='regular-col'>Telefone</li>
                    <li class='big-col'>Endereço</li>
                    <li class='regular-col'></li>
                ";
                
                break;

            case "order":
                $query = "
                    SELECT od.idOrder, od.orderDate, od.orderHour, od.orderStatus, od.idClient AS idPerson, ud.userName,
                    GROUP_CONCAT(
                            CONCAT(
                                '(', po.amount, ')',
                                pv.nameProduct, 
                                ' - ', COALESCE(pv.flavor, pv.sizeProduct, ''), 
                                pv.priceProduct
                            )
                        ) AS products
                    FROM order_data AS od
                        JOIN user_data AS ud 
                            ON od.idClient = ud.idUser
                        LEFT JOIN product_order AS po 
                            ON od.idOrder = po.idOrder
                        LEFT JOIN product_version AS pv 
                            ON po.idProduct = pv.idProduct
                    GROUP BY od.idOrder
                    ORDER BY od.idOrder;
                ";
                
                $sectionTitle = "Todos os Pedidos";
                $tableTitle = "
                    <li class='small-col'>Id</li>
                    <li class='big-col'>Nome Cliente</li>
                    <li class='regular-col'>Data-Hora</li>
                    <li class='big-col'>Produtos</li>
                    <li class='regular-col'>Situação</li>
                ";

                break;
            default:
                $query = "";
                break;
        }

        if($query == ""){
            echo "Erro: Página não encontrada";
            exit();
        }

        echo "
            <section class='table-section'>
                <div class='table-header'>
                    <h1>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                            <path fill-rule='evenodd' d='M2.625 6.75a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0A.75.75 0 0 1 8.25 6h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75ZM2.625 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0ZM7.5 12a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12A.75.75 0 0 1 7.5 12Zm-4.875 5.25a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75Z' clip-rule='evenodd'/>
                        </svg>
                        {$sectionTitle}
                    </h1>
                </div>

                <div class='table-container'>
                    <div class='table-header'>
                        <ul>{$tableTitle}</ul>
                    </div>
                <div class='table-body'>
        ";

        $getItem = $mysqli->query($query);
        if($getItem){
            $amount = $getItem->num_rows;
            if($amount != 0){
                while ($row = $getItem->fetch_assoc()){
                    printTable($row, $amount);
                }
                $getItem->close();
                echo "</div></div></section>";
            }
        }
    }

    function printTable($row, $amount){

        switch($amount){
            case 0:
                echo "<p>Nenhum Item Encontrado</p>";
                break;
            default:
                global $defaultMoney, $mysqli;
                if(isset($row["brandProduct"])){ // product + version
                    echo "
                        <details class='main-table'>
                            <summary>
                                <ul>
                                    <li class='smaller-col rotate-icon'>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5' />
                                        </svg>
                                    </li>
                                    <li class='small-col'>" . $row["idProduct"] . "</li>
                                    <li class='big-col'>"  . $row["printName"] . "</li>
                                    <li class='big-col'>"  . $row["altName"] . "</li>
                                    <li class='regular-col'>"  . $row["brandProduct"] . "</li>
                                    <li class='regular-col'>"  . $row["typeProduct"] . "</li>
                                    <li class='regular-col edit-icon'>
                                        <abbr title='Clique Aqui para Alterar os Dados do Produto ao Lado'>
                                            <a href='changeItem.php?category=product&id=". $row["idProduct"] ."'>
                                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10' />
                                                </svg>
                                            </a>
                                        </abbr>
                                    </li>
                                </ul>
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
                            <div class='sub-table'>
                                <ul class='sub-table-title'>
                                    <li class='small-col'>Id</li>
                                    <li class='image-col'>Imagem</li>
                                    <li class='big-col'>Nome</li>
                                    <li class='regular-col'>Preço</li>
                                    <li class='regular-col'>Data Preço</li>
                                    <li class='regular-col'>Situação</li>
                                    <li class='regular-col'></li>
                                </ul>
                        ";

                        while($version = $allVersions->fetch_assoc()){
                            $price = numfmt_format_currency($defaultMoney, $version['priceProduct'], "BRL");
                            echo "
                                <ul class='sub-table-item'>
                                    <li class='small-col'>" . $version["idVersion"] . "</li>
                                    <li class='image-col'><img src='".$version["imageURL"]."' alt=\"Version Picture\"></li>
                                    <li class='big-col'>" . $version["nameProduct"] . "</li>
                                    <li class='regular-col'>" . $price . "</li>
                                    <li class='regular-col'>" . $version["priceDate"] . "</li>
                            ";

                            $availability = match($version["availability"]){
                                "disponivel" => "<li class='regular-col available'>Disponível</li>",
                                default => "<li class='regular-col not-available'>Indisponível</li>"
                            };
                            echo $availability;

                            echo "  
                                    <li class='regular-col edit-icon'>
                                        <abbr title='Clique Aqui para Alterar os Dados da Versão ao Lado'>
                                            <a href='changeItem.php?category=version&id=".$version["idVersion"]."'>
                                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10' />
                                                </svg>
                                            </a>
                                        </abbr>
                                    </li>
                                </ul>
                            ";     
                        }
                        echo "</div></details>";
                    }else{
                        echo "<p style='text-align: center; background: var(--primary-bg-clr); padding: 1em;'>Nenhuma Versão relacionada ao Produto selecionado foi encontrada</p>";
                    }
                }else if(isset($row["adminPicture"])){ // admin
                    $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";
                    echo "
                        <div class='main-table'>
                            <div>
                                <ul>
                                    <li class='small-col'>" . $row["idUser"] . "</li>
                                    <li class='image-col'><img src=\"".$row["adminPicture"]."\" alt=\"Admin Picture\"></li>
                                    <li class='big-col'>" . $row["userName"] . "</li>
                                    <li class='big-col'>" . $row["userMail"] . "</li>
                                    <li class='regular-col'>" . $row["userPhone"] . "</li>
                                    <li class='big-col'>" . $address . "</li>
                                    <li class='regular-col edit-icon'>
                                        <abbr title='Clique aqui para alterar os dados do admin selecionado'>
                                            <a href='changeItem.php?category=admin&id=". $row["idUser"] ."'>
                                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10' />
                                                </svg>
                                            </a>
                                        </abbr>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    ";
                    
                }else if(isset($row["idClient"])){ // client
                    $address = "{$row["street"]}, {$row["localNum"]} - {$row["district"]}, {$row["city"]} - {$row["state"]}";
                    $id = $row['idUser'];
                    echo "
                        <div class='main-table'>
                            <div>
                                <ul>
                                    <li class='small-col'>" . $row["idUser"] . "</li>
                                    <li class='big-col'>" . $row["userName"] . "</li>
                                    <li class='big-col'>" . $row["userMail"] . "</li>
                                    <li class='regular-col'>" . $row["userPhone"] . "</li>
                                    <li class='big-col'>" . $address . "</li>
                                    <li class='regular-col edit-icon'>
                                        <abbr title='Clique aqui para alterar os dados do cliente selecionado'>
                                            <a href='changeItem.php?category=client&id=". $row["idUser"] ."'>
                                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' d='m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10' />
                                                </svg>
                                            </a>
                                        </abbr>
                                    </li>
                                </ul>
                            </div>
                        </div>
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
                        "Finalizado" => "available",
                        default      => "pending",

                    };

                    echo "
                        <div class='main-table'>
                            <div>
                                <ul>
                                    <li class='small-col'>". $row["idOrder"]. "</li>
                                    <li class='big-col'>" . $row["userName"]. "</li>
                                    <li class='regular-col'>" . $date_hour . "</li>
                                    <li class='big-col'>" . ($products ?: "Nenhum Produto Adicionado") . "</li>
                                    <li class='regular-col $class'>" . $row["orderStatus"] ."</li>
                                </ul>
                            </div>
                        </div>
                    ";
                    
                }else if (isset($row["idChange"])) { // changes
                    $dateHour  = "{$row['changeDate']}, {$row['changeHour']}";
                    $idChange  = $row["idChange"];
                    $adminName = $row["userName"];

                    echo "
                        <details class='main-table'>
                            <summary>
                                <ul>
                                    <li class='smaller-col rotate-icon'>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5' />
                                        </svg>
                                    </li>
                                    <li class='small-col'>{$idChange}</li>
                                    <li class='big-col'>{$adminName}</li>
                                    <li class='regular-col'>{$dateHour}</li>
                                </ul>
                            </summary>
                    ";

                    // get all columns changed
                    $getChangeColumns = $mysqli->prepare("
                        SELECT idAttribute, tableName, objectChanged, oldValue, newValue, typeChange 
                        FROM attribute_change 
                        WHERE idChange = ?
                    ");
                    $getChangeColumns->bind_param("i", $idChange);
                    $getChangeColumns->execute();
                    $result = $getChangeColumns->get_result();
                    

                    switch($amount = $result->num_rows){
                        case 0:
                            echo "<p style='text-align: center; background: var(--primary-bg-clr); padding: 1em;'>Nenhuma Mudança Relacionada a esta Alteração foi encontrada</p>";
                            break;
                    }

                    $itemCounter = 1;

                    echo "<div>";

                    while ($subRow = $result->fetch_assoc()) {
                        $itemType = match($subRow["tableName"]) {
                            "product_data"      => "Produto",
                            "product_version"   => "Versão Produto",
                            "admin_data"        => "Admin",
                            "client_data"       => "Cliente",
                            default             => "Usuário",
                        };

                        $description = match($subRow["typeChange"]) {
                            "Remover"   => "<em>Código</em> = <strong>{$subRow['idAttribute']}</strong> na <em>Tabela</em> <strong>{$subRow['tableName']}</strong>",
                            "Adicionar" => "<em>Código</em> = <strong>{$subRow['idAttribute']}</strong> na <em>Tabela</em> <strong>{$subRow['tableName']}</strong>",
                            default     => "<strong>{$subRow['objectChanged']}</strong> com <em>Código</em> = <strong>{$subRow['idAttribute']}</strong> - <strong>Anteriormente:</strong> <em>{$subRow['oldValue']}</em>, <strong>Agora:</strong> <em>{$subRow['newValue']}</em>",
                        };

                        echo "
                            <div class='sub-table'>
                                <ul class='sub-table-title'>
                                    <li class='small-col'>Id</li>
                                    <li class='regular-col'>Tabela</li>
                                    <li class='regular-col'>Ação</li>
                                    <li class='big-col'>Descrição</li>
                                    <li class='regular-col'></li>
                                </ul>

                                <ul class='sub-table-item'>
                                    <li class='small-col'>{$itemCounter}</li>
                                    <li class='regular-col'>{$itemType}</li>
                                    <li class='regular-col'>{$subRow["typeChange"]}</li>
                                    <li class='big-col'>{$description}</li>
                                    <li class='regular-col'>
                                        <abbr title='Clique Aqui para Reverter a Alteração'>
                                            <a href='changes.php?reverse=1&idChange={$idChange}&idAttribute={$subRow['idAttribute']}&table={$subRow["tableName"]}&type={$subRow['typeChange']}'>
                                                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                    <path stroke-linecap='round' stroke-linejoin='round' d='M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99'/>
                                                </svg>
                                            </a>
                                        </abbr>
                                    </li>
                                </ul>
                        ";
                        $itemCounter++;
                    }
                    echo "</div></details>";
                }
                break;
        }
    }

    function addChange($changeType, $table, $idAttribute, $objectChanged, $oldValue = "", $newValue = ""){
        global $mysqli;
        // add change into change_data and attribute_change table
        $currentDate = date("Y-m-d");
        $currentHour = date("H:i:s");

        $addChange = $mysqli->prepare("INSERT INTO change_data (changeDate, changeHour, idAdmin) VALUES (?,?,?)");
        $addChange->bind_param("ssi", $currentDate, $currentHour, $_SESSION["idUser"]);
        $addChange->execute();
        $addChange->close();

        $idChange = $mysqli->insert_id;

        switch($changeType){
            case "Modificar":
                $addAttribute = $mysqli->prepare("INSERT INTO attribute_change (idChange, tableName, idAttribute, objectChanged, oldValue, newValue) VALUES (?,?,?,?,?,?)");
                $addAttribute->bind_param("isisss", $idChange, $table, $idAttribute, $objectChanged, $oldValue, $newValue);

                break;
            default:
                $objectChanged = "";
                $addAttribute = $mysqli->prepare("INSERT INTO attribute_change (idChange, tableName, idAttribute, objectChanged, typeChange) VALUES (?,?,?,?,?)");
                $addAttribute->bind_param("isiss", $idChange, $table, $idAttribute, $objectChanged, $changeType);

                break;

        }
        $addAttribute->execute();
        $addAttribute->close();
    }