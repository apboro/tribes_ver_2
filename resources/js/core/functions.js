export function timeFormatting(options) {
    const date = Date.parse(options.date);
    const locale = options.locale ?? 'ru';
    
    /* switch (language) {
        case 'en':
            countryFormat = 'en-US';
            break;
        default:
            countryFormat = 'ru';
    } */
    
    let formatter = new Intl.DateTimeFormat(locale, {
        year: options.year ?? undefined, // 2-digit, numeric
        month: options.month ?? undefined, // 2-digit, numeric, narrow, short, long
        day: options.day ?? undefined, // 2-digit, numeric
        weekday: options.weekday ?? undefined, // narrow, short, long
        hour: options.hour ?? undefined, // 2-digit, numeric
        minute: options.minute ?? undefined, // 2-digit, numeric
        second: options.second ?? undefined, // 2-digit, numeric
    });

    return formatter.format(date);
}

export function copyText(value) {
    const el = document.createElement('textarea');
    el.value = value;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.append(el);

    if (navigator.userAgent.match(/Mac|iPhone|iPod|iPad/i)) {
        navigator.clipboard
            .writeText(value)
            .then(() => {
                console.log('success coppy');
            })
            .catch(() => {
                console.log('error coppy');
            });
    } else {
        el.select();
    }
    document.execCommand('copy');
    document.body.removeChild(el);

    alert('Copied')
}
