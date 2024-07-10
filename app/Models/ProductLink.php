<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLink extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'product_id';

    public $guarded = [];
}