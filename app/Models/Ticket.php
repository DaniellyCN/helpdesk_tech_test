<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    /**
     * Data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_requester_id',
        'user_assigned_id',
        'category',
        'description',
        'status',
        'priority'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    /**
     * Defines a "BelongsTo" relationship to retrieve the user who acts as the requester of this ticket.
     *
     * @return BelongsTo
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_requester_id', 'uuid');
    }

    /**
     * Defines a "BelongsTo" relationship to retrieve the user to whom this ticket is assigned.
     *
     * @return BelongsTo
     */
    public function to_assigned(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_assigned_id', 'uuid');
    }
}
