<?php

namespace App\Rules;

   use Illuminate\Contracts\Validation\Rule;
   use Illuminate\Support\Facades\Auth;

   class UserHasShopRule implements Rule
   {
       public function passes($attribute, $value)
       {
           return Auth::user()->hasShops($value);
       }

       public function message()
       {
           return 'Магазин не найден';
       }
   }