<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Server;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function store(Request $request, Server $server)
    {
        $request->validate(['domain_name' => 'required|unique:domains,domain_name']);

        $server->domains()->create([
            'domain_name' => $request->domain_name,
        ]);

        return back();
    }
}
