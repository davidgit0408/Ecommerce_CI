<?php $settings = get_settings('system_settings', true); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="width:100%;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <link rel="icon" type="image/ico" href="#">
    <title>Email - <?= $settings['app_name'] ?></title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i" rel="stylesheet">
</head>

<body style="width:100%;font-family:'open sans', 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
    <div class="card">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td style="text-align:center;vertical-align:top;font-size:0;border-collapse:collapse">
                        <table class="es-header-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#044767;width:720px" cellspacing="0" cellpadding="0" bgcolor="#044767" align="center">
                            <tr style="border-collapse:collapse">
                                <table style="border-spacing:0;border-collapse:collapse" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
                                    <tbody>
                                        <tr style="background-size:cover">
                                            <td style="text-align:center;border-collapse:collapse;background:#fff;border-radius:10px 10px 0px 0px;color:white;height:50px;background-color:#1D92EE">
                                                <h2 style="font-size:20px;font-weight:150"><?= $settings['app_name'] ?></h2>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </tr>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;font-size:0;border-collapse:collapse;padding-left:15px;padding-right:15px">
                        <table style="border-spacing:0;border-collapse:collapse" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f6f6f6">
                            <tbody>
                                <tr>
                                    <td style="padding:10px 25px 10px 25px;background-color:white;border-collapse:collapse">
                                        <div style="font-size:15px;color:#6d6d6d;font-weight:normal">
                                            <p class="card-text"><?= $username ?></p>
                                            <p class="card-text"><?= $email ?></p>
                                            <p class="card-text"><?= $message ?></p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
    </div>
</body>

</html>