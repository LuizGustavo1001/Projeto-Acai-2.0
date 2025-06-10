<?php 
    include "../databaseConnection.php";

    function prodPrice($nameProd){
        global $mysqli;

        $allowedNames = 
        [
            "acai10", "acai5", "acai1", "colher200", "colher500", "colher800", 
            "cremeCupuacu10", "cremeNinho10", "cremeMaracuja10", "cremeMorango10",
            "acaiZero10", "acaiNinho1", "acaiNinho250", "morango1", "leiteEmPo1", 
            "granola1.5", "granola1", "pacoca150", "farofaPacoca1", "amendoimTriturado1",
            "ovomaltine1", "gotaChocolate1", "chocoball1", "jujuba500", "disquete1", "cremeSaborazzi"
        ]; 

        if(in_array($nameProd, $allowedNames)){ // verificar se o nome para pesquisa é um dos produtos cadastrados
            $stmt = $mysqli->prepare("SELECT price FROM product WHERE nameProd = ?");
            $stmt->bind_param("s",$nameProd);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $defaultMoney = numfmt_create("pt-BR", style: NumberFormatter::CURRENCY);
            
            $price = $result->fetch_assoc()['price'];

            echo numfmt_format_currency($defaultMoney, $price, "BRL");

        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../styles/general-style.css">
    <link rel="stylesheet" href="../styles/products.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Sansita+Swashed:wght@300..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gochi+Hand&family=Leckerli+One&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="../icon/iconeAcai.ico" type="image/x-icon">

    <title>Açaí Amazônia - Produtos</title>
</head>
<body>
    
    <header>
        <ul>
            <li class="acai-icon">
                <a href="../index.php">
                    <img src="../general-images/acai-icon-2.png" alt="" style="width:100px">
                </a>
            </li>
        </ul>
        <ul class="right-header">
            <li>
                <a href="../account/account.php">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd" d="M1 6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3H4a3 3 0 0 1-3-3V6Zm4 1.5a2 2 0 1 1 4 0 2 2 0 0 1-4 0Zm2 3a4 4 0 0 0-3.665 2.395.75.75 0 0 0 .416 1A8.98 8.98 0 0 0 7 14.5a8.98 8.98 0 0 0 3.249-.604.75.75 0 0 0 .416-1.001A4.001 4.001 0 0 0 7 10.5Zm5-3.75a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75Zm0 6.5a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75Zm.75-4a.75.75 0 0 0 0 1.5h2.5a.75.75 0 0 0 0-1.5h-2.5Z" clip-rule="evenodd" />
                    </svg>
                    <p>Sua Conta</p>
                </a>
            </li>
            <li>
                <a href="products/products.php">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path d="M2.879 7.121A3 3 0 0 0 7.5 6.66a2.997 2.997 0 0 0 2.5 1.34 2.997 2.997 0 0 0 2.5-1.34 3 3 0 1 0 4.622-3.78l-.293-.293A2 2 0 0 0 15.415 2H4.585a2 2 0 0 0-1.414.586l-.292.292a3 3 0 0 0 0 4.243ZM3 9.032a4.507 4.507 0 0 0 4.5-.29A4.48 4.48 0 0 0 10 9.5a4.48 4.48 0 0 0 2.5-.758 4.507 4.507 0 0 0 4.5.29V16.5h.25a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75v-3.5a.75.75 0 0 0-.75-.75h-2.5a.75.75 0 0 0-.75.75v3.5a.75.75 0 0 1-.75.75h-4.5a.75.75 0 0 1 0-1.5H3V9.032Z" />
                    </svg>
                    <p>Produtos</p>
                </a>
            </li>
            <li>
                 <a href="../cart/cart.php">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path d="M1 1.75A.75.75 0 0 1 1.75 1h1.628a1.75 1.75 0 0 1 1.734 1.51L5.18 3a65.25 65.25 0 0 1 13.36 1.412.75.75 0 0 1 .58.875 48.645 48.645 0 0 1-1.618 6.2.75.75 0 0 1-.712.513H6a2.503 2.503 0 0 0-2.292 1.5H17.25a.75.75 0 0 1 0 1.5H2.76a.75.75 0 0 1-.748-.807 4.002 4.002 0 0 1 2.716-3.486L3.626 2.716a.25.25 0 0 0-.248-.216H1.75A.75.75 0 0 1 1 1.75ZM6 17.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM15.5 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                    </svg>
                    <p>Carrinho</p>
                    <p class="numberItens">N</p>
                 </a>
            </li>
        </ul>

    </header>

    <main>
        <section class="products-header">
            <div id="products-header-title">
                <h1>Nossos Produtos</h1>
                <p>Adicione Produtos ao carrinho para realizar sua compra</p>
                <p>*Preços podem ser modificados com o tempo</p>
            </div>
            
            <div class="products-search-div">
                <div class="products-search-item">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <label for="inameProd">Pesquisar pelo Nome</label>
                    </div>
                    <div class="search-input generic-button">
                        <input type="text" name="nameProd" id="inameProd" placeholder="Nome do Produto">
                        <button>Pesquisar</button>
                    </div>              
                </div>

                <div class="products-search-div">
                    <div class="products-search-item">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                            </svg>
                            <label for="ifilter">Filtrar Por</label>
                        </div>

                        <div class="search-input generic-button">
                            <select name="filter" id="ifilter">
                                <option value="idProd" selected>
                                    Id
                                </option>
                                <option value="nameAsc">
                                    Ordem Alfabética(A-Z)
                                </option>
                                <option value="nameDesc">
                                    Ordem Alfabética(Z-A)
                                </option>
                                <option value="priceAsc">
                                    Maior Preço
                                </option>
                                <option value="priceDesc">
                                    Menor Preço
                                </option>
                            </select>
                            <button >Filtar</button>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section class="products-main">
            <div class="products-list-title">
                <h1>Cremes</h1>
            </div>
            <ul class="products-list">
                    <li class="products-item">
                        <a href="">
                            <img src="../itens-images/caixa-acai.jpg" alt="10 Liters Açaí Box Image">
                            <hr>
                            <div>
                                <h2>Caixa de Açaí - 10 litros</h2>
                                <p><?php prodPrice("acai10")?></p>
                                <p><small>Preço Atualizado em:</small></p>
                            </div>
                        </a>
                    </li>

                    <li class="products-item">
                        <a href="">
                            <img src="../itens-images/caixa-acai.jpg" alt="5 Liters Açaí Box Image">
                            <hr>
                            <div>
                                <h2>Caixa de Açaí - 5 litros</h2>
                                <p><?php prodPrice("acai5")?></p>
                                <p><small>Preço Atualizado em:</small></p>
                            </div>
                        </a>
                    </li>

                    <li class="products-item">
                        <a href="">
                            <img src="../itens-images/caixa-acai.jpg" alt="1 Liters Açaí Box Image">
                            <hr>
                            <div>
                                <h2>Caixa de Açaí - 1 litro</h2>
                                <p><?php prodPrice("acai1")?></p>
                                <p><small>Preço Atualizado em:</small></p>
                            </div>
                        </a>
                    </li>
                
                    <li class="products-item">
                        <a href="">
                            <img src="../itens-images/cremes-frutados.png" alt="Cupuaçu Box Image">
                            <hr>
                            <div>
                                <h2>Cremes Frutados - 10 litros</h2>
                                <p><span>A Partir de: </span><?php prodPrice("cremeCupuacu10")?></p>
                                <p><small>Preço Atualizado em:</small></p>
                            </div>
                        </a>
                    </li>
                
                    <li class="products-item">
                        <a href="">
                        <img src="../itens-images/cremes-saborazzi.png" alt="Saborazzi's Cream Image">
                        <hr>
                        <div>
                            <h2>Cremes Saborazzi&copy; - Balde 5 kg</h2>
                            <p><span>A Partir de: </span><?php prodPrice("cremeSaborazzi")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                        </a>
                    </li>

                    <li class="products-item">
                        <a href="">
                            <img src="../itens-images/caixa-acai.jpg" alt="Açaí Zero Sugar Box Image">
                            <hr>
                            <div>
                                <h2>Açaí Zero - 10 litros</h2>
                                <p><?php prodPrice("acaiZero10")?></p>
                                <p><small>Preço Atualizado em:</small></p>
                            </div>
                        </a>
                    </li>
                    
                    <li class="products-item">
                        <a href="">
                            <img src="../itens-images/caixa-acai.jpg" alt="Açaí with Ninho Box Image">
                            <hr>
                            <div>
                                <h2>Açaí c/Ninho - 1 litro</h2>
                                <p><?php prodPrice("acaiNinho1")?></p>
                                <p><small>Preço Atualizado em:</small></p>
                            </div>
                        </a>
                    </li>

                    <li class="products-item">
                        <a href="">
                            <img src="../itens-images/caixa-acai.jpg" alt="Açaí with Ninho Box Image">
                            <hr>
                            <div>
                                <h2>Açaí c/Ninho - 250 ml</h2>
                                <p><?php prodPrice("acaiNinho250")?></p>
                                <p><small>Preço Atualizado em:</small></p>
                            </div>
                        </a>
                    </li>
            </ul>

            <div class="products-list-title">
                <h1>Adicionais</h1>
            </div>

            <ul class="products-list">
                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/granola.png" alt="Granola Package Image">
                        <hr>
                        <div>
                            <h2>Granola Tia Sônia<sup>&copy;</sup> - 1,5 kg</h2>
                            <p><span>A Partir de: </span><?php prodPrice("granola1.5")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>

                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/granola.png" alt="Granola Package Image">
                        <hr>
                        <div>
                            <h2>Granola Genérica - 1 kg</h2>
                            <p><?php prodPrice("granola1")?></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item">
                    <a href="">
                    <img src="../itens-images/strawberry.jpeg" alt="Strawberry Package Image">
                    <hr>
                    <div>
                        <h2>Morango Congelado - 1 kg</h2>
                        <p><?php prodPrice("morango1")?></p>
                        <p><small>Preço Atualizado em:</small></p>
                    </div>
                    </a>
                </li>
                
                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/leite em po.png" alt="Powdered Milk Package Image">
                        <hr>
                        <div>
                            <h2>Leite Em Pó - 1 kg</h2>
                            <p><?php prodPrice("leiteEmPo1")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item">
                    <a href="">
                    <img src="../itens-images/pacoca.png" alt="Paçoca Package Image">
                    <hr>
                    <div>
                        <h2>Caixa de Paçoca - 150 Unidades</h2>
                        <p><?php prodPrice("pacoca150")?></p>
                        <p><small>Preço Atualizado em:</small></p>
                    </div>
                    </a>
                </li>
              
                <li class="products-item">
                        <a href="">
                        <img src="../itens-images/pacoca.png" alt="Powdered Paçoca Package Image">
                        <hr>
                        <div>
                            <h2>Farofa de Paçoca - 1 kg</h2>
                            <p><?php prodPrice("farofaPacoca1")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>

                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/pacoca.png" alt="Crushed Peanut Package Image">
                        <hr>
                        <div>
                            <h2>Amendoim Triturado - 1 kg</h2>
                            <p><?php prodPrice("amendoimTriturado1")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>

                
                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/gotas.jpeg" alt="Chocolate Chips Package Image">
                        <hr>
                        <div>
                            <h2>Gotas de Chocolate - 1 kg</h2>
                            <p><?php prodPrice("gotaChocolate1")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/gotas.jpeg" alt="Chocoball Package Image">
                        <hr>
                        <div>
                            <h2>ChocoBall - 1 kg</h2>
                            <p><?php prodPrice("chocoball1")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>
               
                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/gotas.jpeg" alt="Jujuba Package Image">
                        <hr>
                        <div>
                            <h2>Jujuba - 500 g</h2>
                            <p><?php prodPrice("jujuba500")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/gotas.jpeg" alt="Disqueti Package Image">
                        <hr>
                        <div>
                            <h2>Disquete - 1 kg</h2>
                            <p><?php prodPrice("disquete1")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>
            </ul>

            <div class="products-list-title">
                <h1>Outros</h1>
            </div>

            <ul class="products-list">
                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/caixa-colher.png" alt="Spoon Box Image">
                        <hr>
                        <div>
                            <h2>Colher Reforçada P/Açaí - 500 Unidades</h2>
                            <p><?php prodPrice("colher500")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/caixa-colher.png" alt="Spoon Box Image">
                        <hr>
                        <div>
                            <h2>Colher Longa P/Açaí - 200 Unidades</h2>
                            <p><?php prodPrice("colher200")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>

                <li class="products-item">
                    <a href="">
                        <img src="../itens-images/caixa-colher.png" alt="Spoon Box Image">
                        <hr>
                        <div>
                            <h2>Colher P/Sorvete - 800 Unidades</h2>
                            <p><?php prodPrice("colher800")?></p>
                            <p><small>Preço Atualizado em:</small></p>
                        </div>
                    </a>
                </li>
            </ul>
        </section>
    </main>

    <footer>
        <ul>
            <li>
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Endereço: 
                </strong>
                <a href="#">
                    <p>Endereço Google Maps</p>
                </a>
            </li>

            <li>
                <strong>
                    <img src="../general-images/instagram-icon.png" alt="instagram logo">
                    Instagram: 
                </strong>
                <a href="#" target="_blank">
                    <p>@Instagram</p>
                </a>
            </li>

            <li>
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    Telefone: 
                </strong>
                <a href="tel:31957401232" target="_blank">
                    <p>Telefone Aqui</p>
                </a>
            </li>

            <li>
                <strong>
                    <img src="../general-images/whatsapp-icon.png" alt="whatsapp logo">
                    WhatsApp: 
                </strong>
                <a href="#" target="_blank">
                    <p>WhatsApp Aqui</p>
                </a>
            </li>

            <li style="color: rgb(202, 161, 235); opacity: 0.8;">
                2025 &copy; Açaí Amazônia Ipatinga. <br> Todos os direitos reservados
            </li>
            <li>
                <strong>
                    <img src="../general-images/github-icon.png" alt="GitHub icon">
                    Desenvolvido Por:
                </strong>
                <a href="github.com/luizgustavo1001" target="_blank">
                    <p>Luiz Gustavo</p>
                </a>
            </li>
        </ul>
    </footer>

</body>
</html>