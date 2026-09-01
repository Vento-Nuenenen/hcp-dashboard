<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = ['server_id', 'domain_name'];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function subdomains()
    {
        return $this->hasMany(Subdomain::class);
    }
}
