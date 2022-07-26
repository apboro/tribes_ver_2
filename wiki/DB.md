## клонировать БД для тестов

выбрать БД
\c testCommunity;

создание пользователя, создание БД для пользователя, создание второй схемы, копирование БД с новым именем для тестов
```psql
create user andrey with encrypted password 'andrey';
CREATE DATABASE community OWNER andrey;
\c community;
CREATE SCHEMA knowledge AUTHORIZATION andrey;
CREATE DATABASE "test_community" WITH TEMPLATE "community" OWNER andrey;
```

## создание схемы в БД для базы знаний

```psql
CREATE SCHEMA knowledge AUTHORIZATION usernamedb;
```

## Установить расширение для UUID 

```psql
## в каждой БД под правами супер пользователя 
\с community;
CREATE EXTENSION "uuid-ossp";
## убедится что функция uuid_generate_v4 установлена
\df;
```

