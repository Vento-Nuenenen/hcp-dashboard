<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Service;
use Illuminate\Http\Request;

class ServerApiController extends Controller
{
    public function update(Request $request)
    {
        $token = $request->header('X-Server-Token');
        $server = Server::where('api_token', $token)->first();

        if (!$server) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $server->update([
            'cpu_usage' => $request->cpu_usage,
            'ram_usage' => $request->ram_usage,
            'disk_usage' => $request->disk_usage,
            'os' => $request->os,
            'architecture' => $request->architecture,
            'package_manager' => $request->package_manager,
            'installed_tools' => $request->installed_tools,
            'status' => 'online',
            'last_seen_at' => now(),
        ]);

        if ($request->has('services')) {
            foreach ($request->services as $serviceData) {
                Service::updateOrCreate(
                    ['server_id' => $server->id, 'name' => $serviceData['name']],
                    ['status' => $serviceData['status']]
                );
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function getConfig(Request $request)
    {
        $token = $request->header('X-Server-Token');
        $server = Server::where('api_token', $token)->first();

        if (!$server) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'domains' => $server->domains()->with('subdomains')->get(),
        ]);
    }
}
