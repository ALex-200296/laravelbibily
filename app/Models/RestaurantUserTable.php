<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantUserTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'restaurant_id',
        'table_id',
        'time_of_day',
    ];
}
