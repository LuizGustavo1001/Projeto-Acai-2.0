<?php require_once __DIR__ . '/../../src/controller/cart/cartController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("cart") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Carrinho</title>
</head>
<body>
    <div class="dazzles-bg fade-in mobile"></div>

    <?php displayHeader("cart")?>

    <main>
        <section class="title-hero rise-above" style="--time-outer: 0.5s">
            <h1>Carrinho</h1>
            <p>
                Caso algum de seus <strong>dados pessoais</strong> estejam incorretos, clique no botão <strong>Editar</strong>. <br>
                Clique em <strong>confirmar pedido</strong> para finalizar a compra.
            </p>
        </section>

        <section class="hero rise-above">
            <div class="left container">
                <div class="item costumer">
                    <div class="title"><h1>Informações do Cliente</h1></div>

                    <ul class="content">
                        <?php displayClientInfo(); ?>
                        <a href="../account/account.php" class="regular-btn">Editar dados pessoais</a>
                    </ul>
                </div>

                <div class="item overview">
                    <div class="title"><h1>Resumo do Pedido</h1></div>

                    <ul class="content">
                        <?php displayOrderOverview(); ?>
                        <a href="cart.php?orderConfirmed=1" class="regular-btn">Confirmar Pedido</a>
                    </ul>
                </div>
            </div>

            <div class="right container">
                <div class="item review">
                    <div class="title"><h1>Revisão dos Itens</h1></div>

                    <ul class="content">
                        <?php printProducts(); ?>
                    </ul>
                </div>
            </div>
        </section>
    </main>
    <?php displayFooter()?>

    <script src="/js/general.js"></script>
</body>
</html>