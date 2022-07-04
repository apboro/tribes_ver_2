<?php


namespace App\Traits;


trait Modulable
{
    public function getSource()
    {
        return $this->id;
    }
}