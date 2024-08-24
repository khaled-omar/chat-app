<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SettingLang extends Model
{
    use HasFactory;

    protected $table = 'settings_lang';

    protected $fillable = [
        'value',
        'lang_code',
        'setting_id',
    ];
}
