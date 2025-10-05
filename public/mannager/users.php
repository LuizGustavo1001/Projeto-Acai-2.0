<?php 
    include "../../databaseConnection.php";
    include "../footerHeader.php";
    include "mannagerPHP.php";
    include "../printStyles.php";

    $amount = getAmountItem(type: "client");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Leckerli+One&family=Lemon&display=swap" rel="stylesheet">

    <?php faviconOut()?>

    <script src="https://kit.fontawesome.com/71f5f3eeea.js" crossorigin="anonymous"></script>
    <script src="../JS/generalScripts.js"></script>

    <link rel="stylesheet" href="<?php printStyle("1", "mannager") ?>">

    <title>Açaí e Polpas Amazônia - Clientes</title>
</head>
<body>
    <header>
        <ul class="top-header">
            <li>
                <a href="../index.php" class="acai-icon">
                    <img src="https://res.cloudinary.com/dw2eqq9kk/image/upload/v1755358113/acai-icon_jsrexi_t30xv5.png" alt="Açaí Logo image" class="item-translate">
                </a>
            </li>
            <li>Açaí e Polpas Amazônia</li>
        </ul>

        <hr>

        <ul class="bottom-header">
            <li>
                <a href="admin.php" >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <p>Administradores</p>
                </a>
            </li>
            <li>
                <a href="users.php" class="selected">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <p>Usuários</p>
                </a>
            </li>
            <li>    
                <a href="orders.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <p>Pedidos</p>
                </a>
            </li>
            <li>
                <a href="products.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    <p>Produtos</p>
                </a>
            </li>
            <li>
                <a href="changes.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                    <p>Mudanças</p>
                </a>
            </li>
        </ul>
        <button class="center-button" onclick="window.location.href='../account/logout.php'">Sair</button>
    </header>

    <main>
        <?php 
            if(isset($_GET["adminNotAllowed"])){
                echo "
                    <section class= \"popup-box show\">
                        <div class=\"popup-div\">
                            <div><h1>Erro</h1></div>
                            <div>
                                <p>É preciso fazer <strong>Login como Cliente</strong> para acessar A Página Anterior</p>
                                <p>Clique no botão abaixo para fechar esta janela</p>
                                <button class=\"popup-button\">Fechar</button>
                            </div>
                        </div>
                    </section>
                ";
            }
            if(isset($_GET["makeClient"])){
                echo "
                    <section class= \"popup-box show\">
                        <div class=\"popup-div\">
                            <div><h1>Atualização</h1></div>
                            <div>
                                <p>Novo Cliente Adicionado com Sucesso</p>
                                <p>Clique no botão abaixo para fechar esta janela</p>
                                <button class=\"popup-button\">Fechar</button>
                            </div>
                        </div>
                    </section>
                ";
            }
            if(isset($_GET["removeS"])){
                 echo "
                    <section class= \"popup-box show\">
                        <div class=\"popup-div\">
                            <div><h1>Atualização</h1></div>
                            <div>
                                <p>Sucesso ao<strong> Remover um Cliente </strong>do Banco de Dados</p>
                                <p>Clique no botão abaixo para fechar esta janela</p>
                                <button class=\"popup-button\">Fechar</button>
                            </div>
                        </div>
                    </section>
                ";
            }
        ?>
        <div class="main-title">
            <div>
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    Gerenciar <strong>Usuários</strong>
                </h1>
                <p>Visualizar e alterar Usuários</p>
            </div>

            <div class="admin-data">
                <img src="<?php echo $_SESSION['adminPicture']; ?>" alt="Admin Picture">
                <p>
                    <?php echo $_SESSION["userName"]; ?>
                </p>
                <a href="settings.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                    </svg>
                </a>
            </div>
        </div>

        <ul class="main-center">
            <li class="item-amount">
                <p class="amountItem"><strong> <?php echo $amount?> </strong></p>
                <p>Clientes Cadastrados</p>
            </li>

            <li class="input-search">
                <form method="get">
                    <label for="iadminName">Pesquisar Clientes pelo Nome ou Email</label>
                    <input type="text" name="searchQuery" id="iadminName" placeholder="Pressiona Enter para Iniciar a Busca">
                </form>
            </li>
        </ul>

        <div class="main-bottom">
            <?php 
                if(isset($_GET["searchQuery"])){
                    echo "<h1>Clientes Encontrados com o Filtro <strong>\"{$_GET["searchQuery"]}\"</strong></h1>";
                }else{
                    echo "<h1>Todos os <strong>Clientes</strong></h1>";
                }
            ?>

            <div class="main-bottom-table">
                <table class="main-table">
                    <thead>
                        <tr>
                            <th class="smaller-td">Id</th>
                            <th class='normal-td'>Nome</th>
                            <th class='normal-td'>Email</th>
                            <th class='normal-td'>Telefone</th>
                            <th class='normal-td'>Endereço</th>
                            <th class="smaller-td"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-tuple">
                            <table class="row-table">
                                <?php 
                                    if(isset($_GET["searchQuery"])){
                                        searchColumns($_GET["searchQuery"], "user");

                                    }else{
                                        GetTableMannager("users");
                                    }
                                ?>
                            </table>
                        </tr>
                    </tbody>
                </table>
            </div>    
        </div>
    </main>
</body>
</html>