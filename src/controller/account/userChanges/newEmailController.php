<?php 
require_once __DIR__ . '/../../mainController.php';
require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../service/mailService.php';

// trying to access the page without autentication
if(!isset($_SESSION["mailUser"])){
    header("location: ../login.php");
    exit();
}

checkSessionStatus();

use Services\MailService;

$userModel = new User($mysqli);
$mailService = new MailService();

if(isset($_POST["email"], $_POST['newEmail'])){
    // verify input email
    $sanitizedEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    try{
        $emailExists = $userModel->verifyEmail($sanitizedEmail);

        if(!$emailExists){
            redirectWithMessage("wrongMail", "newEmail.php", 0);
        }

        // update email value at database
        $sanitizedNewEmail = filter_var($_POST['newEmail'], FILTER_SANITIZE_EMAIL);

        if($sanitizedEmail == $sanitizedNewEmail){
            redirectWithMessage("sameMail", "newEmail.php", 0);
        }

        // change at database
        $userModel->updateData("mailUser", $sanitizedNewEmail, $_SESSION['idUser']);

        $mailService->send(
            $_SESSION["mailUser"],
            'Bem-vindo ao Açaí e Polpas Amazônia',
            '
                <!DOCTYPE html>
                    <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

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

                    <body class="body" style="margin: 0; background-color: #ffffff; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
                        <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 8px; color: #000000; padding: 16px; width: 600px; margin: 0 auto;" width="600">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;">
                                                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                            <tr>
                                                                                                <td class="col-pad" style="padding-bottom:16px;padding-top:16px;">
                                                                                                    <table class="image_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                                        <tr>
                                                                                                            <td class="pad" style="width:100%;" align="center">
                                                                                                                <div style="max-width: 568px;"><img src="https://media.beefree.cloud/pub/bfra/qxh982xj/g6l/t10/rai/Frame%20619.png" style="display: block; height: auto; border: 0; width: 100%;" width="568" alt="" title="" height="auto"></div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                    <table class="paragraph_block block-2" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                                        <tr>
                                                                                                            <td class="pad">
                                                                                                                <div style="color:#583f48;direction:ltr;font-family:Arial, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.2;text-align:left;mso-line-height-alt:17px;">
                                                                                                                    <p style="margin: 0; margin-bottom: 16px;">Olá,&nbsp;<strong>cliente@domínio.com.</strong></p>
                                                                                                                    <p style="margin: 0;">Você acabou de alterar seu <strong>e-mail</strong>&nbsp;no site <strong>Açaí e Polpas Amazônia.</strong></p>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                    <table class="paragraph_block block-3" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                                        <tr>
                                                                                                            <td class="pad">
                                                                                                                <div style="color:#583f48;direction:ltr;font-family:Arial, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.2;text-align:left;mso-line-height-alt:17px;">
                                                                                                                    <p style="margin: 0; margin-bottom: 16px;"><strong>Não foi você?</strong></p>
                                                                                                                    <p style="margin: 0;">Entre em contato com nosso suporte.</p>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                    <table class="divider_block block-4" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                                        <tr>
                                                                                                            <td class="pad" align="center">
                                                                                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                                                    <tr>
                                                                                                                        <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 1px solid #dddddd;"><span style="word-break: break-word;">&#8202;</span></td>
                                                                                                                    </tr>
                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                    <table class="paragraph_block block-5" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                                        <tr>
                                                                                                            <td class="pad">
                                                                                                                <div style="color:#583f48;direction:ltr;font-family:Arial, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.2;text-align:center;mso-line-height-alt:17px;">
                                                                                                                    <p style="margin: 0;">Atenciosamente, Equipe Açaí e Polpas Amazônia.</p>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                    <table class="paragraph_block block-6" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                                        <tr>
                                                                                                            <td class="pad">
                                                                                                                <div style="color:#a17f8c;direction:ltr;font-family:Arial, Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.2;text-align:center;mso-line-height-alt:17px;">
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
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                    </html>
            '
        );

        session_destroy();
        redirectWithMessage("newEmail", "../login.php", 1);

    }catch(Exception $e){
        error_log($e->getMessage());
        redirectWithMessage("error", "newEmail.php", 0);
    }
}
