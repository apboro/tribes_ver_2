## Работа с Api manager PaymentController

роут `host/api/v2/payments`
1. При запросе по роуту возвращаются данные в виде Json
   со следующей структурой:
```
{
    "data": [
        {
            "OrderId",
            "community",
            "add_balance",
            "from",
            "status",
            "created_at",
            "type"
        }
    ],
    "links": {
        "first",
        "last",
        "prev",
        "next"
    },
    "meta": {
        "current_page": 1,
        "from",
        "last_page",
        "links": [
            {
                "url",
                "label",
                "active"
            }
        ],
        "path",
        "per_page",
        "to",
        "total"
    }
}
```

2. Передаваемые параметры 
```
    search - Строковый поиск по колонкам OrderId и from;
    date - Дата для поиска по колонке created_at;
    sort[name] - сортировка по столбцу. Пока есть след. колонки:
         1. user => user_id;
         2. date => created_at;
         3. default => id;
    sort[rule] - сортировка по след. правилам:
         1. asc => asc
         2. desc => desc
         3. default => desc;
    from - фильтр из списка уникальных покупателей:
         1)Для получения списка уникальных покупателей обращаться к роуту host/api/v2/customers;
         2)Формат возвращения данных:
            customers[
               {
                  'id',
                  'name'
               }
            ];
         3)Для фильтрации передавать id покупателя;
    entries - количество объектов на страницу.
```

3. Коллекция для постмана находится на пути wiki/files/tribes-payments-manager.postman_collection.json
