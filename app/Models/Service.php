<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['server_id', 'name', 'status'];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
