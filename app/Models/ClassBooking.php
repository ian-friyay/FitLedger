<?php

namespace App\Models;

use App\Models\Concerns\BelongsToGym;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassBooking extends Model
{
    use BelongsToGym, HasFactory;

    protected $fillable = [
        'gym_id',
        'gym_class_id',
        'member_id',
        'status',
    ];

    public function gymClass(): BelongsTo
    {
        return $this->belongsTo(GymClass::class, 'gym_class_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}