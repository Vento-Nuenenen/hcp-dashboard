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

        $updateData = [
            'cpu_usage' => $request->cpu_usage,
            'ram_usage' => $request->ram_usage,
            'disk_usage' => $request->disk_usage,
            'os' => $request->os,
            'architecture' => $request->architecture,
            'package_manager' => $request->package_manager,
            'installed_tools' => $request->installed_tools,
            'status' => 'online',
            'last_seen_at' => now(),
        ];

        // Auto-cleanup tools_to_install if they are now installed
        if ($request->installed_tools && $server->tools_to_install) {
            $installedToolNames = array_keys($request->installed_tools);
            $pendingTools = $server->tools_to_install;
            $updatedPending = array_values(array_diff($pendingTools, $installedToolNames));
            
            if (count($updatedPending) !== count($pendingTools)) {
                $updateData['tools_to_install'] = $updatedPending;
            }
        }

        $server->update($updateData);

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
            'tools_to_install' => $server->tools_to_install,
            'domains' => $server->domains()->with('subdomains')->get()->map(function ($domain) {
                return [
                    'id' => $domain->id,
                    'domain_name' => $domain->domain_name,
                    'assigned_tools' => $domain->assigned_tools,
                    'subdomains' => $domain->subdomains->map(function ($subdomain) {
                        return [
                            'id' => $subdomain->id,
                            'subdomain_name' => $subdomain->subdomain_name,
                            'assigned_tools' => $subdomain->assigned_tools,
                        ];
                    }),
                ];
            }),
        ]);
    }
}
