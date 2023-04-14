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
                'name' => 'message_length',
                'label' => 'Длина сообщения',
            ],
            [
                'name' => 'username',
                'label' => 'Username пользователя',
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

        $actions = [
            [
                'name' => 'contain',
                'label' => 'содержит',
                'allowedSubjects' => ['message_text'],
            ],
            [
                'name' => 'equal_to',
                'label' => 'совпадает',
                'allowedSubjects' => ['message_text', 'reputation'],
            ],
            [
                'name' => 'less_than',
                'label' => 'меньше чем',
                'allowedSubjects' => ['message_length', 'username_length', 'first_name_length', 'last_name_length', 'reputation'],
            ],
            [
                'name' => 'more_than',
                'label' => 'больше чем',
                'allowedSubjects' => ['message_length', 'username_length', 'first_name_length', 'last_name_length', 'reputation'],
            ],
            [
                'name' => 'format',
                'label' => 'формат',
                'allowedSubjects' => ['username', 'first_name', 'last_name'],
            ],
        ];

        $values = [
//            [
//                'name' => 'text',
//                'label' => 'ввести вручную',
//                'allowedSubjects' => ['message_text'],
//                'allowedActions' => ['contain', 'equal_to'],
//            ],
//            [
//                'name' => 'length',
//                'label' => 'ввести вручную',
//                'allowedSubjects' => ['message_text','message_length', 'username_length', 'first_name_length', 'last_name_length', 'reputation'],
//                'allowedActions' => ['equal_to', 'less_than', 'more_than'],
//            ],
            [
                'name' => 'rtl_format',
                'label' => 'арабская вязь',
                'allowedSubjects' => ['username', 'first_name', 'last_name'],
                'allowedActions' => ['format'],
            ],
            [
                'name' => 'link',
                'label' => 'ссылку',
                'allowedSubjects' => ['message_text'],
                'allowedActions' => ['contain'],
            ],
            [
                'name' => 'bot_command',
                'label' => 'команду боту',
                'allowedSubjects' => ['message_text'],
                'allowedActions' => ['contain'],
            ],
            [
                'name' => 'channel_message',
                'label' => 'сообщение от имени канала',
                'allowedSubjects' => ['message_text'],
                'allowedActions' => ['contain'],
            ],
            [
                'name' => 'telegram_system_message',
                'label' => 'системное сообщение Telegram',
                'allowedSubjects' => ['message_text'],
                'allowedActions' => ['contain'],
            ],
            [
                'name' => 'custom',
                'value' =>'',
                'label' => 'введите значение',
                'allowedSubjects' => ['message_text','message_length', 'username_length', 'first_name_length', 'last_name_length', 'reputation'],
                'allowedActions' => ['contain', 'less_than', 'more_than', 'equal_to'],
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