<?php 
function prodPrice($nameProd){
    global $mysqli;

    $allowedNames = 
    [
        "acaiT", "colheres", "cremesFrutados","acaiZero10", "acaiNinho1", "acaiNinho250", 
        "morango1", "leiteEmPo1", "granola1.5", "granola1", "pacoca150", "farofaPacoca1", 
        "amendoimTriturado1","ovomaltine1", "gotaChocolate1", "chocoball1", "jujuba500", 
        "disquete1", "saborazzi", "polpas"
    ];

    // Optou-se por adicionar os produtos na página web manualmente para evitar muitos produtos repetidos

    if(in_array($nameProd, $allowedNames)){ // verificar se o nome para pesquisa é um dos produtos cadastrados
        $defaultMoney = numfmt_create("pt-BR", NumberFormatter::CURRENCY);

        if(
            $nameProd == "saborazzi" or 
            $nameProd == "polpas" or 
            $nameProd == "acaiT" or 
            $nameProd == "cremesFrutados" or
            $nameProd == "colheres"
        ){
            $product = "";
            match($nameProd){
                "saborazzi"      => $product = "saborazzi%",
                "polpas"         => $product = "polpa%",
                "acaiT"          => $product = "acaiT%",
                "cremesFrutados" => $product = "creme%",
                "colheres"       => $product = "colher%"
            };

            $query1 = $mysqli->prepare("SELECT MIN(price) AS price, priceDate, brand FROM product WHERE nameProd LIKE ?");
            $query1->bind_param("s",$product);

            $query2 = $mysqli->prepare("SELECT MAX(price) AS price, priceDate, brand FROM product WHERE nameProd LIKE ?");
            $query2->bind_param("s",$product);

            $query1->execute();
            $result1 = $query1->get_result();
            $result1 = $result1->fetch_assoc();

            $query2->execute();
            $result2 = $query2->get_result();
            $result2 = $result2->fetch_assoc();

            if ($result1 && $result2 && isset($result1['price']) && isset($result2['price'])){
                $priceMin = $result1['price'];
                $priceMax = $result2['price'];
                $date = $result1['priceDate'];

                echo "  <p> 
                            <span style= \"font-size: 2em; color: var(--secondary-clr);\">" .
                            numfmt_format_currency($defaultMoney, $priceMin, "BRL") .
                            " - " . 
                            numfmt_format_currency($defaultMoney, $priceMax, "BRL") . 
                            "</span>
                        <p>
                    ";

                echo "
                    <p style=\"color: var(--primary-clr)\">
                        <small>Preço Atualizado em:
                            <strong> $date</strong>
                        </small>
                    </p>
                ";
              
            }else{
                echo "<em><small>Preço não disponível</small></em>";
            }
        }else{
            $query = $mysqli->prepare("SELECT price, priceDate, brand FROM product WHERE nameProd = ?");
            $query->bind_param("s", $nameProd);

            $query->execute();
            $result = $query->get_result();
            
            $result = $result->fetch_assoc();
            
            if ($result && isset($result['price'])) {
                $price = $result['price'];
                $date = $result['priceDate'];

                echo "<p style=\"margin-bottom: 1em;\">
                        <span style= \"font-size: 2em; color: var(--secondary-clr);\">" .
                        numfmt_format_currency($defaultMoney, $price, "BRL") .
                        "</span></p>
                    ";
                echo "<p style=\"color: var(--primary-clr)\"><small>Preço Atualizado em:<strong> $date</strong></small></p>";
                
            } else {
                echo "<em><small>Preço não disponível</small></em>";
            }
        }

    }else{
        echo "<em><small>Produto não encontrado</small></em>";
    }
}
