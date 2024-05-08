<head>
    <meta property="og:image:width" content="300" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image" content="{{ $shop->photo }}" />
    <meta property="og:image:height" content="1000" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{$shop->name ?? ''}}" />
    <meta property="og:description" content="{{$shop->about ?? ''}}" />
    <meta property="og:site_name" content="Spodial" />
    <meta http-equiv="refresh" content="1;URL={{ $link }}">
</head>

