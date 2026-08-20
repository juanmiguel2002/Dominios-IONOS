<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'ionos_id',
        'name',
        'provisioning_status',
        'set_to_renew_on',
        'set_to_expire_on',
        'pending_provisioning',
        'raw',
        'client_name',
        'notes',
        'is_active',
        'synced_at',
    ];

    protected $casts = [
        'set_to_renew_on' => 'date',
        'set_to_expire_on' => 'date',
        'pending_provisioning' => 'boolean',
        'is_active' => 'boolean',
        'raw' => 'array',
        'synced_at' => 'datetime',
    ];
}
