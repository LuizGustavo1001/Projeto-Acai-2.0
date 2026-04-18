<?php 
require_once __DIR__ . '/mainController.php';
require_once __DIR__ . '/../models/Product.php';

$productModel = new Product($mysqli);

if(isset($_SESSION["passwordToken"])){ 
    unset($_SESSION["passwordToken"]); 
}

// feature 6 random products from Database
function featureItems(){
    global $productModel;
    $randomProducts = $productModel->getRandom(6);

    foreach($randomProducts as $product){
        $name = "{$product['printName']}";
        if($product['flavor'] == null){
            $name = "{$product['printName']} - {$product['size']}";
        }else{
            $name = "{$product['printName']} - {$product['flavor']}";
        }
        $product['printName'] = $name;
        printProduct($product, "index", "outer");
    }
}

$aboutUsMap = [
    [
        "icon" => getIcon("userLove"),
        "title" => "Quem Somos?",
        "text" => [
            "Açaí Amazônia Ipatinga, <strong>distribuidora de cremes e polpas variados</strong>.",
            "Vendemos também <strong>picolés, sorvetes, adicionais variados para açaí e sorvete e outros tipos de cremes</strong>.",
            "Ofereçemos apenas produtos com qualidade comprovada."
        ]
    ],
    [
        "icon" => getIcon("industry"),
        "title" => "Produção",
        "text" => [
            "Nossa fábrica está localizada na cidade de <strong>*******</strong>.",
            "Contamos com todas as <strong>alvarás e licenças</strong> exigidos pela <strong>Vigilância Sanitária</strong>, <strong>Ministério da Agricultura</strong> e demais orgãos reguladores. ",
            "Nossa produção é supervisionada por <strong>engenheiro de alimentos</strong> altamente qualificado."
        ]
    ],
    [
        "icon" => getIcon("truck"),
        "title" => "Entregas",
        "text" => [
            "Atendemos e entregamos no <strong>**** ** ***, **** ** *** ****, e região</strong>.",
            "Realizamos entregas tanto para sua loja como para consumo próprio*.",
        ]
    ],
    [
        "icon" => getIcon("maps"),
        "title" => "Endereço",
        "text" => [
            "Nosso depósito está localizado na <strong>Rua ******, *** - ******, ****, ****</strong>.",
        ]
    ],
];

function fillAboutUs($map){
    foreach($map as $item){
        $iconBg = getIcon("iconBg");
        echo "
            <div class='item'>
                <div class='title'>
                    <div class='icon-wrapper'>
                        {$iconBg}
                        {$item['icon']}
                    </div>
                    <p>{$item['title']}</p>
                </div>

                <div class='text'>";
                    foreach($item['text'] as $text){ echo "<p>{$text}</p>"; }
        echo "  </div>
            </div>
        ";
    }
}