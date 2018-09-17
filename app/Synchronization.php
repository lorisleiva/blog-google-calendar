<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Synchronization extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'token', 'last_synchronized_at'
    ];

    protected $casts = [
        'last_synchronized_at' => 'datetime',
    ];
    
    public function ping()
    {
        return $this->synchronizable->synchronize();
    }

    public function synchronizable()
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($synchronization) {
            $synchronization->id = Uuid::uuid4();
            $synchronization->last_synchronized_at = now();
        });

        static::created(function ($synchronization) {
            $synchronization->ping();
        });
    }
}
