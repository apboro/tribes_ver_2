<?php

namespace App\Traits;

use App\Models\DonateVariant;
use App\Models\TariffVariant;

trait Authorable
{
    public function getAuthor()
    {
        if($this instanceof DonateVariant || $this instanceof TariffVariant){
            return $this->author();
        } else {
            return $this->author()->first();
        }
    }
}