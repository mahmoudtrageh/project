<?php

namespace Modules\Lang\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Lang\Database\Factories\LangFactory;

class Lang extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'icon',
        'status',
        'rtl',
    ];
}
