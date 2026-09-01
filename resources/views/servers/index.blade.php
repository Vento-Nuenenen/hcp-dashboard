<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-4xl text-slate-900 leading-tight">
                {{ __('Server Übersicht') }}
            </h2>
            <button onclick="document.getElementById('addModal').classList.toggle('hidden')" class="bg-indigo-600 text-white px-10 py-4 rounded-2xl shadow-md hover:bg-indigo-700 transition-all font-bold text-lg active:scale-95">
                + Neuen Server hinzufügen
            </button>
        </div>
    </x-slot>

    <div id="addModal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 transition-all flex items-center justify-center p-4">
        <div class="relative mx-auto p-12 border-0 w-full max-w-5xl shadow-2xl rounded-[3rem] bg-white">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-4xl font-black text-slate-900">Server hinzufügen</h3>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('servers.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">
                    <div>
                        <label class="block text-xl font-black text-slate-700 mb-3 text-left">Anzeigename</label>
                        <input type="text" name="name" class="w-full border border-slate-200 p-5 rounded-2xl text-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-slate-50 shadow-none outline-none" placeholder="z.B. Production-Web-01" required>
                    </div>
                    <div>
                        <label class="block text-xl font-black text-slate-700 mb-3 text-left">IP / Hostname</label>
                        <input type="text" name="ip_address" class="w-full border border-slate-200 p-5 rounded-2xl text-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-slate-50 shadow-none outline-none" placeholder="127.0.0.1" required>
                    </div>
                </div>
                <div class="flex justify-end space-x-6 pt-10 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="bg-slate-100 px-10 py-5 rounded-2xl font-black text-xl hover:bg-slate-200 transition-all text-slate-600">Abbrechen</button>
                    <button type="submit" class="bg-indigo-600 text-white px-12 py-5 rounded-2xl font-black text-xl shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all active:scale-95">Server erstellen</button>
                </div>
            </form>
        </div>
    </div>

    <div class="py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-8">
            @foreach($servers as $server)
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full {{ $server->status == 'online' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $server->name }}</h2>
                        <div class="flex items-center text-slate-400 font-bold">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $server->ip_address }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="flex items-center px-4 py-2 text-xs font-black rounded-full tracking-widest {{ $server->status == 'online' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} border {{ $server->status == 'online' ? 'border-emerald-100' : 'border-rose-100' }}">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $server->status == 'online' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            {{ strtoupper($server->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-8 mb-10">
                    <div>
                        <div class="flex justify-between text-sm font-black text-slate-500 uppercase tracking-widest mb-3">
                            <span>CPU Auslastung</span>
                            <span class="{{ $server->cpu_usage > 80 ? 'text-rose-600' : 'text-slate-900' }}">{{ round($server->cpu_usage) }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden p-0.5 border border-slate-200/50">
                            <div class="{{ $server->cpu_usage > 80 ? 'bg-rose-500' : 'bg-indigo-500' }} h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $server->cpu_usage }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm font-black text-slate-500 uppercase tracking-widest mb-3">
                            <span>RAM Nutzung</span>
                            <span class="{{ $server->ram_usage > 80 ? 'text-rose-600' : 'text-slate-900' }}">{{ round($server->ram_usage) }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden p-0.5 border border-slate-200/50">
                            <div class="{{ $server->ram_usage > 80 ? 'bg-rose-500' : 'bg-indigo-500' }} h-full rounded-full transition-all duration-1000 ease-out" style="width: {{ $server->ram_usage }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-100 flex justify-between items-center">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-2">Security Token</span>
                        <div class="flex items-center space-x-2">
                            <code class="bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg text-xs text-slate-500 font-mono">{{ substr($server->api_token, 0, 8) }}...</code>
                        </div>
                    </div>
                    <a href="{{ route('servers.show', $server) }}" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-sm hover:bg-indigo-600 transition-all shadow-lg hover:shadow-indigo-500/20 active:scale-95">
                        Konfigurieren
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
