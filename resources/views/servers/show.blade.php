@extends('layouts.app')

@section('content')
<div class="mb-6">
    <a href="{{ route('servers.index') }}" class="text-blue-500 hover:underline">← Zurück zur Übersicht</a>
    <h1 class="text-3xl font-bold mt-2">Server: {{ $server->name }}</h1>
</div>

<!-- System Info Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase font-bold">OS</p>
        <p class="text-lg font-semibold">{{ $server->os ?? 'Unbekannt' }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-purple-500">
        <p class="text-xs text-gray-500 uppercase font-bold">Architektur</p>
        <p class="text-lg font-semibold">{{ $server->architecture ?? 'Unbekannt' }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-orange-500">
        <p class="text-xs text-gray-500 uppercase font-bold">Paket-Manager</p>
        <p class="text-lg font-semibold">{{ $server->package_manager ?? 'Unbekannt' }}</p>
    </div>
    <div class="bg-white p-4 rounded-lg shadow-md border-l-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase font-bold">Status</p>
        <p class="text-lg font-semibold">{{ strtoupper($server->status) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Domains & Subdomains -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Domains & Subdomains</h2>
        <form action="{{ route('domains.store', $server) }}" method="POST" class="mb-6 flex space-x-2">
            @csrf
            <input type="text" name="domain_name" placeholder="example.com" class="flex-1 border p-2 rounded" required>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Hinzufügen</button>
        </form>

        <div class="space-y-4">
            @foreach($server->domains as $domain)
            <div class="border rounded p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold">{{ $domain->domain_name }}</h3>
                </div>
                
                <div class="pl-4 border-l-2 border-gray-200 space-y-2">
                    @foreach($domain->subdomains as $subdomain)
                    <div class="text-sm text-gray-600">{{ $subdomain->subdomain_name }}.{{ $domain->domain_name }}</div>
                    @endforeach
                    
                    <form action="{{ route('subdomains.store', $domain) }}" method="POST" class="mt-2 flex space-x-2">
                        @csrf
                        <input type="text" name="subdomain_name" placeholder="www" class="text-xs border p-1 rounded w-20" required>
                        <button type="submit" class="text-xs bg-blue-500 text-white px-2 py-1 rounded">Subdomain +</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Services & Tools -->
    <div class="space-y-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Installierte Tools</h2>
            <div class="grid grid-cols-1 gap-4">
                @if($server->installed_tools)
                    @foreach($server->installed_tools as $toolName => $toolDetail)
                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 shadow-sm"></span>
                                <span class="text-sm font-bold capitalize text-gray-800">{{ $toolName }}</span>
                            </div>
                        </div>
                        <div class="space-y-3 ml-5">
                            @php
                                // Falls toolDetail noch das alte Format (String) hat, überspringen oder konvertieren
                                $versions = is_array($toolDetail) && isset($toolDetail['versions']) ? $toolDetail['versions'] : [];
                            @endphp
                            
                            @foreach($versions as $version)
                            <div class="border-l-2 border-gray-200 pl-3 py-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-700 font-semibold truncate mr-2" title="{{ $version['version'] }}">
                                        {{ $version['version'] }}
                                    </span>
                                    @if(isset($version['is_up_to_date']))
                                        @if($version['is_up_to_date'])
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-green-100 text-green-800">
                                                Aktuell
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-yellow-100 text-yellow-800">
                                                Update verfügbar
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1 truncate" title="{{ $version['path'] }}">
                                    {{ $version['path'] }}
                                </p>
                            </div>
                            @endforeach

                            @if(empty($versions))
                                <span class="text-xs text-red-500 italic">Keine Versionsinfo</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500 italic">Keine Daten verfügbar</p>
                @endif
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Dienste</h2>
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Dienst</th>
                        <th class="py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($server->services as $service)
                    <tr class="border-b">
                        <td class="py-2">{{ $service->name }}</td>
                        <td class="py-2">
                            <span class="px-2 py-0.5 text-xs rounded {{ $service->status == 'running' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ strtoupper($service->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
