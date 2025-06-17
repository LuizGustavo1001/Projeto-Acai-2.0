<?php 
    include "../../databaseConnection.php";
    include "../prodPrice.php";
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../styles/general-styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&display=swap" rel="stylesheet"> 

    <link rel="shortcut icon" href="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750080377/iconeAcai_mj7dqy.ico" type="image/x-icon">

    <title>Açaí Amazônia - Produtos</title>

    <style>
        main{
            padding-inline: 1.5em;

            display: flex;
            flex-direction: column;
            gap: 2em;

        }

        .product-img, .product-text, .product-forms{
            background: white;

        }

        .product-img{
            padding-top: 1em;
            padding-inline: 1em;

            display: flex;
            justify-content: center;

            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);

        }

        .product-img img{
            width: 350px;
            border-radius: var(--border-radius);

        }

        .product-text{
            padding: 1em;
            padding-bottom: 2em;
            border-bottom-left-radius: var(--border-radius);
            border-bottom-right-radius: var(--border-radius);
            text-align: center;
            
        }

        .product-text h1{
            font-size: 2.5em;
            margin-bottom: 0.5em;
        }

        .price{
            font-weight: bold;
            color: var(--secondary-clr);
            font-size: 1.5em;
             margin-bottom: 0.5em;
        }

        .product-forms{
            margin-top: 2em;
            padding: 2em;

            display: flex;
            flex-direction: column;
            gap: 2em;

            border-radius: var(--border-radius);

        }

        .product-forms button{
            justify-content: center;

        }

        .product-forms svg{
            width: 25px;
            cursor: pointer;
        }

        .forms-item{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .forms-item label{
            font-weight: bold;
            font-size: 1.2em;
        }

        .forms-item select, .forms-item input{
            border-radius: var(--border-radius);
            color: var(--fourth-clr);
            border: 2px solid var(--primary-clr);

            font-weight: bold;
            padding-inline: 0.3em;

        }

        .forms-item select:focus, .forms-item input:focus{
            border-color: var(--secondary-clr);
            outline: none;

        }

        .product-size select, .product-flavor select{
            width: 210px;
            height: 45px;

        }

        .product-amount input{
            width: 200px;
            height: 40px;

        }

        .back-button{
            display: flex;
            align-items: center;
            gap: 0.3em;

            margin-top: 2em;

            font-size: 1.2em;

            color: var(--secondary-clr);

            width: 20%;

            transition: var(--transition);

        }

        .back-button:hover{
            color: var(--secondary-clr);
            text-decoration: underline;

            text-underline-offset: 0.1em;

        }

        .back-button svg{
            width: 30px;
        }

        @media(min-width: 1024px){
            main{
                margin-bottom: 6em;
            }

            .back-button{
                width: 6%;

            }

             .product-img{
                padding: 2em;
                border-radius: var(--border-radius);

             }

            .product-img img{
                width: 500px;
                

            }

            .product-hero{
                display: flex;
                align-items: center;
                justify-content: center;

                gap: 4em;   
                
            }

            .product-text{
                background: white;
                border-radius: 0px;

                border-top-right-radius: var(--border-radius);
                border-top-left-radius: var(--border-radius);
                padding-bottom: 3em;
                
            }

            .product-text small{
                border-bottom: 2px solid var(--primary-clr);
                padding-bottom: 2em;
                
            }

            .product-text h1{
                font-size: 3em;

            }
            
            .price{
                font-size: 2em;

            }

            .product-forms{
                margin-top: 0em;

                border-radius: 0px;
                border-bottom-left-radius: var(--border-radius);
                border-bottom-right-radius: var(--border-radius);

                width: 30vw;
                height: 270px;

            }

        }
    </style>

</head>
<body>

    <header>
        <ul>
            <li class="acai-icon">
                <a href="../../index.php">
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079683/acai-icon-white_fll4gt.png" class="item-translate" alt="Açaí Icon">
                </a>
                <p>Açaí Amazônia Ipatinga</p>
            </li>
        </ul>
        <ul class="right-header">
            <li>
                <a href="../../account/account.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <p>Sua Conta</p>
                </a>
            </li>
            <li>
                <a href="../products.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>
                    <p>Produtos</p>
                </a>
            </li>
            <li>
                 <a href="../../cart/cart.php">
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
        <div>
            <a href="../products.php" class="back-button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Voltar
            </a>
        </div>
        
        <section class="product-hero">
            <div class="product-img">
                <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1750079860/leite_em_po_rkrf0f.png" alt="Product Image">
            </div>
            <div>
                <div class="product-text">
                    <h1>Leite em Pó<br> 1 kg</h1>
                    <p><?php prodPrice("leiteEmPo1")?></p>
                </div>
                <form method="get" class="product-forms">
                    
                    <div class="forms-item product-amount">
                        <label for="iamount-product">Quantidade: </label>
                        <input type="number" name="amount-product" id="iamount-product" value="1" max="15" min="1">
                    </div>

                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        Adicionar Ao Carrinho
                    </button>
                        
                </form>
            </div>
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