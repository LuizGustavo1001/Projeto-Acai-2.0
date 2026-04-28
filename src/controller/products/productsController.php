<?php 
require_once __DIR__ . '/../mainController.php';
require_once __DIR__ . '/../../models/Product.php';

checkSessionStatus();

$productModel = new Product($mysqli);

$mapCategories = [ // products categories map
    "cream" => [
        "title" => "Cremes",
        "label" => "Cream",
    ],
    "additional" => [
        "title" => "Adicionais",
        "label" => "Additional",
    ],
    "other" => [
        "title" => "Outros",
        "label" => "Other",
    ],
];

function displayCategories($map){
    foreach($map as $item){
        echo "
            <div class='category'>
                <div class='section-title'><h1>{$item['title']}</h1></div>

                <div class='products'>";
                categoryItems($item["label"], $_GET['filter'] ?? 'nofilter');
        echo "  </div>
            </div>
        ";
    }
}

// search bar result
function searchOutput($prodName){
    global $productModel;

    $products = $productModel->getLikeName($prodName, 'pd.printName');

    echo "<div class='category'>";

    if(!$products){
        echo "
            <div class='search-label'>
                <p>Nenhum Produto encontrado com o nome <em>" . htmlspecialchars($prodName, ENT_QUOTES, 'UTF-8') . "</em></p>
            </div>
            <div class='products'>
        ";
    }else{
        echo "
            <div class='search-label'>
                <p>Produtos encontrados com o nome: <em>" . htmlspecialchars($prodName, ENT_QUOTES, 'UTF-8') . "</em></p>
            </div>

            <div class='products'>
        ";
        
        foreach($products as $product){
            printProduct($product);
        }
    }
    echo '  </div>
        </div>
    ';
}


// print the products based on selected filter at HTML
function categoryItems($type, $orderType){
    global $productModel;

    $types = $productModel->getTypes();

    if(in_array($type, $types)){
        $products = $productModel->getByType($type, $orderType);

        foreach($products as $product){
            printProduct($product);
        }
    }else{
        echo "<div class='search-label'> <p>Erro: Nenhum Produto encontrado com o Tipo Inserido</p> </div>";
    }
}