<?php
    require_once __DIR__ . '/../../databaseConnection.php';
    require_once "../footerHeader.php";
    require_once "managerPHP.php";
    require_once "../printStyles.php";

    $amount = getAmountItem("admin");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("admin") ?>">

    <?php displayFavicon()?>
    
    <title>Açaí e Polpas Amazônia | Administradores</title>
</head>

<body>
    <div class="dazzles-bg fade-in mobile"></div>

    <aside class="manager-aside">
        <?php echo getIcon("brand-outer-m")?>
        
        <nav class="aside-hero">
            <?php echo getIcon("brand-outer-d")?>
            <a href="" class="nav-link selected">
                <?php echo getIcon("iconBg"); ?>
                <span class="label">
                    <p>Admin</p>
                    <?php echo getIcon("key2Filled")?>
                </span>
                
            </a>
            <a href="" class="nav-link">
                <?php echo getIcon("iconBg"); ?>
                <span class="label">
                    <p>Clientes</p>
                    <?php echo getIcon("userGroupEmpty")?>
                </span>
            </a>
            <a href="" class="nav-link">
                <?php echo getIcon("iconBg"); ?>
                <span class="label">
                    <p>Produtos</p>
                    <?php echo getIcon("priceEmpty")?>
                </span>
            </a>
            <a href="" class="nav-link">
                <?php echo getIcon("iconBg"); ?>
                <span class="label">
                    <p>Pedidos</p>
                    <?php echo getIcon("billEmpty")?>
                </span>
            </a>
            <a href="" class="nav-link">
                <?php echo getIcon("iconBg"); ?>
                <span class="label">
                    <p>Mudanças</p>
                    <?php echo getIcon("dbEmpty")?>
                </span>
            </a>

            <button class="theme-btn icon-button toggle-theme">
                <div class='icon-bg active'><?php echo getIcon("moon")?></div>
                <div class='icon-bg'><?php echo getIcon("sun") ?></div>
            </button>
        </nav>

        <button class='icon-button mobile menu'>
            <?php echo getIcon("menu")?>
        </button>
    </aside>

    <main>
        <div class="hero-title">
            <h1>Administradores</h1>
            <details class="user-info">
                <summary>
                    <?php echo getIcon("userOuter")?>
                    <p>Administrador</p>
                    <?php getIcon("chevron-down")?>
                </summary>

                <span class="user-options">
                    <a href="">
                        <?php echo getIcon("cog")?>
                        <p>Configurações</p>
                    </a>

                    <a href="">
                        <?php echo getIcon("logout")?>
                        <p>Sair</p>
                    </a>
                </span>
            </details>
        </div>
    </main>

    <!--
    <?php displayManagerNav("admin")?>

    <main>
        <?php
        /*
            if(isset($_GET["adminNotAllowed"]))
                displayPopUp("adminNotAllowed", "");
            else if(isset($_GET["makeAdmin"]))
                displayPopUp("makeAdmin", "");
            */
        ?>
        
        <?php //displayPageNav("admin")?>

        <section class="info-section">
            <ul>
                <li class="amount">
                    <p><span><?php //echo $amount?></span></p>
                    <p>Total de Administradores</p>
                </li>
            </ul>
        </section>

        <form method="GET" class="search-section">
            <label for="isearch">Pesquisar pelo <strong>nome</strong> ou <strong>código</strong></label>
            <div class="search-bar">
                <label for="isearch" style="display: flex; align-items: center; cursor: pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
                    </svg>
                </label>
                <input type="text" name='query' id="isearch" class="alt-input">
            </div>
        </form>


        <?php 
        /*
            if(isset($_GET["query"])){
                searchColumns($_GET["query"], "admin");
            }
            
            displayTable("admin");
             */
        ?>
       
    </main>

-->

<script src="/js/general.js"></script>
<script src="/js/manager.js"></script>

</body>
</html>