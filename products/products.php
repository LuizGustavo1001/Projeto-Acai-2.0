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
            "ovomaltine1", "gotaChocolate1", "chocoball1", "jujuba500", "disquete1", 
            "cremeSaborazzi", "polpas"
        ]; 

        if(in_array($nameProd, $allowedNames)){ // verificar se o nome para pesquisa é um dos produtos cadastrados
            $query = $mysqli->prepare("SELECT price,priceDate FROM product WHERE nameProd = ?");
            $query->bind_param("s",$nameProd);
            $query->execute();
            $result = $query->get_result();
            
            $defaultMoney = numfmt_create("pt-BR", style: NumberFormatter::CURRENCY);
            
            $result = $result->fetch_assoc();
            $price = $result['price'];

            echo numfmt_format_currency($defaultMoney, $price, "BRL");

            $date = $result['priceDate'];
            
            echo "<p><small>Preço Atualizado em:<strong> $date</strong></small></p>";

        }else{
            echo "<em><small>Produto não encontrado</small></em>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../styles/general-styles.css">
    <link rel="stylesheet" href="../styles/products.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080377/iconeAcai_mj7dqy.ico" type="image/x-icon">

    <title>Açaí Amazônia - Produtos</title>
    
</head>
<body>
    
    <header>
        <ul>
            <li class="acai-icon">
                <a href="../index.php">
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079683/acai-icon-white_fll4gt.png" class="item-translate" alt="Açaí Icon">
                </a>
                <p>Açaí Amazônia Ipatinga</p>
            </li>
        </ul>
        <ul class="right-header">
            <li>
                <a href="../account/account.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <p>Sua Conta</p>
                </a>
            </li>
            <li>
                <a href="products.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>
                    <p>Produtos</p>
                </a>
            </li>
            <li>
                 <a href="../cart/cart.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <p>Carrinho</p>
                    <p class="numberItens">N</p>
                 </a>
            </li>
        </ul>

    </header>

    <main>

        <section class="header-feature">
            <img src="../general-images/feature-alt-5.png" alt="">
        </section>
        <section class="products-header">
            <div class="products-header-title">
                <h1>Nossos Produtos</h1>
                <p>Adicione Produtos ao carrinho para realizar sua compra</p>
                <p>*Preços podem ser modificados com o tempo</p>
            </div>
            
            <div class="products-search-div">
                <form method="get" class="products-search-item">
                    <div class="products-search-label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <label for="inameProd">Pesquisar pelo Nome</label>
                    </div>
                    <div class="search-input generic-button">
                        <input type="text" name="nameProd" id="inameProd" placeholder="Nome do Produto">
                        <button>Pesquisar</button>
                    </div>              
                </form>

                <form method="get" class="products-search-item">
                    <div  class="products-search-label">
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
                        <button>Filtar</button>
                    </div>
                </form>

            </div>
        </section>

        <section class="products-main">
            <div class="products-list-title">
                <h1>Cremes</h1>
            </div>

            <ul class="products-list">
                    <li class="products-item item-translate-alt">
                        <a href="product-item/caixaAcai10.php">
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="10 Liters Açaí Box Image">
                            <hr>
                            <div>
                                <h2>Caixa de Açaí - 10 litros</h2>
                                <p><?php prodPrice("acai10")?></p>
                            </div>
                        </a>
                    </li>

                    <li class="products-item item-translate-alt">
                        <a href="">
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="5 Liters Açaí Box Image">
                            <hr>
                            <div>
                                <h2>Caixa de Açaí - 5 litros</h2>
                                <p><?php prodPrice("acai5")?></p>
                            </div>
                        </a>
                    </li>

                    <li class="products-item item-translate-alt">
                        <a href="">
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="1 Liters Açaí Box Image">
                            <hr>
                            <div>
                                <h2>Caixa de Açaí - 1 litro</h2>
                                <p><?php prodPrice("acai1")?></p>
                            </div>
                        </a>
                    </li>
                
                    <li class="products-item item-translate-alt">
                        <a href="">
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079855/cremes-frutados_kfdx1f.png" alt="Cupuaçu/ Other Creams Box Image">
                            <hr>
                            <div>
                                <h2>Cremes Frutados - 10 litros</h2>
                                <p><em>A Partir de</em>: <?php prodPrice("cremeCupuacu10")?></p>
                            </div>
                        </a>
                    </li>
                
                    <li class="products-item item-translate-alt">
                        <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="Saborazzi's Cream Image">
                        <hr>
                        <div>
                            <h2>Cremes Saborazzi&copy; - Balde 5 kg</h2>
                            <p><span>A Partir de: </span><?php prodPrice("cremeSaborazzi")?></p>
                        </div>
                        </a>
                    </li>

                    <li class="products-item item-translate-alt">
                        <a href="">
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="Açaí Zero Sugar Box Image">
                            <hr>
                            <div>
                                <h2>Açaí Zero - 10 litros</h2>
                                <p><?php prodPrice("acaiZero10")?></p>
                            </div>
                        </a>
                    </li>
                    
                    <li class="products-item item-translate-alt">
                        <a href="">
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="Açaí with Ninho Box Image">
                            <hr>
                            <div>
                                <h2>Açaí c/Ninho - 1 litro</h2>
                                <p><?php prodPrice("acaiNinho1")?></p>
                            </div>
                        </a>
                    </li>

                    <li class="products-item item-translate-alt">
                        <a href="">
                            <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079853/caixa-acai_l7uokc.jpg" alt="Açaí with Ninho Box Image">
                            <hr>
                            <div>
                                <h2>Açaí c/Ninho - 250 ml</h2>
                                <p><?php prodPrice("acaiNinho250")?></p>
                            </div>
                        </a>
                    </li>
            </ul>

            <div class="products-list-title">
                <h1>Adicionais</h1>
            </div>

            <ul class="products-list">
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079859/granola_majjmg.png" alt="Granola Package Image">
                        <hr>
                        <div>
                            <h2>Granola Tia Sônia<sup>&copy;</sup> - 1,5 kg</h2>
                            <p><span>A Partir de: </span><?php prodPrice("granola1.5")?></p>
                        </div>
                    </a>
                </li>

                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079859/granola_majjmg.png" alt="Granola Package Image">
                        <hr>
                        <div>
                            <h2>Granola Genérica - 1 kg</h2>
                            <p><?php prodPrice("granola1")?></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item item-translate-alt">
                    <a href="">
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080166/strawberry_akhbkp.jpg" alt="Strawberry Package Image">
                    <hr>
                    <div>
                        <h2>Morango Congelado - 1 kg</h2>
                        <p><?php prodPrice("morango1")?></p>
                    </div>
                    </a>
                </li>
                
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079860/leite_em_po_rkrf0f.png" alt="Powdered Milk Package Image">
                        <hr>
                        <div>
                            <h2>Leite Em Pó - 1 kg</h2>
                            <p><?php prodPrice("leiteEmPo1")?></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item item-translate-alt">
                    <a href="">
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079844/pacoca_dlxek6.png" alt="Paçoca Package Image">
                    <hr>
                    <div>
                        <h2>Caixa de Paçoca - 150 Unidades</h2>
                        <p><?php prodPrice("pacoca150")?></p>
                    </div>
                    </a>
                </li>
              
                <li class="products-item item-translate-alt">
                        <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079844/pacoca_dlxek6.png" alt="Powdered Paçoca Package Image">
                        <hr>
                        <div>
                            <h2>Farofa de Paçoca - 1 kg</h2>
                            <p><?php prodPrice("farofaPacoca1")?></p>
                        </div>
                    </a>
                </li>

                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079844/pacoca_dlxek6.png" alt="Crushed Peanut Package Image">
                        <hr>
                        <div>
                            <h2>Amendoim Triturado - 1 kg</h2>
                            <p><?php prodPrice("amendoimTriturado1")?></p>
                        </div>
                    </a>
                </li>

                
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079858/gotas_wanvya.jpg    " alt="Chocolate Chips Package Image">
                        <hr>
                        <div>
                            <h2>Gotas de Chocolate - 1 kg</h2>
                            <p><?php prodPrice("gotaChocolate1")?></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079858/gotas_wanvya.jpg" alt="Chocoball Package Image">
                        <hr>
                        <div>
                            <h2>ChocoBall - 1 kg</h2>
                            <p><?php prodPrice("chocoball1")?></p>
                        </div>
                    </a>
                </li>
               
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079858/gotas_wanvya.jpg" alt="Jujuba Package Image">
                        <hr>
                        <div>
                            <h2>Jujuba - 500 g</h2>
                            <p><?php prodPrice("jujuba500")?></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079858/gotas_wanvya.jpg" alt="Disqueti Package Image">
                        <hr>
                        <div>
                            <h2>Disquete - 1 kg</h2>
                            <p><?php prodPrice("disquete1")?></p>
                        </div>
                    </a>
                </li>
            </ul>

            <div class="products-list-title">
                <h1>Outros</h1>
            </div>

            <ul class="products-list">
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png" alt="Spoon Box Image">
                        <hr>
                        <div>
                            <h2>Colher Reforçada P/Açaí - 500 Unidades</h2>
                            <p><?php prodPrice("colher500")?></p>
                        </div>
                    </a>
                </li>
                
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png" alt="Spoon Box Image">
                        <hr>
                        <div>
                            <h2>Colher Longa P/Açaí - 200 Unidades</h2>
                            <p><?php prodPrice("colher200")?></p>
                        </div>
                    </a>
                </li>

                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079854/caixa-colher_eurc6f.png" alt="Spoon Box Image">
                        <hr>
                        <div>
                            <h2>Colher P/Sorvete - 800 Unidades</h2>
                            <p><?php prodPrice("colher800")?></p>
                        </div>
                    </a>
                </li>
                <li class="products-item item-translate-alt">
                    <a href="">
                        <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079845/polpas_lnxxhz.jpg" alt="">
                        <hr>
                        <div>
                            <h2>Polpas de Frutas Sabor Natural&copy;</h2>
                            <p><?php prodPrice("polpas")?></p>
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
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079684/instagram-icon_wjguqu.png" alt="instagram logo">
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
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079686/whatsapp-icon_ketibs.png" alt="whatsapp logo">
                    WhatsApp: 
                </strong>
                <a href="#" target="_blank">
                    <p>WhatsApp Aqui</p>
                </a>
            </li>

            <li style="color: rgb(238, 224, 250); opacity: 0.8;">
                2025 &copy; Açaí Amazônia Ipatinga. <br> Todos os direitos reservados
            </li>
            <li>
                <strong>
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079684/github-icon_xab4gh.png" alt="">
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