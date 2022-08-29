<?php

namespace App\Services\Telegram\MainComponents;

use App\Models\TelegramUser;
use App\Services\TelegramLogService;
use Askoldex\Teletant\States\Scene;
use Askoldex\Teletant\Addons\Menux;
use Askoldex\Teletant\Context;
use App\Models\Community;
use App\Models\Donate;

class Scenes
{

    public static function getDonateScene()
    {
        try {
            $scene = new Scene('donate');

            $scene->onAction('variant:{id:string}', function (Context $ctx) {
                $id = $ctx->var('id');
                $community = Community::find($id);

                if ($community->donate->first() !== NULL) {
                    $menu = Menux::Create('links')->inline();

                    foreach ($community->donate as $donate) {
                        $menu->row()->btn(($donate->title) ? $donate->title : 'Донат' . $donate->index, 'donate:' . $donate->id);
                    }
                    $menu->row()->btn('Отмена', 'concellation');

                    $ctx->reply('Выберите донат', $menu);
                } else {
                    $ctx->reply('Сообщества в которых вы состоите не подключили функцию донатов.');
                    $ctx->leave();
                }
            });

            $scene->onAction('donate:{id:string}', function (Context $ctx) {
                $id = $ctx->var('id');
                $donate = Donate::find($id);
                $ty = TelegramUser::where('telegram_id', $ctx->getUserID())->first();
                $ty->scene_for_donate = $donate->id;
                $ty->save();
                $community = $donate->community;
                if ($donate) {
                    $menu = Menux::Create('links')->inline();
                    foreach ($donate->variants as $variant) {
                        if ($variant->price && $variant->isActive !== false) {
                            $key = array_search($variant->currency, Donate::$currency);
                            $currencyLabel = Donate::$currency_labels[$key];
                            $data = [
                                'amount' => $variant->price,
                                'currency' => $variant->currency,
                                'donateId' => $donate->id
                            ];
                            $desc = $variant->description ? ' — ' . $variant->description : '';

                            $menu->row()->uBtn($variant->price . $currencyLabel . $desc, $community->getDonatePaymentLink($data));
                        } elseif ($variant->min_price && $variant->max_price && $variant->isActive !== false) {
                            $desc = $variant->description ?? 'Произвольная сумма';
                            $menu->row()->btn($desc, 'sum');
                        }
                    }

                    if ($donate->checkForNonStaticVariant() == true)
                        $menu->row()->btn('Отмена', 'concellation');

                    $img = ($donate->getMainImage()) ? '<a href="' . route('main') . $donate->getMainImage()->url . '">&#160</a>' : '';
                    $description = ($donate->description) ? $donate->description : 'Описания нет';
                    $text = $description . $img;

                    $ctx->replyHTML($text, $menu);

                    if ($donate->checkForNonStaticVariant() == false)
                        $ctx->leave();
                } else {
                    $ctx->reply('Донаты не определены для сообщества.');
                    $ctx->leave();
                }
            });

            $scene->onAction('sum', function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $menu->row()->btn('Отмена', 'concellation');
                $ctx->replyHTML('Введите сумму:', $menu);
            });

            $scene->onText('{sum:integer}', function (Context $ctx) {
                try {
                    $ty = TelegramUser::where('telegram_id', $ctx->getUserID())->first();
                    $donate = Donate::find($ty->scene_for_donate);

                    $sum = $ctx->var('sum');
                    foreach ($donate->variants ?? [] as $variant) {
                        if ($variant->min_price and $variant->max_price) {
                            $min_price = $variant->min_price;
                            $max_price = $variant->max_price;
                            $key = array_search($variant->currency, Donate::$currency);
                            $currencyLabel = Donate::$currency_labels[$key];
                        } else {
                            $min_price = 50;
                            $max_price = 14999;
                            $currencyLabel = '₽';
                        }
                    }
                    if (gettype($sum) != 'integer') {
                        $ctx->replyHTML('Введите число');
                    } elseif ($sum < $min_price) {
                        $ctx->replyHTML('К сожалению сумма не может быть меньше ' . $min_price);
                    } elseif ($sum > $max_price) {
                        $ctx->replyHTML('К сожалению сумма не может быть больше ' . $max_price);
                    } else {
                        $data = [
                            'amount' => $sum,
                            'currency' => 0,
                            'donateId' => $donate->id
                        ];
                        $ctx->replyMarkdown(
                            'Донат',
                            Menux::Create('Donate')
                                ->inline()->row()
                                ->uBtn('Оказать материальную помощ в размере: ' . $sum . $currencyLabel, $donate->community->getDonatePaymentLink($data))
                                ->row()
                        );
                    }
                    $ctx->leave();
                } catch (\Exception $e) {
                    TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
                }
            });

            $scene->onAction('concellation', function (Context $ctx) {
                $ctx->replyHTML('Донат отменён');
                $ctx->leave();
            });

            return $scene;
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    public static function getAllScene()
    {
        return [self::getDonateScene()];
    }
}
