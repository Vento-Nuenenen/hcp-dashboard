<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = ['name', 'ip_address', 'api_token', 'status', 'cpu_usage', 'ram_usage', 'disk_usage', 'last_seen_at', 'os', 'architecture', 'package_manager', 'installed_tools', 'tools_to_install'];

    protected $casts = [
        'installed_tools' => 'array',
        'tools_to_install' => 'array',
        'last_seen_at' => 'datetime',
    ];

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
