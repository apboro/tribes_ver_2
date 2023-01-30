<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
  <meta charset="utf-8">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
  <!--[if mso]>
    <xml><o:officedocumentsettings><o:pixelsperinch>96</o:pixelsperinch></o:officedocumentsettings></xml>
  <![endif]-->
    <title>Регистрация</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700" rel="stylesheet" media="screen">
    <style>
.hover-underline:hover {
  text-decoration: underline !important;
}
@media (max-width: 600px) {
  .sm-w-full {
    width: 100% !important;
  }
  .sm-px-24 {
    padding-left: 24px !important;
    padding-right: 24px !important;
  }
  .sm-py-32 {
    padding-top: 32px !important;
    padding-bottom: 32px !important;
  }
  .sm-leading-32 {
    line-height: 32px !important;
  }
}
</style>
</head>
<body style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #eceff1;">
    <div style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; display: none;">
        {{ env('APP_NAME') }} - новое уведомление
    </div>

    <div
        role="article"
        style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;"
    >
        <table
            style="width: 100%; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;"
            cellpadding="0"
            cellspacing="0"
            role="presentation"
        >
            <tr>
                <td
                    align="center"
                    style="mso-line-height-rule: exactly; background-color: #eceff1; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;"
                >
                    <table
                        class="sm-w-full"
                        style="width: 600px;"
                        cellpadding="0"
                        cellspacing="0"
                        role="presentation"
                    >
                        <tr>
                            <td
                                align="center"
                                class="sm-px-24"
                                style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;"
                            >
                                <table
                                    style="width: 100%; margin: 0; margin-top: 40px"
                                    cellpadding="0"
                                    cellspacing="0"
                                    role="presentation"
                                >
                                    {{ env('APP_NAME') }}
                                    <tr>
                                        <td
                                            class="sm-px-24"
                                            style="mso-line-height-rule: exactly; border-radius: 4px; background-color: #ffffff; padding: 48px; text-align: left; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 16px; line-height: 24px; color: #626262;"
                                        >
                                            <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 0; font-size: 24px; font-weight: 700; color: #ff5850;">
                                                {{$mailBody}} {{$courseName}}
                                            </p>

                                            <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 24px;">

                                            </p>
                                        </td>
                                    </tr>
                                    Если мы не решили Ваш вопрос, отправьте, пожалуйста, повторное обращение или свяжитесь с нашими специалистами в Telegram.
                                    <br>
                                    <a href="{{env('APP_URL')}}">{{env('APP_NAME')}}</a>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
