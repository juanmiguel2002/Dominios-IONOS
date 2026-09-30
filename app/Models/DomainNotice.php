<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainNotice extends Model
{
    public const TYPE_AVISO = 'aviso';
    public const TYPE_RENOVADO = 'renovado';

    protected $fillable = [
        'domain',
        'domain_id',
        'type',
        'reference_date',
        'notified_at',
    ];

    protected $casts = [
        'reference_date' => 'date',
        'notified_at' => 'datetime',
    ];
}
