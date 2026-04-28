<?php 
require_once __DIR__ . '/../mainController.php';
require_once __DIR__ . '/../../service/mailService.php';

if(isset($_SESSION["passwordToken"])){ 
    unset($_SESSION["passwordToken"]); 
}
if (isset($_SESSION["isAdmin"])) { 
    redirectWithMessage("adminNotAllowed", "../manager/admin.php", 0); 
}

use Services\MailService;

$mailService = new MailService();

$token = bin2hex(random_bytes(3));

$mailService->send(
    'cliente@email.com',
    'Token de Recuperação de Senha',
    '
    <!DOCTYPE html>
        <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="pt-BR">

        <head>
            <title></title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--[if mso]>
        <xml><w:WordDocument xmlns:w="urn:schemas-microsoft-com:office:word"><w:DontUseAdvancedTypographyReadingMail/></w:WordDocument>
        <o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml>
        <![endif]-->
            <style>
                * {
                    box-sizing: border-box;
                }

                body {
                    margin: 0;
                    padding: 0;
                }

                a[x-apple-data-detectors] {
                    color: inherit !important;
                    text-decoration: inherit !important;
                }

                #MessageViewBody a {
                    color: inherit;
                    text-decoration: none;
                }

                p {
                    line-height: inherit
                }

                .desktop_hide,
                .desktop_hide table {
                    mso-hide: all;
                    display: none;
                    max-height: 0px;
                    overflow: hidden;
                }

                .image_block img+div {
                    display: none;
                }

                sup,
                sub {
                    font-size: 75%;
                    line-height: 0;
                }

                @media (max-width:620px) {
                    .mobile_hide {
                        display: none;
                    }

                    .row-content {
                        width: 100% !important;
                    }

                    .stack .column {
                        width: 100%;
                        display: block;
                    }

                    .mobile_hide {
                        min-height: 0;
                        max-height: 0;
                        max-width: 0;
                        overflow: hidden;
                        font-size: 0px;
                    }

                    .desktop_hide,
                    .desktop_hide table {
                        display: table !important;
                        max-height: none !important;
                    }
                }
            </style><!--[if mso ]><style>sup, sub { font-size: 100% !important; } sup { mso-text-raise:10% } sub { mso-text-raise:-10% }</style> <![endif]-->
        </head>

        <body class="body" style="background-color: #ffffff; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
            <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;">
                <tbody>
                    <tr>
                        <td>
                            <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 600px; margin: 0 auto;" width="600">
                                                <tbody>
                                                    <tr>
                                                        <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;">
                                                            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                <tr>
                                                                    <td class="col-pad" style="padding-bottom:5px;padding-top:5px;">
                                                                        <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <tr>
                                                                                <td class="pad" style="width:100%;">
                                                                                    <div class="alignment" align="center">
                                                                                        <div style="max-width: 600px;"><a href="https://www.google.com" target="_blank"><img src="https://media.beefree.cloud/pub/bfra/qxh982xj/g6l/t10/rai/Frame%20619.png" style="display: block; height: auto; border: 0; width: 100%;" width="600" alt="Açaí e Polpas Amazônia Logo" title="Açaí e Polpas Amazônia Logo" height="auto"></a></div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="heading_block block-2" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <tr>
                                                                                <td class="pad">
                                                                                    <h1 style="margin: 0; color: #583f48; direction: ltr; font-family: Arial, Helvetica, sans-serif; font-size: 38px; font-weight: 700; letter-spacing: normal; line-height: 1.2; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 46px;"><strong><span class="tinyMce-placeholder" style="word-break: break-word;">Olá, '. $_SESSION["sendMail"] .'</span></strong></h1>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="paragraph_block block-3" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                            <tr>
                                                                                <td class="pad">
                                                                                    <div style="color:#583f48;direction:ltr;font-family:Arial, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:1.2;text-align:left;mso-line-height-alt:19px;">
                                                                                        <p style="margin: 0;">Segue seu&nbsp;<strong>token&nbsp;</strong>de verificação de e-mail:</p>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="heading_block block-4" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <tr>
                                                                                <td class="pad">
                                                                                    <h1 style="margin: 0; color: #89475f; direction: ltr; font-family: Arial, Helvetica, sans-serif; font-size: 38px; font-weight: 700; letter-spacing: normal; line-height: 1.2; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 46px;"><span class="tinyMce-placeholder" style="word-break: break-word;">'. $token .'</span></h1>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="paragraph_block block-5" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                            <tr>
                                                                                <td class="pad">
                                                                                    <div style="color:#583f48;direction:ltr;font-family:Arial, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:1.2;text-align:center;mso-line-height-alt:19px;">
                                                                                        <p style="margin: 0;">Este token é válido apenas enquanto a <strong>página web estiver aberta</strong>. Após isso será necessário solicitá-lo novamente.</p>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="paragraph_block block-6" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                            <tr>
                                                                                <td class="pad">
                                                                                    <div style="color:#583f48;direction:ltr;font-family:Arial, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:1.2;text-align:center;mso-line-height-alt:19px;">
                                                                                        <p style="margin: 0;">Atenciosamente,&nbsp;<strong>Equipe Açaí e Polpas Amazônia.</strong></p>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                        <table class="paragraph_block block-7" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                            <tr>
                                                                                <td class="pad">
                                                                                    <div style="color:#a17f8c;direction:ltr;font-family:Arial, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:1.2;text-align:center;mso-line-height-alt:19px;">
                                                                                        <p style="margin: 0;">Este é um e-mail automático. Não responda.</p>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table><!-- End -->
        </body>

        </html>
    '
);

$_SESSION["passwordToken"]  = $token;
session_write_close();
header("location: rescuePassword.php"); 
exit();
