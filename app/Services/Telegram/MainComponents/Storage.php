<?php 

namespace App\Services\Telegram\MainComponents;


use Askoldex\Teletant\Interfaces\StorageInterface;
use App\Models\TelegramUser;

class Storage implements StorageInterface 
{
    protected $user; 

    public function __construct(TelegramUser $user)
    {
        $this->user = $user;    
    }

    public function setScene(string $sceneName)
    {
        $this->user->update([
            'scene' => $sceneName
        ]);
    }

    public function getScene(): string
    {
        return $this->user->scene ?? '';
    }

    public function setTtl(string $sceneName, int $seconds)
    {

    }

    public function getTtl(string $sceneName)
    {
        
    }
}
