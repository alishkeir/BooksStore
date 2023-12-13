<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrizeGamingForm extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const CHRISTMAS_GAME_2022 = 'christmas_game_2022';

    public const KAP_DUMASZINHAZ_BELEPOJEGY = 'kap_dumaszinhaz_belepojegy';

    public const LAST_AVAILABLE_BEFORE = '2023/12/13'; // yyyy/mm/dd

    protected $table = 'prize_gaming_forms';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'order_number',
        'prize_game_form',
    ];

    protected static function booted()
    {
        // static::creating(function ($model) {
        //     $model->prize_game_form = self::CHRISTMAS_GAME_2022;
        // });
    }
}