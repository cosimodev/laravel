<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen">
        <!-- Left panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-between bg-slate-950 p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(124,58,237,0.15),transparent_50%),radial-gradient(ellipse_at_bottom_right,rgba(99,102,241,0.12),transparent_50%)]"></div>

            <!-- Separator line -->
            <div class="absolute right-0 top-0 bottom-0 w-px bg-gradient-to-b from-violet-500/0 via-violet-500/20 to-indigo-500/0"></div>

            <div class="relative z-10">
                <Link href="/" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 shadow-lg shadow-violet-500/25">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-lg font-bold text-white">Internal Academy</span>
                </Link>
            </div>

            <div class="relative z-10 space-y-6">
                <h2 class="text-4xl font-black tracking-tight text-white leading-[1.15]">
                    La formazione del team<br>
                    inizia <span class="bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">da qui.</span>
                </h2>
                <p class="max-w-md text-base leading-relaxed text-slate-400">
                    Workshop interni, iscrizioni smart e monitoraggio in tempo reale.
                    Tutto quello che serve per far crescere il tuo team.
                </p>
                <div class="flex items-center gap-6 pt-4">
                    <div class="text-center">
                        <p class="text-2xl font-black text-white">150+</p>
                        <p class="text-xs text-slate-500 mt-1">Partecipanti</p>
                    </div>
                    <div class="h-8 w-px bg-slate-800"></div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-white">24</p>
                        <p class="text-xs text-slate-500 mt-1">Workshop</p>
                    </div>
                    <div class="h-8 w-px bg-slate-800"></div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-white">98%</p>
                        <p class="text-xs text-slate-500 mt-1">Soddisfazione</p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-sm text-slate-600">
                Internal Academy &copy; {{ new Date().getFullYear() }}
            </div>
        </div>

        <!-- Right panel - Form -->
        <div class="flex w-full flex-col justify-center px-6 py-12 lg:w-1/2 bg-gradient-to-b from-white to-slate-50/50">
            <!-- Mobile logo -->
            <div class="mb-10 lg:hidden">
                <Link href="/" class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-lg font-bold text-slate-900">Internal Academy</span>
                </Link>
            </div>

            <div class="mx-auto w-full max-w-md">
                <slot />
            </div>
        </div>
    </div>
</template>
