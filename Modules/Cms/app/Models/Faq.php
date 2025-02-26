<?php

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Cms\Database\Factories\FaqFactory;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'question',
        'answer',
        'is_published'
    ];

    public $translatable = ['question', 'answer'];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
