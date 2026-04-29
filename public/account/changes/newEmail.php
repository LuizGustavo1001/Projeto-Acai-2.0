<?php require_once __DIR__ . '/../../../src/controller/account/userChanges/newEmailController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Alterar Email</title>
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
                <?php echo getIcon("mail")?>
            </div>

            <div class="title">
                <h1>Alterar Email</h1>
                <p>Insira o <strong>endereço de email vinculado</strong> a sua conta e o <strong>novo email</strong> para realizar a alteração.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="iemail">Email anterior: </label>
                    <input type="email" name="email" id="iemail" maxlength="50" placeholder="exemplo@dominio.com" required>
                </div>

                <div class="regular-input-box">
                    <label for="inewEmail">Novo email: </label>
                    <input type="email" name="newEmail" id="inewEmail" maxlength="50" placeholder="exemplo@dominio.com" required>
                </div>

                <button class="regular-btn await">
                    <span>Alterar</span>
                    <div class="wheel spinning inactive"></div>
                </button>
            </form>
        </div>
    </main>
    
    <script src="/js/general.js"></script>
</body>
</html>