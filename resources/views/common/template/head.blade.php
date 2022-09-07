<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@auth
<meta name="api-token" content="{{ Session::get('current_token') }}">
@endauth
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">

<title>{{ env('APP_NAME') }}</title>
<link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
<link rel="shortcut icon" type="image/x-icon" href="../../../ico/favicon.ico">

<!-- BEGIN: Custom CSS-->
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<!-- END: Custom CSS-->
<script>
    window.botName = '{{ env('TELEGRAM_BOT_FIRST_NAME') }}';
    window.botLogin = '{{ env('TELEGRAM_BOT_NAME') }}';
</script>

<script src="//code.jivo.ru/widget/Fp7nrxvh0u" async></script>
