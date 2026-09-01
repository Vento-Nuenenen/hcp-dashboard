<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Subdomain;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function store(Request $request, Domain $domain)
    {
        $request->validate(['subdomain_name' => 'required']);

        $domain->subdomains()->create([
            'subdomain_name' => $request->subdomain_name,
        ]);

        return back();
    }
}
