## вызов из консоли с x-debug

прокинуть порт ssh Xdebug 2
ssh -R 9001:localhost:9001 root@188.225.82.96

Запуск консольной команды с параметром дэбага

```bash
php -dxdebug.mode=debug dxdebug.xdebug_session_start=PHPSTORM artisan test --filter KnowledgeObserverTest

```

