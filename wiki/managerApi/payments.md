## Работа с Api manager PaymentController

роут `host/api/v2/payments`
1. При запросе по роуту возвращаются данные в виде Json
   со следующей структурой:
```
   {
    "current_page": 1,
    "data": [
        {
            "id":
            "OrderId":                                                                                                                                                                                                                                                ",
            "community_id": 
            "add_balance": 
            "from":
            "comment":
            "isNotify":
            "telegram_user_id":
            "paymentId":
            "amount":
            "paymentUrl":
            "response":
            "status":
            "token":
            "error":                                                                                                                                                                                                                                                         ",
            "created_at":
            "updated_at":
            "type":
            "activated":
            "SpAccumulationId":
            "RebillId":
            "user_id":
            "payable_id":
            "payable_type":
            "author":
        },
    ],
    "first_page_url": "http:\/\/localhost\/api\/v2\/payments?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http:\/\/localhost\/api\/v2\/payments?page=1",
    "links": [
        {
            "url": null,
            "label": "\u041d\u0430\u0437\u0430\u0434",
            "active": false
        },
        {
            "url": "http:\/\/localhost\/api\/v2\/payments?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "\u0414\u0430\u043b\u0435\u0435",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http:\/\/localhost\/api\/v2\/payments",
    "per_page": 15,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

2. Передаваемые параметры 
```
    search - Строковый поиск по колонкам OrderId и from
    date - Дата для поиска по колонке created_at
    sort[name] - сортировка по столбцу. Пока есть след. колонки:
         1. user => user_id
         2. date => created_at
         3. default => id
    sort[rule] - сортировка по след. правилам:
         1. asc => asc
         2. desc => desc
         3. default => desc
    entries - количество объектов на страницу
```

3. Коллекция для постмана находится на пути wiki/files/tribes-payments-manager.postman_collection.json
