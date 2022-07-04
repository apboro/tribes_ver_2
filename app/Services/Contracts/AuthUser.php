<?php

namespace App\Services\Contracts;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Authenticatable ;

/**
 * Гнилая идея, получить авторизованного пользователя
 * через конструктор и контейнер зависимостей.
 * singleton \Illuminate\Auth\AuthServiceProvider::registerAuthenticator
 * объект Auth::user() возвращает null при регистрации
 */
interface AuthUser extends  Authenticatable
{

}