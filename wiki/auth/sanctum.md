## Создание api-token средствами Sanctum

1. При прохождении регистрации, аутентификации пользователя, покупки курса или
   оплаты тарифа создается бессрочный api-token, который записывает в таблицу
   personal_access_token и поле api-token таблицы users.
   Удаляется старый токен при выполнении тех же действий пользователя.

```php
 public function createTempToken()
    {
        if ($this->tokens()->count() !== 0) {
            $this->tokens()->delete();
        }

        $token = $this->createToken('api-token');
        return $this->withAccessToken($token->plainTextToken)
            ->setTempToken($token->plainTextToken);
    }

    private function setTempToken($token) //todo при переходе на FullRest - Удалить
    {
        $this->api_token = $token;

        return $this->save();
    }
```

2. Для получение токена фронту, обращаться к метатегу api-token.

```html
<meta name="api-token" content="{{ Auth->user()->api_token }}">
```

3. Для получения доступа к защищенным роутам в bootstrap.js присвоить
   заголовку Authorization данные метатега api-token.

```js

let api_token = document.head.querySelector('meta[name="api-token"]').content;
window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + api_token;
```

4. После реализации FullRest API приложения, для аутентификации,
   страница "входа" SPA должна сначала сделать запрос к
   /sanctum/csrf-cookie для инициализации защиты от CSRF для приложения:
```js
axios.get('/sanctum/csrf-cookie').then(response => {
// Login...
});
```
Во время этого запроса Laravel установит cookie XSRF-TOKEN, содержащий
текущий токен CSRF. Этот токен следует передавать в заголовке X-XSRF-TOKEN
при последующих запросах. Некоторые клиентские библиотеки HTTP, такие,
как Axios и Angular HttpClient, будут это делать автоматически.
Если HTTP-библиотека JavaScript не задает автоматически значение, то нужно
будет вручную установить заголовок X-XSRF-TOKEN, чтобы он соответствовал
значению XSRF-TOKEN cookie, установленному этим маршрутом(POSTMAN).
