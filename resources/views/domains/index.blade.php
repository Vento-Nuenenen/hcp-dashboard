<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-5xl text-slate-900 leading-tight tracking-tight">
            Domains & Subdomains
        </h2>
        <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-xs">Zentrale Verwaltung aller Webseiten</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <!-- Add Domain Section -->
            <div class="bg-white p-12 rounded-[3rem] border border-slate-100 shadow-sm">
                <h3 class="text-2xl font-black mb-8 text-slate-900 flex items-center">
                    <span class="mr-4 bg-indigo-50 p-3 rounded-2xl text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </span>
                    Neue Domain hinzufügen
                </h3>
                <form action="{{ route('dashboard') }}" method="POST" id="global-domain-form" class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-6">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="domain_name" placeholder="z.B. mein-projekt.de" class="w-full border border-slate-200 p-8 rounded-[2rem] text-4xl font-black focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-slate-50 shadow-none outline-none" required>
                    </div>
                    <div class="w-full lg:w-72">
                        <select name="server_id" id="server_select" class="w-full border border-slate-200 p-6 rounded-[1.5rem] text-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-slate-50 shadow-none outline-none appearance-none" required onchange="updateFormAction(this.value)">
                            <option value="">Server wählen...</option>
                            @foreach($allServers as $server)
                                <option value="{{ $server->id }}">{{ $server->name }} ({{ $server->ip_address }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-12 py-6 rounded-[1.5rem] text-xl font-black hover:bg-indigo-700 shadow-xl shadow-indigo-500/20 transition-all active:scale-95 whitespace-nowrap">
                        Domain erstellen
                    </button>
                </form>
                <script>
                    function updateFormAction(serverId) {
                        const form = document.getElementById('global-domain-form');
                        if (serverId) {
                            form.action = `/servers/${serverId}/domains`;
                        } else {
                            form.action = "{{ route('dashboard') }}";
                        }
                    }
                </script>
            </div>

            <!-- Domains List -->
            <div class="grid grid-cols-1 gap-10">
                @foreach($domains as $domain)
                <div class="bg-white border border-slate-100 rounded-[3rem] p-10 hover:shadow-2xl transition-all duration-500 group" x-data="{ editDomain: false }">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <div class="flex items-center space-x-4 mb-2">
                                <h3 class="text-4xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $domain->domain_name }}</h3>
                                <span class="px-4 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full border border-indigo-100 uppercase tracking-widest">
                                    {{ $domain->server->name }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if($domain->assigned_tools)
                                    @foreach($domain->assigned_tools as $tool => $version)
                                        @if($version)
                                        <span class="px-4 py-1.5 bg-slate-50 text-slate-400 text-[10px] font-black rounded-full border border-slate-100 shadow-sm uppercase tracking-widest">
                                            {{ $tool }}: {{ $version }}
                                        </span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="editDomain = true" class="p-4 bg-slate-50 text-slate-400 hover:text-indigo-600 rounded-2xl shadow-sm border border-slate-100 transition-all active:scale-95">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('domains.destroy', $domain) }}" method="POST" onsubmit="return confirm('Domain wirklich löschen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-4 bg-slate-50 text-slate-400 hover:text-rose-600 rounded-2xl shadow-sm border border-slate-100 transition-all active:scale-95">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Domain Modal (Same as in show.blade.php) -->
                    <div x-show="editDomain" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 transition-all flex items-center justify-center p-4" x-cloak>
                        <div class="relative mx-auto p-12 border-0 w-full max-w-5xl shadow-2xl rounded-[3rem] bg-white" @click.away="editDomain = false">
                            <div class="flex justify-between items-center mb-10">
                                <h3 class="text-4xl font-black text-slate-900 truncate pr-10">Domain bearbeiten</h3>
                                <button @click="editDomain = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <form action="{{ route('domains.update', $domain) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10 text-left">
                                    <div>
                                        <label class="block text-xl font-black text-slate-700 mb-3">Domain Name</label>
                                        <input type="text" name="domain_name" value="{{ $domain->domain_name }}" class="w-full border border-slate-200 p-8 rounded-2xl text-3xl font-black focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-slate-50 shadow-none outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-xl font-black text-slate-700 mb-3">Server zuweisen</label>
                                        <select name="server_id" class="w-full border border-slate-200 p-5 rounded-2xl text-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-slate-50 shadow-none outline-none appearance-none">
                                            @foreach($allServers as $s)
                                                <option value="{{ $s->id }}" {{ $domain->server_id == $s->id ? 'selected' : '' }}>{{ $s->name }} ({{ $s->ip_address }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-12 text-left">
                                    <label class="block text-2xl font-black text-slate-900 mb-8 flex items-center">
                                        <span class="mr-4 bg-amber-50 p-3 rounded-2xl text-amber-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a2 2 0 002 2h3a2 2 0 012 2v3a2 2 0 002 2h1a2 2 0 110 4h-1a2 2 0 00-2 2v3a2 2 0 01-2 2h-3a2 2 0 00-2 2v1a2 2 0 11-4 0v-1a2 2 0 00-2-2H7a2 2 0 01-2-2v-3a2 2 0 00-2-2H4a2 2 0 110-4h1a2 2 0 002-2V7a2 2 0 012-2h3a2 2 0 002-2V4z"></path></svg>
                                        </span>
                                        Tools & Versionen zuweisen
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                        @if($domain->server->installed_tools)
                                            @foreach($domain->server->installed_tools as $toolName => $toolDetail)
                                            <div class="p-8 rounded-[2rem] bg-slate-50 border border-slate-100 shadow-sm group/tool transition-all">
                                                <div class="flex items-center space-x-3 mb-6">
                                                    <span class="text-xl font-black capitalize text-slate-900 group-hover/tool:text-indigo-600 transition-colors">{{ $toolName }}</span>
                                                </div>
                                                <div class="relative">
                                                    <select name="assigned_tools[{{ $toolName }}]" class="w-full border border-slate-200 p-4 rounded-xl text-sm font-bold focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-white shadow-none appearance-none">
                                                        <option value="">Nicht aktiv</option>
                                                        @php
                                                            $versions = is_array($toolDetail) && isset($toolDetail['versions']) ? $toolDetail['versions'] : [];
                                                            $currentAssigned = $domain->assigned_tools[$toolName] ?? '';
                                                        @endphp
                                                        @foreach($versions as $v)
                                                            <option value="{{ $v['version'] }}" {{ $currentAssigned == $v['version'] ? 'selected' : '' }}>
                                                                Version {{ $v['version'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="col-span-full p-8 rounded-2xl bg-slate-50 border border-dashed border-slate-200 text-slate-400 italic font-bold text-left">
                                                Keine Tools auf diesem Server verfügbar.
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-6 pt-10 border-t border-slate-100">
                                    <button type="button" @click="editDomain = false" class="bg-slate-100 px-12 py-5 rounded-2xl font-black text-xl hover:bg-slate-200 transition-all text-slate-600">Abbrechen</button>
                                    <button type="submit" class="bg-indigo-600 text-white px-14 py-5 rounded-2xl font-black text-xl shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all active:scale-95">Änderungen speichern</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Subdomains list -->
                    <div class="mt-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($domain->subdomains as $subdomain)
                            <div class="flex items-center bg-slate-50 border border-slate-100 p-6 rounded-[2rem] shadow-sm group/sub transition-all hover:bg-white hover:shadow-md" x-data="{ editSub: false }">
                                <div class="flex-1 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-xl font-black text-slate-900">
                                            <span class="text-indigo-600">{{ $subdomain->subdomain_name }}</span>.{{ $domain->domain_name }}
                                        </span>
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @if($subdomain->assigned_tools)
                                                @foreach($subdomain->assigned_tools as $tool => $version)
                                                    @if($version)
                                                    <span class="px-2 py-0.5 bg-white text-slate-400 text-[8px] font-black rounded-full border border-slate-100 uppercase tracking-tighter">
                                                        {{ $tool }}: {{ $version }}
                                                    </span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-2 opacity-0 group-hover/sub:opacity-100 transition-opacity">
                                        <button @click="editSub = true" class="p-2 text-slate-300 hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('subdomains.destroy', $subdomain) }}" method="POST" onsubmit="return confirm('Subdomain wirklich löschen?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Edit Subdomain Modal -->
                                <div x-show="editSub" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-[60] flex items-center justify-center p-4" x-cloak>
                                    <div class="relative mx-auto p-12 border-0 w-full max-w-5xl shadow-2xl rounded-[3rem] bg-white" @click.away="editSub = false">
                                        <div class="flex justify-between items-center mb-10">
                                            <h3 class="text-4xl font-black text-slate-900 truncate pr-10 text-left">Subdomain bearbeiten</h3>
                                            <button @click="editSub = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        <form action="{{ route('subdomains.update', $subdomain) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="mb-12 text-left text-left">
                                                <label class="block text-xl font-black text-slate-700 mb-4">Präfix</label>
                                                <div class="relative text-left">
                                                    <input type="text" name="subdomain_name" value="{{ $subdomain->subdomain_name }}" class="w-full border border-slate-200 p-6 rounded-2xl text-2xl font-black focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-slate-50 shadow-none outline-none" required>
                                                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 font-black text-2xl">.{{ $domain->domain_name }}</span>
                                                </div>
                                            </div>

                                            <div class="mb-12 text-left text-left">
                                                <label class="block text-2xl font-black text-slate-900 mb-8 flex items-center">
                                                    <span class="mr-4 bg-amber-50 p-3 rounded-2xl text-amber-600">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a2 2 0 002 2h3a2 2 0 012 2v3a2 2 0 002 2h1a2 2 0 110 4h-1a2 2 0 00-2 2v3a2 2 0 01-2 2h-3a2 2 0 00-2 2v1a2 2 0 11-4 0v-1a2 2 0 00-2-2H7a2 2 0 01-2-2v-3a2 2 0 00-2-2H4a2 2 0 110-4h1a2 2 0 002-2V7a2 2 0 012-2h3a2 2 0 002-2V4z"></path></svg>
                                                    </span>
                                                    Tools für Subdomain
                                                </label>
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                                    @if($domain->server->installed_tools)
                                                        @foreach($domain->server->installed_tools as $toolName => $toolDetail)
                                                        <div class="p-8 rounded-[2rem] bg-slate-50 border border-slate-100 shadow-sm transition-all text-left">
                                                            <div class="flex items-center space-x-3 mb-6 text-left">
                                                                <span class="text-xl font-black capitalize text-slate-900 text-left">{{ $toolName }}</span>
                                                            </div>
                                                            <select name="assigned_tools[{{ $toolName }}]" class="w-full border border-slate-200 p-4 rounded-xl text-sm font-bold focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all bg-white shadow-none appearance-none">
                                                                <option value="">Nicht aktiv</option>
                                                                @php
                                                                    $versions = is_array($toolDetail) && isset($toolDetail['versions']) ? $toolDetail['versions'] : [];
                                                                    $currentAssigned = $subdomain->assigned_tools[$toolName] ?? '';
                                                                @endphp
                                                                @foreach($versions as $v)
                                                                    <option value="{{ $v['version'] }}" {{ $currentAssigned == $v['version'] ? 'selected' : '' }}>
                                                                        Version {{ $v['version'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex justify-end space-x-6 pt-10 border-t border-slate-100">
                                                <button type="button" @click="editSub = false" class="bg-slate-100 px-12 py-5 rounded-2xl font-black text-xl hover:bg-slate-200 transition-all text-slate-600">Abbrechen</button>
                                                <button type="submit" class="bg-indigo-600 text-white px-14 py-5 rounded-2xl font-black text-xl shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all active:scale-95">Speichern</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Inline add subdomain form -->
                            <form action="{{ route('subdomains.store', $domain) }}" method="POST" class="flex items-center space-x-4 p-4 border border-slate-100 border-dashed rounded-[2.5rem] bg-slate-50/30">
                                @csrf
                                <input type="text" name="subdomain_name" placeholder="dev" class="flex-1 border-none p-6 rounded-2xl text-2xl font-black focus:ring-0 bg-transparent shadow-none outline-none" required>
                                <button type="submit" class="bg-white text-indigo-600 p-6 rounded-2xl shadow-sm border border-slate-100 hover:bg-indigo-600 hover:text-white transition-all active:scale-95">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
