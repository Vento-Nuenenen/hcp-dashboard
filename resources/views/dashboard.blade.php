<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-4xl text-slate-900 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm border border-slate-100 rounded-[3rem] p-16 relative">
                <div class="absolute top-0 right-0 p-16 opacity-5 pointer-events-none">
                    <svg class="w-64 h-64 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                </div>
                <div class="relative z-10">
                    <div class="text-5xl font-black text-slate-900 mb-8 tracking-tight">
                        Willkommen zurück,<br>
                        <span class="text-indigo-600">{{ Auth::user()->name }}!</span>
                    </div>
                    <p class="text-2xl text-slate-500 font-bold mb-12 max-w-2xl leading-relaxed">
                        Verwalten Sie Ihre Server-Infrastruktur, Domains und Dienste mit Leichtigkeit über Ihr zentrales Kontrollzentrum.
                    </p>
                    <div class="flex flex-wrap gap-6">
                        <a href="{{ route('servers.index') }}" class="inline-flex items-center bg-indigo-600 text-white px-12 py-6 rounded-3xl text-xl font-black shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all active:scale-95">
                            Server-Flotte verwalten
                            <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center bg-slate-100 text-slate-600 px-12 py-6 rounded-3xl text-xl font-black hover:bg-slate-200 transition-all active:scale-95">
                            Profil anpassen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
