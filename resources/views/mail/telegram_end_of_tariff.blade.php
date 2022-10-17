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
    <title>Окончание тарифа</title>
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
        Окончание тарифа
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
                                    <tr>
                                        <td
                                            class="sm-px-24"
                                            style="mso-line-height-rule: exactly; border-radius: 4px; background-color: #ffffff; padding: 48px; text-align: left; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 16px; line-height: 24px; color: #626262;"
                                        >
                                            <p style="font-family: 'Arial', sans-serif; mso-line-height-rule: exactly; margin-top: 0; margin-bottom: 15px; font-size: 24px; font-weight: 700; color: #7367F0;">
                                                Приветствуем {{$user_name}}!
                                            </p>
                                            <p class="sm-leading-32" style="font-family: 'Arial', sans-serif; mso-line-height-rule: exactly; margin: 0; font-size: 16px; line-height: 22px; font-weight: 400; color: #252129;">
                                                Срок действия тарифа
                                                <span style="color: #7367F0;">{{$tariff_variant_name}}, {{$tariff_variant_period}} {{\App\Traits\Declination::defineDeclination($tariff_variant_period)}}</span>
                                                для сообщества
                                                <span style="color: #7367F0;">{{$community_name}}</span>
                                                закончится через
                                                <span style="color: #D0221D; font-weight: 600;">{{$days_left}}</span>
                                                {{\App\Traits\Declination::defineDeclination($days_left)}}.
                                            </p>

                                            @if(count($recTarVars) > 0)
                                                <p style="font-family: 'Arial', sans-serif; margin: 0; margin-bottom: 24px;">
                                                    Для выбора другого активного тарифа перейдите по ссылке
                                                </p>
                                                <a href="{{$link}}" style="font-family: 'Montserrat', sans-serif; color: #FFFFFF; margin: auto; letter-spacing: 0.02em; text-transform: uppercase; padding: 10px 40px; font-weight: 600; font-size: 14px; line-height: 20px; text-decoration: none; background: #7367F0; border-radius: 5px; display: inline-block; text-align: center;">
                                                    Перейти
                                                </a>
                                            @else
                                                <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 24px;">
                                                    Обратитесь к владельцу сообщества, чтобы уточнить информацию об условиях доступа.
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
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
