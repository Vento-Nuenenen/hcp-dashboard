<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Subdomain;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function store(Request $request, Domain $domain)
    {
        $request->validate([
            'subdomain_name' => 'required',
            'assigned_tools' => 'nullable|array',
        ]);

        $domain->subdomains()->create([
            'subdomain_name' => $request->subdomain_name,
            'assigned_tools' => $request->assigned_tools,
        ]);

        return back();
    }

    public function update(Request $request, Subdomain $subdomain)
    {
        $request->validate([
            'subdomain_name' => 'required',
            'assigned_tools' => 'nullable|array',
        ]);

        $subdomain->update([
            'subdomain_name' => $request->subdomain_name,
            'assigned_tools' => $request->assigned_tools,
        ]);

        return back();
    }

    public function destroy(Subdomain $subdomain)
    {
        $subdomain->delete();
        return back();
    }
}
