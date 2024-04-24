<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLegalInfo extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $table = 'user_legal_info';

    public $fillable = [
        'name',
        'inn',
        'kpp',
        'email',
        'phone',
        'user_id',
        'address',
        'ogrn',
        'additionally',
    ];

    public function updateProps(array $properties)
    {
        $this->fill($properties);
        $this->save();
    }

    public function getInfoAsTextAttribute(): string
    {
        return 'Название: ' . $this->name . ', ' . 
                'ИНН: ' . $this->inn . ', ' . 
                ($this->kpp ? 'КПП: ' . $this->kpp . ', ' : '') .
                'E-mail: ' . $this->email . ', ' .
                ($this->phone ? 'Телефон: ' . $this->phone : '') . '.';
    }
}
