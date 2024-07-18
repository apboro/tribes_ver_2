<?php

namespace App\Services\Shop;

use App\Models\User;

class UsersReport
{
    private function prepareTableHeader(): array
    {
        return [
            0 => [
                'Имя',
                'Email',
                'Телефон',
                'Дата регистрации'
            ]
        ];
    }

    private function prepareRow(User $user): array
    {
        return [
            $user->name ?? '',
            $user->email ?? '',
            $user->phone ?? '',
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function prepareTable(): array
    {
        $users = User::getUsersHavingSubscription();
        $userRows = $users->map(function ($user) {
            return $this->prepareRow($user);
        })->toArray();
    
        return array_merge($this->prepareTableHeader(), $userRows);
    }
}