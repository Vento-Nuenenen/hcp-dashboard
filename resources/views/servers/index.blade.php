@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Server Übersicht</h1>
    <button onclick="document.getElementById('addModal').classList.toggle('hidden')" class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600 transition">Server hinzufügen</button>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold mb-4">Neuen Server anlegen</h3>
        <form action="{{ route('servers.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">IP Adresse</label>
                <input type="text" name="ip_address" class="w-full border p-2 rounded" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="bg-gray-300 px-4 py-2 rounded">Abbrechen</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Speichern</button>
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($servers as $server)
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 {{ $server->status == 'online' ? 'border-green-500' : 'border-red-500' }}">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-xl font-bold">{{ $server->name }}</h2>
                <p class="text-gray-500">{{ $server->ip_address }}</p>
            </div>
            <span class="px-2 py-1 text-xs font-semibold rounded {{ $server->status == 'online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ strtoupper($server->status) }}
            </span>
        </div>
        
        <div class="space-y-2 mb-4">
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span>CPU</span>
                    <span>{{ round($server->cpu_usage) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $server->cpu_usage }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span>RAM</span>
                    <span>{{ round($server->ram_usage) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $server->ram_usage }}%"></div>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t flex justify-between items-center">
            <span class="text-xs text-gray-400">Token: <code class="bg-gray-100 px-1">{{ $server->api_token }}</code></span>
            <a href="{{ route('servers.show', $server) }}" class="text-blue-500 hover:underline text-sm font-semibold">Verwalten →</a>
        </div>
    </div>
    @endforeach
</div>
@endsection
