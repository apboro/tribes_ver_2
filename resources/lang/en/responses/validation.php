<?php
return [
    'register'=>[
        'email_already_use'=>'Email already use',
        'incorrect_format'=>'Incorrect format',
        'name_max_length'=>'Max name length 40 symbols',
        'email_required'=>'email required',
        'phone_required'=>'Телефон обязателен для заполнения',
        'phone_already_use'=>'Номер телефона уже используется',
        'password_require'=>'Пароль обязателен для заполнения',
        'password_min_length'=>'Минимальная длина - 8 символов',
        'password_confirm'=>'Пароль должен быть подтвержден'
    ],
    'login'=>[
        'email_incorrect_format'=>'Значение поля email должно быть действительным электронным адресом.',
        'email_required'=>'email  - обязательное поле',
        'password_require'=>'пароль обязательное поле',
    ],
    'reset_password'=>[
        'token_required'=>'Отсутствует токен',
        'password_length'=>'Пароль должен быть не менее 6 символов',
        'password_confirmed'=>'Не совпадает с полем “пароль”'
    ],
    'phone'=>[
        'required'=>'Телефон - обязательное поле',
        'incorrect_format'=>'Неверный формат телефона',
        'code_required'=>'Код телефона - обязательное поле',
        'code_incorrect_format'=>'Неверный формат кода телефона',
        'sms_code_required'=>'СМС код обязательное поле',
        'sms_code_not_valid'=>'СМС код -должен быть числом'
    ]
];
