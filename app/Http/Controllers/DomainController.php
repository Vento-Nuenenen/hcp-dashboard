<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Server;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index()
    {
        $domains = Domain::with('server', 'subdomains')->get();
        $allServers = Server::all();
        return view('domains.index', compact('domains', 'allServers'));
    }

    public function store(Request $request, Server $server)
    {
        $request->validate(['domain_name' => 'required|unique:domains,domain_name']);

        $server->domains()->create([
            'domain_name' => $request->domain_name,
        ]);

        return back();
    }

    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'domain_name' => 'required|unique:domains,domain_name,' . $domain->id,
            'server_id' => 'required|exists:servers,id',
            'assigned_tools' => 'nullable|array',
        ]);

        $domain->update($request->all());

        return back();
    }

    public function destroy(Domain $domain)
    {
        $domain->delete();
        return back();
    }
}
