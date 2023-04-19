<?php

namespace App\Http\ApiResources;


use App\Http\ApiRequests\ApiStoreOnboardingRequest;

class ApiOnboardingDictionary
{

    public function get(ApiStoreOnboardingRequest $request)
    {
        $subjects = [
            [
                'name' => 'name',
                'label' => 'Название правила',
                'value'=>''
            ],
            [
                'name' => 'mute_new_user',
                'label' => 'Мьют нового участника',
                'value' =>''
            ],
            [
                'name' => 'mute_new_user_period',
                'label' => 'Username пользователя',
                'value' =>''
            ],
            [
                'name' => 'username_length',
                'label' => 'Длина username пользователя',
            ],
            [
                'name' => 'first_name',
                'label' => 'Имя пользователя',
            ],
            [
                'name' => 'first_name_length',
                'label' => 'Длина имени пользователя',
            ],
            [
                'name' => 'last_name',
                'label' => 'Фамилия пользователя',
            ],
            [
                'name' => 'last_name_length',
                'label' => 'Длина фамилия пользователя',
            ],
            [
                'name' => 'reputation',
                'label' => 'Репутация',
            ],

        ];


        return response()->json([
            'subjects' => $subjects,
        ]);
    }


}