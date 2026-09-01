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
        $allServers = Server::all();
        return view('servers.show', compact('server', 'allServers'));
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

    public function installTool(Request $request, Server $server)
    {
        $request->validate([
            'tool' => 'required|string',
        ]);

        $tools = $server->tools_to_install ?? [];
        if (!in_array($request->tool, $tools)) {
            $tools[] = $request->tool;
            $server->update(['tools_to_install' => $tools]);
        }

        return back()->with('success', 'Tool wurde zur Installationswarteschlange hinzugefügt.');
    }
}
