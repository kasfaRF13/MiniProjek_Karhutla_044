<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'area_id',
        'location_detail',
        'latitude',
        'longitude',
        'incident_time',
        'condition_level',
        'description',
        'evidence_file',
        'status',
    ];

    // Relasi: Sebuah laporan dimiliki oleh satu User (Pelapor)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Sebuah laporan berada di satu Area tertentu
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
