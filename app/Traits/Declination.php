<?php

namespace App\Traits;

trait Declination
{
    public static function defineDeclination($int)
    {
        if ($int == 1) {
            return 'день';
        }
        elseif ($int > 1 && $int < 5) {
            return 'дня';
        }
        elseif ($int > 4 && $int < 21 || $int == 0) {
            return 'дней';
        }
        elseif ($int > 21) {
            $arr = str_split($int, 1);
            $count = count($arr);
            if ($arr[$count - 2] == 1) {
                return 'дней';
            } 
            elseif ($arr[$count - 1] == 1) {
                return 'день';
            }
            elseif ($arr[$count -1] > 1 && $arr[$count -1] < 5) {
                return 'дня';
            }
            elseif ($arr[$count -1] > 4 && $arr[$count -1] < 21 || $arr[$count -1] == 0) {
                return 'дней';
            }
        }
    }
}