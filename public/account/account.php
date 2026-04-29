<?php require_once __DIR__ . '/../../src/controller/account/accountController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php printStyle("base") ?>">
    <link rel="stylesheet" href="<?php printStyle("main") ?>">
    <link rel="stylesheet" href="<?php printStyle("account") ?>">

    <?php displayFavicon()?>

    <title>Açaí e Polpas Amazônia | Minha Conta</title>
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
                <?php echo getIcon("userOuter")?>
            </div>

            <div class="title">
                <h1>Área do Usuário</h1>
                <p>Ao clicar em <strong>"editar"</strong> todos os campos preenchidos serão <strong>verificados</strong>.</p>
            </div>

            <form method="post" class="regular-form">
                <div class="regular-input-box">
                    <label for="iuserName">Nome: </label>
                    <div class="form-input">
                        <input type="text" name="nameUser" id="iuserName" maxlength="30" minlength="8" placeholder="<?php echo $_SESSION['nameUser']; ?>" >
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="iuserPhone">Telefone de Contato:</label>
                    <div class="form-input">
                        <input type="text" name="userPhone" id="iuserPhone" minlength="15" maxlength="16" pattern="\(\d{2}\) \d \d{4} \d{4}" placeholder="<?php echo $_SESSION['phoneUser']; ?>" >
                    </div>
                </div>

                <div class="wrap">
                    <div class="regular-input-box">
                        <label for="istreet">Rua: </label>
                        <div class="form-input">
                            <input type="text" name="a_street" id="istreet" maxlength="50" placeholder="<?php echo $_SESSION['a_street']; ?>" >
                        </div>
                    </div>
                    <div class="regular-input-box">
                        <label for="ilocalNum">Número: </label>
                        <div class="form-input">
                            <input type="number" name="a_numHouse" id="ilocalNum" max="99999999" placeholder="<?php echo $_SESSION['a_numHouse']; ?>">
                        </div>
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="iuserDistrict">Bairro: </label>
                    <div class="form-input">
                        <input type="text" name="a_district" id="iuserDistrict" maxlength="40" placeholder="<?php echo $_SESSION['a_district']; ?>" >
                    </div>
                </div>

                <div class="wrap">
                    <div class="regular-input-box">
                        <label for="iuserCity">Cidade: </label>
                        <div class="form-input">
                            <input type="text" name="a_city" id="iuserCity" maxlength="40" placeholder="<?php echo $_SESSION['a_city']; ?>">
                        </div>
                    </div>
                    <div class="regular-input-box">
                        <label for="istate">Estado</label>
                        <select name="a_state" id="istate"> <?php displayStateOptions() ?> </select>
                    </div>
                </div>

                <div class="regular-input-box">
                    <label for="ireferencePoint">Ponto de Referência: </label>
                    <div class="form-input">
                        <input type="text" name="a_referencePoint" id="ireferencePoint" maxlength="50" placeholder="<?php echo $_SESSION['a_referencePoint']; ?>">
                    </div>
                </div>
                <button class="regular-btn await">
                    <span>Editar</span>
                    <div class="wheel spinning inactive"></div>
                </button>
            </form>

            <div class="fot">
                <a href="?logout=1">Clique aqui para <strong>sair da sua conta</strong></a>
                <a href="changes/newPassword.php">Alterar Senha</a>
                <a href="changes/newEmail.php">Alterar Email</a>
            </div>
        </div>
    </main>

    <script src="/js/general.js"></script>
</body>
</html>