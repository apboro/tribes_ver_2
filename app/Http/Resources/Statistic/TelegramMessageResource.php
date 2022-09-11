<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

/** @property object $resource */
class TelegramMessageResource extends JsonResource
{
    public function toArray($request)
    {

        $reactions = DB::table('telegram_message_reactions')
            ->where('group_chat_id', $this->resource->group_chat_id)
            ->where('message_id', $this->resource->message_id)
            ->leftJoin('telegram_dict_reactions', 'telegram_message_reactions.reaction_id', 'telegram_dict_reactions.id')
            ->distinct()
            ->select(
                'telegram_dict_reactions.code',
                'telegram_dict_reactions.name',
                DB::raw("COUNT(telegram_message_reactions.reaction_id) as count_reactions")
            )
            ->groupBy('telegram_dict_reactions.code', 'telegram_dict_reactions.name')
            ->get();

        return [
            'telegram_user_id' => $this->resource->telegram_user_id,
            "name" => $this->resource->name,
            "nick_name" => $this->resource->nick_name,
            "text" => $this->resource->text,
            "answers" => $this->resource->answers,
            "utility" => $this->resource->utility,
            "count_reactions" => $this->resource->count_reactions,
            "message_date" => $this->resource->message_date,
            "reactions" => TelegramMessageReactionResource::collection($reactions)
        ];
    }
}
