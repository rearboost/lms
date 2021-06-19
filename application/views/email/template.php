<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <!--[if gte mso 9]>
    <xml>
    <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
<![endif]-->
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="format-detection" content="date=no" />
<meta name="format-detection" content="address=no" />
<meta name="format-detection" content="telephone=no" />
<meta name="x-apple-disable-message-reformatting" />
<!--[if !mso]><!-->
<link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i" rel="stylesheet" />
<!--<![endif]-->
<title><?php echo get_phrase($subject); ?></title>
<!--[if gte mso 9]>
<style type="text/css" media="all">
sup { font-size: 100% !important; }
</style>
<![endif]-->


<style type="text/css" media="screen">
/* Linked Styles */
body { padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#f4f4f4; -webkit-text-size-adjust:none }
a { color:#66c7ff; text-decoration:none }
p { padding:0 !important; margin:0 !important }
img { -ms-interpolation-mode: bicubic; /* Allow smoother rendering of resized image in Internet Explorer */ }
.mcnPreviewText { display: none !important; }


/* Mobile styles */
@media only screen and (max-device-width: 480px), only screen and (max-width: 480px) {
    .mobile-shell { width: 100% !important; min-width: 100% !important; }
    .bg { background-size: 100% auto !important; -webkit-background-size: 100% auto !important; }

    .text-header,
    .m-center { text-align: center !important; }

    .center { margin: 0 auto !important; }
    .container { padding: 20px 10px !important }

    .td { width: 100% !important; min-width: 100% !important; }

    .m-br-15 { height: 15px !important; }
    .p30-15 { padding: 30px 15px !important; }
    .p40 { padding: 20px !important; }

    .m-td,
    .m-hide { display: none !important; width: 0 !important; height: 0 !important; font-size: 0 !important; line-height: 0 !important; min-height: 0 !important; }

    .m-block { display: block !important; }

    .fluid-img img { width: 100% !important; max-width: 100% !important; height: auto !important; }

    .column,
    .column-top,
    .column-empty,
    .column-empty2,
    .column-dir-top { float: left !important; width: 100% !important; display: block !important; }
    .column-empty { padding-bottom: 10px !important; }
    .column-empty2 { padding-bottom: 20px !important; }
    .content-spacing { width: 15px !important; }
}
</style>
</head>
<body class="body" style="padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#f4f4f4; -webkit-text-size-adjust:none;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f4f4">
        <tr>
            <td align="center" valign="top">
                <table width="650" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                    <tr>
                        <td class="td container" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; margin:0; font-weight:normal; padding:55px 0px;">
                            <!-- Header -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="p30-15" style="padding: 25px 30px 25px 30px;" bgcolor="#ffffff">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <th class="column-top" width="145" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td class="h3 pb20" style="color:#114490; font-family:'Noto Sans', Arial,sans-serif; font-size:24px; line-height:32px; text-align:left; padding-bottom:20px;"><?php echo get_phrase($subject); ?></td>
                                                                    </tr>
                                                                </table>
                                                            </th>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- END Header -->



                            <!-- Article / Title + Copy + Button -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                            <tr>
                                                <td class="p30-15" style="padding: 50px 30px;">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td class="text pb20" style="color:#777777; font-family:'Noto Sans', Arial,sans-serif; font-size:14px; line-height:26px; text-align:left; padding-bottom:20px;">
                                                                <?php echo $message; ?>
                                                            </td>
                                                        </tr>
                                                        <!-- Button -->
                                                        <tr>
                                                            <td align="left">
                                                                <table border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <?php if (isset($redirect_url) && !empty($redirect_url)): ?>
                                                                            <td class="text-button" style="background:#114490; color:#ffffff; font-family:'Noto Sans', Arial,sans-serif; font-size:14px; line-height:18px; padding:12px 20px; text-align:center; border-radius:6px;"><a href="<?php echo $redirect_url; ?>" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none;"><span class="link-white" style="color:#ffffff; text-decoration:none;">Verify Your Account</span></a></td>
                                                                        <?php endif; ?>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <!-- END Button -->
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- END Article / Title + Copy + Button -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
