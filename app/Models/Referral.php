<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends BaseModel
{
    protected $table = 'th_referral';
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'prev_parent_id',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
