<?php
// print the amount of products on the cart at header
function verifyCartAmount(){
    global $mysqli;

    if(isset($_SESSION["idOrder"])){
        $getCartAmount = $mysqli->prepare("SELECT COUNT(*) AS itemCount FROM product_order WHERE idOrder = ?") or die("idOrder Function:" . $mysqli->errno);
        $getCartAmount->bind_param("i", $_SESSION["idOrder"]);
        
        $getCartAmount->execute();
        $result = $getCartAmount->get_result();
        $getCartAmount->close();

        if($result){
            $row = $result->fetch_assoc();
            $cartAmount = $row["itemCount"];
            
            return "<div class=\"cart-amount\">{$cartAmount}</div>";
        }else{
            return "<div class=\"cart-amount\">0</div>";
        }
    }
    return "";
}

function displayFavicon(){
    echo "<link rel=\"shortcut icon\" href='https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755358113/acai-icon_jsrexi_t30xv5.png' type=\"image/x-icon\">";
}

function displayHeader($page = "index"){
    $userPage = "/account/account.php";
    $userTitle = "Conta";

    if(isset($_SESSION["isAdmin"])){
        $userPage = "/mannager/admin.php";
        $userTitle = "Admin";
    }

    $moon       = getIcon("moon");
    $sun        = getIcon("sun");
    $menuIcon   = getIcon("menu");
    $brand      = getIcon("brand-outer");

    $menu = [
        "index" => [
            "link" => "/index.php",
            "label" => "Início",
            "icon" => ($page === "index") ? getIcon("indexFilled") : getIcon("indexEmpty")
        ],
        "user" => [
            "link" => $userPage,
            "label" => $userTitle,
            "icon" => ($page === "user") ? getIcon("userFilled") : getIcon("userEmpty")
        ],
        "product" => [
            "link" => "/products/products.php",
            "label" => "Produtos",
            "icon" => ($page === "product") ? getIcon("productFilled") : getIcon("productEmpty")
        ],
        "cart" => [
            "link" => "/cart/cart.php",
            "label" => "Carrinho",
            "icon" => ($page === "cart") ? getIcon("cartFilled") : getIcon("cartEmpty"),
            "verify" => verifyCartAmount()
        ],
    ];

    echo "
        <header>
            {$brand}
            <nav>
                <div class='nav-links'>";
                    foreach($menu as $key => $item){
                        $amountCart = $item['verify'] ?? '';
                        $class = ($page === $key) ? "selected" : "";

                        echo "
                            <a href='{$item['link']}' class='item {$class}'>
                                {$amountCart}
                                <div class='icon'>{$item['icon']}</div>
                                <span>{$item['label']}</span>
                            </a>
                        ";
                    }
    echo "      </div>
                <div class='toggle-theme mobile'>
                    <div class='selected-bg'></div>

                    <div class='icon-bg active'> {$moon} </div>

                    <div class='icon-bg'> {$sun} </div>
                </div>
            </nav>

            <div class='toggle-theme desktop'>
                    <div class='selected-bg'></div>

                    <div class='icon-bg active'> {$moon} </div>

                    <div class='icon-bg'> {$sun} </div>
                </div>

            <div class='icon-button mobile menu'>
                {$menuIcon}
            </div>
        </header>
    ";
}

function displayFooter(){
    $map = [
        "maps" => [
            "link" => "",
            "icon" => getIcon("maps")
        ],
        "wpp" => [
            "link" => "",
            "icon" => getIcon("wpp")
        ],
        "instagram" => [
            "link" => "",
            "icon" => getIcon("instagram")
        ],
        "github" => [
            "link" => "https://github.com/LuizGustavo1001",
            "icon" => getIcon("github")
        ],
    ];
    
    echo "
        <footer>
            <span>2026 Açaí e Polpas Amazônia. Todos os direitos reservados.</span>

            <nav>";
            foreach($map as $item){
                echo "
                    <a href='{$item['link']}' target='_blank' class='icon-box hover'>
                        <svg class='icon-bg' viewBox='0 0 250 250' fill='none' xmlns='http://www.w3.org/2000/svg' aria-label='icon-bg'>
                            <path d='M77.4903 246.66C83.6098 244.613 89.1887 241.685 95.3897 239.168C109.056 233.67 124.206 233.131 138.226 237.643C149.445 241.389 160.021 246.23 171.75 248.205C186.763 250.743 203.204 251.409 217.442 244.531C232.241 237.366 243.491 222.955 247.948 207.204C254.73 183.223 242.736 159.969 235.709 137.606C231.313 123.655 236.025 112.673 241.43 99.9103C248.682 82.8076 253.047 60.1883 247.387 42.0928C244.408 32.8762 239.299 24.4977 232.476 17.6421C225.653 10.7864 217.311 5.64808 208.13 2.6471C191.037 -2.68533 169.537 1.01974 153.188 7.58037C146.61 10.221 140.449 14.0079 133.504 15.6455C126.055 17.3106 118.276 16.6279 111.229 13.6907C89.923 5.04209 64.3335 -4.83468 41.0286 2.6471C26.3113 7.37567 13.899 18.3066 6.68828 31.9294C-2.14412 48.4896 -1.17521 67.2197 3.53677 84.916C7.11665 98.3853 16.408 111.312 16.3978 125.293C16.3978 133.88 13.0627 139.95 9.85 147.646C2.30268 165.711 -3.40881 189.528 2.60865 208.78C5.43498 217.752 10.4064 225.894 17.0891 232.495C23.7718 239.096 31.9626 243.955 40.947 246.649C49.8764 249.377 59.2899 250.124 68.5355 248.84C71.5815 248.394 74.5794 247.664 77.4903 246.66Z' fill='currentColor'/>
                        </svg>
                        {$item['icon']}
                    </a>
                ";
            }

    echo"   </nav>
        </footer>
    ";
}