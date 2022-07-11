## Описание команд для работы с ботом телеграмма
```php

$deleteWebhook = 'https://api.telegram.org/bot5352050869:AAEIYvbTquj8mGEjZrsuonLfhR0uZzAaKxk/deleteWebhook?url=https://test1.spodial.com/bot/webhook';
$setWebhook = 'https://api.telegram.org/bot5352050869:AAEIYvbTquj8mGEjZrsuonLfhR0uZzAaKxk/setWebhook?url=https://test1.spodial.com/bot/webhook';
$getWebhookInfo = 'https://api.telegram.org/bot5352050869:AAEIYvbTquj8mGEjZrsuonLfhR0uZzAaKxk/getWebhookInfo';

$deleteWebhookKN = 'https://api.telegram.org/bot5552498089:AAGnrPSuHur9Pwkvvc9K26MBSVtGO8j3Quc/deleteWebhook?url=https://test1.spodial.com/bot/knowledge-webhook';
$setWebhookKN = 'https://api.telegram.org/bot5552498089:AAGnrPSuHur9Pwkvvc9K26MBSVtGO8j3Quc/setWebhook?url=https://test1.spodial.com/bot/webhook-knowledge';
$getWebhookKNInfo = 'https://api.telegram.org/bot5552498089:AAGnrPSuHur9Pwkvvc9K26MBSVtGO8j3Quc/getWebhookInfo';


$getMe = 'https://api.telegram.org/bot5352050869:AAEIYvbTquj8mGEjZrsuonLfhR0uZzAaKxk/getMe';
```


```telegramm
@BotFather
/mybots
выбрать или создать бота
Bot settings -> domain -> set domain
в тестовом поле ввода передать домен своего сервера "https://test1.spodial.com" 
результат  "Success! Domain updated."
```

![Image](images/telegram_domain_register.png)


## HTML style

To use this mode, pass HTML in the parse_mode field. The following tags are currently supported:

```html
<b>bold</b>, <strong>bold</strong>
<i>italic</i>, <em>italic</em>
<u>underline</u>, <ins>underline</ins>
<s>strikethrough</s>, <strike>strikethrough</strike>, <del>strikethrough</del>
<span class="tg-spoiler">spoiler</span>, <tg-spoiler>spoiler</tg-spoiler>
<b>bold <i>italic bold <s>italic bold strikethrough <span class="tg-spoiler">italic bold strikethrough spoiler</span></s> <u>underline italic bold</u></i> bold</b>
<a href="http://www.example.com/">inline URL</a>
<a href="tg://user?id=123456789">inline mention of a user</a>
<code>inline fixed-width code</code>
<pre>pre-formatted fixed-width code block</pre>
<pre><code class="language-python">pre-formatted fixed-width code block written in the Python programming language</code></pre>
```
Please note:

    Only the tags mentioned above are currently supported.
    All <, > and & symbols that are not a part of a tag or an HTML entity must be replaced with the corresponding HTML entities (< with &lt;, > with &gt; and & with &amp;).
    All numerical HTML entities are supported.
    The API currently supports only the following named HTML entities: &lt;, &gt;, &amp; and &quot;.
    Use nested pre and code tags, to define programming language for pre entity.
    Programming language can't be specified for standalone code tags.
