<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        return view('servers.index', compact('servers'));
    }

    public function show(Server $server)
    {
        $server->load(['domains.subdomains', 'services']);
        return view('servers.show', compact('server'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ip_address' => 'required|ip',
        ]);

        Server::create([
            'name' => $request->name,
            'ip_address' => $request->ip_address,
            'api_token' => bin2hex(random_bytes(16)),
        ]);

        return redirect()->route('servers.index');
    }
}
