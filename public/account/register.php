<?php require_once __DIR__ . '/../../src/controller/account/registerController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">
    
    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Registrar</title>
</head>

<body>
    <div class="dazzles-bg mobile"></div>

    <main class="rise-above">
        <div class="back-button" onclick="window.location.href = 'login.php'">
            <?php echo getIcon("back")?>
            <span>Voltar</span>
        </div>

        <div class="hero">
            <div class="icon-wrapper">
                <?php echo getIcon("iconBg")?>
                <?php echo getIcon("newUser")?>
            </div>

            <div class="title">
                <h1>Área de Registro</h1>
                <p><strong>Registre-se</strong> para <strong>começar a comprar</strong> em nosso site.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="iname">Nome *</label>
                    <input type="text" name="name" id="iname" maxlength="30" minlength="8"
                                placeholder="Nome Completo" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="regular-input-box">
                    <label for="iemail">Endereço de Email *</label>
                    <input type="email" name="email" id="iemail" maxlength="50"
                                placeholder="email@exemplo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="regular-input-box">
                    <label for="inumber">Telefone de Contato *</label>
                    <input type="text" name="phone" id="inumber" minlength="15" maxlength="16"
                        pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="(XX) 9 8888 8888" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                </div>

                <div class="wrap">
                    <div class="regular-input-box">
                        <label for="istreet">Rua *</label>
                        <input type="text" name="street" id="istreet" maxlength="50"
                            placeholder="Nome da Rua Aqui" value="<?= htmlspecialchars($_POST['street'] ?? '') ?>" required>
                    </div>
                    <div class="regular-input-box">
                        <label for="ihouseNum">Número *</label>
                        <input type="number" name="houseNum" id="ihouseNum" max="99999999"
                            placeholder="Número  da Residência Aqui" value="<?= htmlspecialchars($_POST['houseNum'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="idistrict">Bairro *</label>
                    <input type="text" name="district" id="idistrict" maxlength="40"
                        placeholder="Nome do Bairro Aqui" value="<?= htmlspecialchars($_POST['district'] ?? '') ?>" required>
                </div>

                <div class="wrap">
                    <div class="regular-input-box">
                        <label for="icity">Cidade *</label>
                        <input type="text" name="city" id="icity" maxlength="40"
                            placeholder="Nome da Cidade Aqui" value="<?= htmlspecialchars($_POST['city'] ?? '') ?>" required>
                    </div>
                    <div class="regular-input-box">
                        <label for="istate">Estado: </label>
                        <select name="state" id="istate"> <?php displayStateOptions() ?> </select>
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="ireference">Ponto de Referência:</label>
                    <input type="text" name="reference" id="ireference" maxlength="50"
                        value="<?= htmlspecialchars($_POST['reference'] ?? '') ?>">
                </div>

                <div class="regular-input-box">
                    <label for="ipassword">Senha *</label>
                    <input type="password" name="password" id="ipassword" placeholder="********" required maxlength="30">
                </div>

                <button class="regular-btn">Registrar-se</button>
            </form>
        </div>
    </main>

    <script src="/js/general.js"></script>
</body>
</html>