<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $servers = Server::withCount('domains')->get();
        return view('services.index', compact('servers'));
    }
}
