<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({ stats: Object });
const liveStats = ref(props.stats);
let interval;

onMounted(() => {
    interval = setInterval(async () => {
        try {
            const res = await fetch(route('admin.stats.live'));
            liveStats.value = await res.json();
        } catch (e) {}
    }, 5000);
});
onUnmounted(() => clearInterval(interval));

function maxVal(data) {
    return Math.max(...data.map(w => w.confirmed + w.waiting), 1);
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Title -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                    <p class="mt-1 text-sm text-slate-500">Panoramica della piattaforma in tempo reale</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Workshop totali</p>
                                <p class="mt-2 text-3xl font-bold text-slate-900">{{ liveStats.total_workshops }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                        </div>
                        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-violet-500 to-indigo-500"></div>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Iscrizioni confermate</p>
                                <p class="mt-2 text-3xl font-bold text-emerald-600">{{ liveStats.total_registrations }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Piu popolare</p>
                                <p class="mt-2 text-lg font-bold text-slate-900 truncate max-w-[180px]">{{ liveStats.most_popular || 'Nessuno' }}</p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                        </div>
                        <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-amber-400 to-orange-500"></div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Iscrizioni per workshop</h2>
                            <p class="text-sm text-slate-500">Confermati vs lista d'attesa</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs">
                            <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span> Confermati</span>
                            <span class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span> In attesa</span>
                        </div>
                    </div>

                    <div v-if="liveStats.workshops_data?.length" class="space-y-4">
                        <div v-for="w in liveStats.workshops_data" :key="w.title" class="group flex items-center gap-4">
                            <div class="w-36 truncate text-sm font-medium text-slate-700" :title="w.title">{{ w.title }}</div>
                            <div class="flex flex-1 items-center gap-1 rounded-lg bg-slate-100 p-1">
                                <div class="h-7 rounded-md bg-gradient-to-r from-violet-500 to-indigo-500 transition-all duration-500" :style="{ width: Math.max(w.confirmed / maxVal(liveStats.workshops_data) * 100, w.confirmed > 0 ? 4 : 0) + '%' }"></div>
                                <div class="h-7 rounded-md bg-gradient-to-r from-amber-400 to-orange-400 transition-all duration-500" :style="{ width: Math.max(w.waiting / maxVal(liveStats.workshops_data) * 100, w.waiting > 0 ? 4 : 0) + '%' }"></div>
                            </div>
                            <div class="w-20 text-right text-sm tabular-nums">
                                <span class="font-semibold text-violet-600">{{ w.confirmed }}</span>
                                <span class="text-slate-300 mx-1">/</span>
                                <span class="text-amber-600">{{ w.waiting }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="flex flex-col items-center py-12 text-slate-400">
                        <svg class="h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p class="text-sm">Nessun dato disponibile</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
