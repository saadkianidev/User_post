<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTheme extends Model
{
    protected $fillable = ['user_id', 'primary_color', 'secondary_color', 'bg_color'];
}