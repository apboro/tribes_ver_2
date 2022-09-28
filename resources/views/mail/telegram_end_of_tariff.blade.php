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
                                                <span style="color: #7367F0;">{{$tariff_variant_name}}, {{$tariff_variant_period}} дней</span>
                                                для сообщества
                                                <span style="color: #7367F0;">{{$community_name}}</span>
                                                закончится через
                                                <span style="color: #D0221D; font-weight: 600;">{{$days_left}}</span>
                                                дня.
                                            </p>

                                            @if(count($recTarVars) > 0)
                                                <p style="font-family: 'Arial', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 24px;">
                                                    Для подключения к сообществу выберите для себя другой активный тариф из этого списка:
                                                </p>
                                                @foreach($recTarVars as $tarVar)
                                                    <div style="padding-bottom: 24px; margin-bottom: 22px; border-bottom: 1px solid #7367f026; overflow: auto;">
                                                        <div style="float: left">
                                                            <p style="font-family: 'Arial', sans-serif; font-size: 16px; line-height: 22px; font-weight: 400; color: #3F3F3F; margin-bottom: 9px;">{{ $tarVar->title }}</p>
                                                            <div>
                                                                <span style="font-family: 'Arial', sans-serif; font-size: 18px; line-height: 26px; font-weight: 400; color: #3F3F3F;">{{ $tarVar->period }} дней / </span>
                                                                <span style="font-family: 'Arial', sans-serif; font-size: 24px; line-height: 34px; font-weight: 400; color: #7367F0; letter-spacing: 0.02em;">{{ $tarVar->price }} ₽</span>
                                                            </div>
                                                        </div>
                                                        <div style="float: right">
                                                            <span style="display: block">Выбрать</span>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @else
                                                <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 24px;">
                                                    Обратитесь к владельцу сообщества, чтобы уточнить информацию об условиях доступа.
                                                </p>
                                            @endif
                                            {{--<div style="margin-top: 50px;">adwad</div>
                                            {{dd($recTarVars)}}--}}
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
