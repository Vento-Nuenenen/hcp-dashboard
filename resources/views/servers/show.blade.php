<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('servers.index') }}" class="flex items-center text-indigo-600 hover:text-indigo-800 font-black text-sm mb-4 transition-colors group">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                    ZURÜCK ZUR ÜBERSICHT
                </a>
                <h2 class="font-black text-5xl text-slate-900 leading-tight tracking-tight">
                    {{ $server->name }}
                </h2>
                <div class="flex items-center text-slate-400 font-bold mt-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    {{ $server->ip_address }}
                </div>
            </div>
            <div class="flex flex-col items-end">
                <span class="flex items-center px-6 py-3 text-sm font-black rounded-full tracking-widest {{ $server->status == 'online' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} border {{ $server->status == 'online' ? 'border-emerald-100' : 'border-rose-100' }} shadow-sm">
                    <span class="w-3 h-3 rounded-full mr-3 {{ $server->status == 'online' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                    {{ strtoupper($server->status) }}
                </span>
                <span class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-3">Letztes Update: {{ $server->last_seen_at ? $server->last_seen_at->diffForHumans() : 'Nie' }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <!-- Resource Usage & System Info -->
            <div class="grid grid-cols-1 2xl:grid-cols-3 gap-12">
                <!-- Auslastung -->
                <div class="2xl:col-span-2 bg-white p-12 rounded-[3rem] border border-slate-100 shadow-sm">
                    <h2 class="text-4xl font-black mb-12 text-slate-900 flex items-center">
                        <span class="mr-6 bg-indigo-50 p-4 rounded-3xl text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </span>
                        Server-Status & Ressourcen
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div>
                            <div class="flex justify-between text-sm font-black text-slate-500 uppercase tracking-widest mb-4">
                                <span>CPU Auslastung</span>
                                <span class="{{ $server->cpu_usage > 80 ? 'text-rose-600' : 'text-slate-900' }} text-xl">{{ round($server->cpu_usage) }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-6 overflow-hidden p-1 border border-slate-200/50">
                                <div class="{{ $server->cpu_usage > 80 ? 'bg-rose-500' : 'bg-indigo-500' }} h-full rounded-full transition-all duration-1000 ease-out shadow-inner" style="width: {{ $server->cpu_usage }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm font-black text-slate-500 uppercase tracking-widest mb-4">
                                <span>RAM Nutzung</span>
                                <span class="{{ $server->ram_usage > 80 ? 'text-rose-600' : 'text-slate-900' }} text-xl">{{ round($server->ram_usage) }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-6 overflow-hidden p-1 border border-slate-200/50">
                                <div class="{{ $server->ram_usage > 80 ? 'bg-rose-500' : 'bg-indigo-500' }} h-full rounded-full transition-all duration-1000 ease-out shadow-inner" style="width: {{ $server->ram_usage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 pt-12 border-t border-slate-50">
                        <div class="group">
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-[0.2em] mb-2">Betriebssystem</p>
                            <p class="text-2xl font-black text-slate-900">{{ $server->os ?? 'Unbekannt' }}</p>
                        </div>
                        <div class="group">
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-[0.2em] mb-2">Architektur</p>
                            <p class="text-2xl font-black text-slate-900">{{ $server->architecture ?? 'Unbekannt' }}</p>
                        </div>
                        <div class="group">
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-[0.2em] mb-2">Paket-Manager</p>
                            <p class="text-2xl font-black text-slate-900">{{ $server->package_manager ?? 'Unbekannt' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Client Config -->
                <div class="bg-slate-900 p-12 rounded-[3rem] shadow-2xl text-white">
                    <h2 class="text-3xl font-black mb-8 flex items-center text-indigo-400">
                        <span class="mr-4 bg-indigo-500/20 p-3 rounded-2xl text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        </span>
                        Verbindung
                    </h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">API URL</label>
                            <div class="bg-slate-800 border border-slate-700 p-4 rounded-xl font-mono text-indigo-300 text-xs break-all">
                                {{ request()->getSchemeAndHttpHost() }}/api
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Token</label>
                            <div class="bg-slate-800 border border-slate-700 p-4 rounded-xl font-mono text-emerald-400 text-xs break-all">
                                {{ $server->api_token }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shortcuts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <a href="{{ route('domains.index') }}" class="bg-white p-12 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        <span class="text-4xl font-black text-slate-200 group-hover:text-indigo-100 transition-colors">{{ $server->domains->count() }}</span>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 mb-2">Domains verwalten</h3>
                    <p class="text-slate-400 font-bold">Konfiguriere Webseiten und Subdomains für diesen Server.</p>
                </a>

                <a href="{{ route('services.index') }}" class="bg-white p-12 rounded-[3rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-16 h-16 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 mb-2">Dienste & Software</h3>
                    <p class="text-slate-400 font-bold">Installiere neue Runtimes oder überwache Systemdienste.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
