## Автосохранение логина и пароля для https 

```bash 
## скопировать У СЕБЯ НА МАШИНЕ свой публичный ключ
cat ~/.ssh/id_rsa.pub
## добавить в конец в файл НА СЕРВЕРЕ, если нету то создать
nano ~/.ssh/authorized_keys
```

```bash
git config  credential.helper store

git clone https://git.fabit.ru/senatorov/tribes.git .

## nano .git/config ## проверка раздела credential
```

При каждом следующем правильном вводе логина и пароля они будут автоматически сохраняться в хранилище

