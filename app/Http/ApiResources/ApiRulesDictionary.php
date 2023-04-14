<?php

namespace App\Http\ApiResources;

use App\Http\ApiRequests\ActionsConditions\ApiGetRulesDictRequest;

class ApiRulesDictionary
{

    public function get(ApiGetRulesDictRequest $request)
    {
        $subjects = [
            [
                'name' => 'message_text',
                'label' => 'Текст сообщения',
            ],
            [
                'name' => 'message_type',
                'label' => 'Тип сообщения',
            ],
            [
                'name' => 'message_content',
                'label' => 'Содержание сообщения',
            ],
            [
                'name' => 'username',
                'label' => 'Username пользователя',
            ],
            [
                'name' => 'first_name',
                'label' => 'Имя пользователя',
            ],
            [
                'name' => 'last_name',
                'label' => 'Фамилия пользователя',
            ],
            [
                'name' => 'reputation',
                'label' => 'Репутация',
            ],

        ];

        $actions = [
            [
                'name' => 'contain',
                'label' => 'содержит',
                'allowedSubjects' => ['message_text'],
            ],
            [
                'name' => 'equal_to',
                'label' => 'совпадает',
                'allowedSubjects' => ['message_text'],
            ],
        ];

        $values = [
            [
                'name' => 'text',
                'label' => 'текст',
                'allowedSubjects' => ['message_text'],
                'allowedActions' => ['contain', 'equal_to'],
            ],
            [
                'name' => 'length',
                'label' => 'длина',
                'allowedSubjects' => ['message_text', 'username', 'first_name', 'last_name'],
                'allowedActions' => ['contain', 'equal_to'],
            ],
        ];
        $callbacks = [
            'send_message_in_chat_from_bot',
            'delete_message',
            'send_message_in_pm_from_bot',
            'ban_user',
            'mute_user',
            'increase_reputation',
            'decrease_reputation',
            'add_warning',
            'delete_warning'
        ];

        return response()->json([
           'subjects' => $subjects,
           'actions' => $actions,
           'values' => $values,
           'callbacks' => $callbacks,
        ]);
    }

}