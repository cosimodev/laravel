<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Internal Academy" />
    <div class="relative min-h-screen overflow-hidden bg-slate-950">
        <!-- Background animated gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-violet-950/50 via-slate-950 to-indigo-950/50"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-violet-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-3xl"></div>

        <!-- Navbar -->
        <nav class="relative z-10 flex items-center justify-between px-6 py-5 sm:px-12">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 shadow-lg shadow-violet-500/25">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="text-lg font-bold text-white">Internal Academy</span>
            </div>
            <div class="flex items-center gap-3">
                <Link v-if="canLogin" :href="route('login')" class="rounded-lg px-5 py-2.5 text-sm font-medium text-slate-300 transition hover:text-white">
                    Accedi
                </Link>
                <Link v-if="canRegister" :href="route('register')" class="rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:shadow-violet-500/40 hover:brightness-110">
                    Registrati
                </Link>
            </div>
        </nav>

        <!-- Hero -->
        <div class="relative z-10 flex min-h-[calc(100vh-80px)] flex-col items-center justify-center px-6 text-center">
            <div class="inline-flex items-center gap-2 rounded-full border border-violet-500/20 bg-violet-500/10 px-4 py-1.5 text-sm text-violet-300 backdrop-blur-sm mb-8">
                <span class="h-1.5 w-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                Piattaforma di formazione aziendale
            </div>

            <h1 class="max-w-4xl text-5xl font-bold tracking-tight text-white sm:text-7xl">
                Cresci insieme al
                <span class="bg-gradient-to-r from-violet-400 via-indigo-400 to-cyan-400 bg-clip-text text-transparent">tuo team</span>
            </h1>

            <p class="mt-6 max-w-2xl text-lg leading-relaxed text-slate-400">
                Organizza workshop, gestisci iscrizioni e monitora la formazione del tuo team.
                Tutto in un unico posto, semplice e intuitivo.
            </p>

            <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                <Link v-if="canRegister" :href="route('register')" class="group relative inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-8 py-4 text-sm font-semibold text-white shadow-2xl shadow-violet-500/25 transition-all hover:shadow-violet-500/40 hover:brightness-110">
                    Inizia gratuitamente
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </Link>
                <Link v-if="canLogin" :href="route('login')" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-700 bg-slate-800/50 px-8 py-4 text-sm font-semibold text-slate-300 backdrop-blur-sm transition hover:border-slate-600 hover:bg-slate-800 hover:text-white">
                    Ho gia un account
                </Link>
            </div>

            <!-- Feature cards -->
            <div class="mt-24 grid max-w-5xl grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-sm transition hover:border-slate-700">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/10 text-violet-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-white">Gestione Workshop</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-400">Crea e organizza workshop con pochi click. Definisci date, durata e capienza.</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-sm transition hover:border-slate-700">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-white">Iscrizioni Smart</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-400">Waiting list automatica con promozione FIFO. Nessun conflitto orario.</p>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur-sm transition hover:border-slate-700">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-400">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-white">Dashboard Live</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-400">Monitora iscrizioni e presenze in tempo reale con statistiche aggiornate.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="relative z-10 border-t border-slate-800 py-8 text-center text-sm text-slate-500">
            Internal Academy &copy; {{ new Date().getFullYear() }} &mdash; Piattaforma di formazione interna
        </footer>
    </div>
</template>
