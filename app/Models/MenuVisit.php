<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuVisit extends Model
{
    protected $fillable = ['slug', 'ip_address'];
}
