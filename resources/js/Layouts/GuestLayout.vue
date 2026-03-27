<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen">
        <!-- Left panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-between bg-slate-950 p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-950/40 via-slate-950 to-indigo-950/40"></div>
            <div class="absolute top-20 -left-20 w-[500px] h-[500px] bg-violet-600/8 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-0 w-[400px] h-[400px] bg-indigo-600/8 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <Link href="/" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 shadow-lg shadow-violet-500/25">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-lg font-bold text-white">Internal Academy</span>
                </Link>
            </div>

            <div class="relative z-10">
                <h2 class="text-4xl font-bold tracking-tight text-white leading-tight">
                    La formazione del team<br>
                    inizia <span class="bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">da qui.</span>
                </h2>
                <p class="mt-4 max-w-md text-base leading-relaxed text-slate-400">
                    Workshop interni, iscrizioni smart e monitoraggio in tempo reale.
                    Tutto quello che serve per far crescere il tuo team.
                </p>
            </div>

            <div class="relative z-10 text-sm text-slate-600">
                Internal Academy &copy; {{ new Date().getFullYear() }}
            </div>
        </div>

        <!-- Right panel - Form -->
        <div class="flex w-full flex-col justify-center px-6 py-12 lg:w-1/2 bg-white">
            <!-- Mobile logo -->
            <div class="mb-10 lg:hidden">
                <Link href="/" class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-lg font-bold text-slate-900">Internal Academy</span>
                </Link>
            </div>

            <div class="mx-auto w-full max-w-sm">
                <slot />
            </div>
        </div>
    </div>
</template>
