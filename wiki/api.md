## Соглашения наименования uri-адресов
test
роут `host/api/controller/action`
1. контроллер представляет собой имя существительное во множественном числе
```
host/api/questions
host/api/members
host/api/communities
```
2. action стандартные crud
```
    list(post)
    get(post)
    add(post)
    store(post)
    delete(post)
    ----- какие то дополнительные dictionary-enum-сущности
    statuses{post} {'id':1,'name' : 'Новый'}
    ----- список доступных фильтров для листинга вопросов
    filters{post} {'key':'user', 'name' : 'Пользователи', 'type': 'enum', 'list':{obj-users}} 
    
    -----
    если сущности должны быть управляемыми, то они выносятся в отдельный контроллер например `category`
    do{post} роут для массовых операций {'command':'commName', 'ids' : ['1',2,3], 'params':['mark': true]}
    
```
3. токены авторизации и аутентификации передаются в headers

4. тело запроса это объекты и коллекции объектов в `data`, так же по необходимости 
   передаются поименованные вспомогательные объекты типа `pagination`
```json 
{
    data: {
        {'id':'a'},
        {'id':1},
        {'id':null},
        {'id':{obj}},
    },
    'pagination': {
        pagination-object
    }
}
```