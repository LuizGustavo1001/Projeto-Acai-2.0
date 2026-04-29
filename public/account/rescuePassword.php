<?php require_once __DIR__ . '/../../src/controller/account/rescuePasswordController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>
    
    <title>Açaí e Polpas Amazônia | Recuperar Senha</title>
</head>

<body>
    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = '/index.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-wrapper">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("key")?>
            </div>

            <div class="title">
                <h1>Código de Verificação</h1>
                <p>Insira o <strong>token</strong> enviado para o seu email parar alterar sua senha.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="itoken">Token de Recuperação: </label>
                    <input type="text" name="token" id="itoken" maxlength="50" placeholder="Digite o Token de Recuperação Aqui" required>
                </div>

                <button class="regular-btn await">
                    <span>Enviar</span>
                    <div class="wheel spinning inactive"></div>
                </button>
            </form>
        </div>
    </main>

    <script src="/js/general.js"></script>
</body>
</html>