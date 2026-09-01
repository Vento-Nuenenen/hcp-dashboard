<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdomain extends Model
{
    protected $fillable = ['domain_id', 'subdomain_name', 'assigned_tools'];

    protected $casts = [
        'assigned_tools' => 'array',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
