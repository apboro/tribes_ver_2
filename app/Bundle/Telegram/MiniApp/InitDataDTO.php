<?php

declare(strict_types=1);

namespace App\Bundle\Telegram\MiniApp;

use App\Bundle\Telegram\MiniApp\InitData\Chat;
use App\Bundle\Telegram\MiniApp\InitData\Make;
use App\Bundle\Telegram\MiniApp\InitData\Receiver;
use App\Bundle\Telegram\MiniApp\InitData\MiniAppUser;
use Carbon\CarbonInterface;

final class InitDataDTO extends Make
{
    /**
     * Optional. A unique identifier for the Web App session, required for sending messages via the answerWebAppQuery method.
     */
    public ?string $queryId = null;

    /**
     * An object containing data about the current user.
     */
    public ?MiniAppUser $user = null;

    /**
     * An object containing data about the chat partner of the current user in
     * the chat where the bot was launched via the attachment menu. Returned only for
     * private chats and only for Web Apps launched via the attachment menu.
     */
    public ?Receiver $receiver = null;


    /**
     * Optional. An object containing data about the chat where the bot was launched via the attachment menu.
     * Returned for supergroups, channels and group chats – only for Web Apps launched via the attachment menu.
     */
    public ?Chat $chat = null;

    /**
     * Optional. The value of the startattach parameter, passed via link. Only returned for Web Apps when launched from the attachment menu via link.
     * The value of the start_param parameter will also be passed in the GET-parameter tgWebAppStartParam, so the Web App can load the correct interface right away.
     */
    public ?string $startParam = null;

    /**
     * Optional. Type of the chat from which the Web App was opened.
     * Can be either “sender” for a private chat with the user opening the link,“private”, “group”, “supergroup”, or “channel”.
     * Returned only for Web Apps launched from direct links.
     */
    public ?string $chatType = null;

    /**
     * Optional. Global identifier, uniquely corresponding to the chat from which the Web App was opened.
     * Returned only for Web Apps launched from a direct link.
     */
    public ?string $chatInstance = null;

    /**
     * Optional. Time in seconds, after which a message can be sent via the answerWebAppQuery method.
     */
    public ?int $canSendAfter = null;

    /**
     * Unix time when the form was opened.
     */
    public CarbonInterface $authDate;

    /**
     * A hash of all passed parameters, which the bot server can use to check their validity.
     */
    public string $hash;
}