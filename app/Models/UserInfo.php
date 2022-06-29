<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends BaseModel
{
    use HasFactory;

    protected $table = 'th_users_info';

    protected $fillable = [
        'user_id',
        'date',
        'type', 
        'level',
        'total_bet',
        'left_bonus',
        'right_bonus',
        'basic_bonus',
        'basic_percent',
        'bonus_rate'
    ];

    public function levelInfo() {
        return $this->belongsTo(BonusRateSetting::class, 'level', 'id');
    }

    public function user() {
        return $this->hasOne(User::class);
    }
}
