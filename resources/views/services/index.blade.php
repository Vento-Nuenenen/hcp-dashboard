<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-5xl text-slate-900 leading-tight tracking-tight">
            Dienste & Software
        </h2>
        <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-xs">Monitoring und Verwaltung der Server-Dienste</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            @foreach($servers as $server)
            <div class="space-y-8">
                <div class="flex items-center justify-between border-b border-slate-100 pb-6">
                    <div class="flex items-center space-x-6">
                        <div class="w-16 h-16 bg-indigo-600 text-white rounded-[1.5rem] flex items-center justify-center shadow-xl shadow-indigo-500/20 font-black text-2xl">
                            {{ substr($server->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-3xl font-black text-slate-900">{{ $server->name }}</h3>
                            <p class="text-slate-400 font-bold tracking-widest text-xs uppercase mt-1">{{ $server->ip_address }} • {{ $server->domains_count }} Domains</p>
                        </div>
                    </div>
                    <a href="{{ route('servers.show', $server) }}" class="px-8 py-3 bg-slate-50 text-slate-400 hover:text-indigo-600 font-black text-sm rounded-2xl border border-slate-100 transition-all active:scale-95">
                        SERVER DETAILS
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-4 gap-8">
                    @php
                        $allPossibleTools = ['nginx', 'php', 'node', 'ruby', 'mysql', 'mariadb', 'postgresql'];
                        $installedToolNames = $server->installed_tools ? array_keys($server->installed_tools) : [];
                        $pendingTools = $server->tools_to_install ?? [];
                    @endphp

                    @foreach($allPossibleTools as $tool)
                        @php
                            $isInstalled = in_array($tool, $installedToolNames);
                            $isPending = in_array($tool, $pendingTools);
                            $toolDetail = $server->installed_tools[$tool] ?? null;
                        @endphp

                        <div class="p-8 rounded-[2.5rem] {{ $isInstalled ? 'bg-white border-slate-100' : 'bg-slate-50/50 border-slate-100 border-dashed' }} border shadow-sm transition-all group overflow-hidden relative">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 {{ $isInstalled ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-400' }} rounded-xl flex items-center justify-center font-black uppercase text-[10px]">
                                        {{ substr($tool, 0, 2) }}
                                    </div>
                                    <span class="text-xl font-black capitalize text-slate-900">{{ $tool }}</span>
                                </div>
                                @if(!$isInstalled)
                                    @if($isPending)
                                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest">Warten...</span>
                                    @else
                                        <form action="{{ route('servers.install-tool', $server) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="tool" value="{{ $tool }}">
                                            <button type="submit" class="text-[9px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest">Installieren</button>
                                        </form>
                                    @endif
                                @else
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                @endif
                            </div>
                            
                            @if($isInstalled && $toolDetail)
                                <div class="space-y-2">
                                    @php
                                        $versions = is_array($toolDetail) && isset($toolDetail['versions']) ? $toolDetail['versions'] : [];
                                    @endphp
                                    @foreach(array_slice($versions, 0, 2) as $version)
                                    <div class="flex items-center justify-between text-[10px] font-black">
                                        <span class="text-slate-500">{{ $version['version'] }}</span>
                                        @if(isset($version['is_up_to_date']))
                                            <span class="{{ $version['is_up_to_date'] ? 'text-emerald-500' : 'text-amber-500' }}">●</span>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[10px] text-slate-400 font-bold italic">Nicht installiert</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- System Services Table for this server -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-slate-50">
                            @foreach($server->services->take(3) as $service)
                            <tr class="group">
                                <td class="p-6 text-lg font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $service->name }}</td>
                                <td class="p-6 text-right">
                                    <span class="inline-flex items-center px-4 py-1.5 text-[9px] font-black rounded-full tracking-widest {{ $service->status == 'running' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} border {{ $service->status == 'running' ? 'border-emerald-100' : 'border-rose-100' }}">
                                        {{ strtoupper($service->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
