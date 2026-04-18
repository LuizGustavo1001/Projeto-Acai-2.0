<?php require_once __DIR__ . '/../../../src/controller/account/userChanges/newPasswordController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Alterar Senha</title>
</head>

<body>
    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = '/account/account.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-wrapper">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("key")?>
            </div>

            <div class="title">
                <h1>Alterar Senha</h1>
                <p>Insira a <strong>senha vinculado</strong> a sua conta e a <strong>nova senha</strong> para realizar a alteração.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="ipassword">Senha anterior: </label>
                    <input type="password" name="password" id="ipassword" maxlength="30" placeholder="• • • • • • • • • •" required>
                </div>
                <div class="regular-input-box">
                    <label for="inewPassword">Nova senha: </label>
                    <input type="password" name="newPassword" id="inewPassword" maxlength="30" placeholder="• • • • • • • • • •" required>
                </div>

                <button class="regular-btn">Enviar</button>
            </form>
        </div>
    </main>

    <script src="/js/general.js"></script>
</body>
</html>