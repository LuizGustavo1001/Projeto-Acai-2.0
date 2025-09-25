<?php 

function verifyCartAmount(){ // imprime a quantidade de produtos no carrinho do cliente logado
    global $mysqli;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(isset($_SESSION["idOrder"])){
        $stmt = $mysqli->prepare("SELECT COUNT(*) as itemCount FROM product_order WHERE idOrder = ?");
        $stmt->bind_param("i", $_SESSION["idOrder"]);
        
        if($stmt->execute()){
            $result = $stmt->get_result();
            if ($result) {
                $row = $result->fetch_assoc();
                $cartAmount = $row["itemCount"];
                echo "<p class=\"numberItens\">$cartAmount</p>";
            } else {
                echo "<p class=\"numberItens\">0</p>";
            }
        }else{
            echo "erro ao executar o stmt";
        }
    }
}

function faviconOut(){
    echo "
        <link rel=\"shortcut icon\" href='https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755358113/acai-icon_jsrexi_t30xv5.png' type=\"image/x-icon\">
    ";
}

function headerOut($local){

    echo "
        <header>
            <ul class=\"left-header\">
                <li class=\"acai-icon\">
    ";

    $link = match($local){
        0 => "<a href=\"index.php\">",
        1 => "<a href=\"../index.php\">",
        2 => "<a href=\"../../index.php\">",
    };
    echo $link;

    echo "
                        <img src=\"https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079683/acai-icon-white_fll4gt.png\" class=\"item-translate\" alt=\"Açaí Icon\">
                    </a>
                    <p>Açaí e Polpas Amazônia</p>
                </li>
            </ul>
            
            <ul class=\"right-header\">
                <li>
    ";
    if(isset($_SESSION["isAdmin"])){
        $link = match($local){
            0=> "<a href='mannager/admin.php'>",
            1=> "<a href='../mannager/admin.php'>",
            2=> "<a href='../../mannager/admin.php'>",

        };
        echo $link;

        echo "
                        <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"size-6\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z\" />
                        </svg>

                        <p>Página Admin</p>
                    </a>
                </li>

                <li>
        ";

    }else{
        $link = match($local){
            0 => "<a href=\"account/account.php\">",
            1 => "<a href=\"../account/account.php\">",
            2 => "<a href=\"../../account/account.php\">",
        };
        echo $link;

        echo "
                            <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"size-6\">
                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z\" />
                            </svg>
                            <p><span>Minha</span> Conta</p>
                        </a>
                    </li>

                    <li>
        ";
    }
    

    $link = match($local){
        0 => "<a href=\"products/products.php\">",
        1 => "<a href=\"../products/products.php\">",
        2 => "<a href=\"../../products/products.php\">",
    };
    echo $link;

    echo "
                    <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"size-6\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z\" />
                        </svg>

                        <p>Produtos</p>
                    </a>
                </li>

                <li style=\"display: flex; align-items: top\">
    ";

    $link = match($local){
        0 => "<a href=\"cart/cart.php\">",
        1 => "<a href=\"../cart/cart.php\">",
        2 => "<a href=\"../../cart/cart.php\">",
    };
    echo $link;

    echo "
                        <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"size-6\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z\" />
                        </svg>

                        <p>Carrinho</p>
                    </a>
                    
                

    ";

    echo verifyCartAmount();
    echo "
                </li>
            </ul>
        </header>
    ";

}

function footerOut(){
    echo "
        <footer>
            <ul>
                <li>
                    <strong>
                        <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"size-6\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25\" />
                        </svg>
                        Endereço: 
                    </strong>
                    <a href=\"#\">
                        <p>Endereço Google Maps</p>
                    </a>
                </li>

                <li>
                    <strong>
                        <i class=\"fa-brands fa-instagram fa-xl\"></i>
                        Instagram: 
                    </strong>
                    <a href=\"https://www.instagram.com\" target=\"_blank\">
                        <p>@Instagram</p>
                    </a>
                </li>

                <li>
                    <strong>
                        <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke-width=\"1.5\" stroke=\"currentColor\" class=\"size-6\">
                            <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z\" />
                        </svg>
                        Telefone: 
                    </strong>
                    <a href=\"tel:31957401232\" target=\"_blank\">
                        <p>Telefone Aqui</p>
                    </a>
                </li>

                <li>
                    <strong>
                        <i class=\"fa-brands fa-whatsapp fa-xl\"></i>
                        WhatsApp: 
                    </strong>
                    <a href=\"#\" target=\"_blank\">
                        <p>WhatsApp Aqui</p>
                    </a>
                </li>

                <li style=\"color: rgb(238, 224, 250); opacity: 0.8;\">
                    2025 &copy; Açaí e Polpas Amazônia. <br> Todos os direitos reservados
                </li>
                
                <li>
                    <strong>
                        <i class=\"fa-brands fa-github fa-xl\"></i>
                        Desenvolvido Por:
                    </strong>
                    <a href=\"https://www.github.com/LuizGustavo1001\" target=\"_blank\">
                        <p>Luiz Gustavo</p>
                    </a>
                </li>
            </ul>
        </footer>
    ";
}
