<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $fillable = [
        'user_id',
        'nagrita_number',
        'nagrita_number_normalized',
        'nagrita_front',
        'nagrita_front_hash',
        'nagrita_back',
        'nagrita_back_hash',
        'date_of_birth',
        'district',
        'ward_no',
        'verification_status',
        'verification_score',
        'verification_flags',
        'verified_at',
        'last_verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'verification_flags' => 'array',
        'verified_at' => 'datetime',
        'last_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
