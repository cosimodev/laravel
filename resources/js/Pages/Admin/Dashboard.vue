<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    stats: Object,
});

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

function maxValue(data) {
    return Math.max(...data.map(w => w.confirmed + w.waiting), 1);
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard Admin</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="rounded-lg bg-white p-6 shadow">
                        <h3 class="text-sm font-medium text-gray-500">Totale Workshop</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ liveStats.total_workshops }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow">
                        <h3 class="text-sm font-medium text-gray-500">Iscrizioni Confermate</h3>
                        <p class="mt-2 text-3xl font-bold text-green-600">{{ liveStats.total_registrations }}</p>
                    </div>
                    <div class="rounded-lg bg-white p-6 shadow">
                        <h3 class="text-sm font-medium text-gray-500">Piu Popolare</h3>
                        <p class="mt-2 text-xl font-bold text-indigo-600">{{ liveStats.most_popular || '-' }}</p>
                    </div>
                </div>

                <!-- Chart -->
                <div class="mt-8 rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-6 text-lg font-semibold text-gray-900">Iscrizioni per Workshop</h3>
                    <div v-if="liveStats.workshops_data?.length" class="space-y-4">
                        <div v-for="w in liveStats.workshops_data" :key="w.title" class="flex items-center gap-4">
                            <div class="w-40 truncate text-sm text-gray-700" :title="w.title">{{ w.title }}</div>
                            <div class="flex flex-1 gap-1">
                                <div class="h-6 rounded bg-green-500 transition-all" :style="{ width: (w.confirmed / maxValue(liveStats.workshops_data) * 100) + '%' }" :title="'Confermati: ' + w.confirmed"></div>
                                <div class="h-6 rounded bg-yellow-400 transition-all" :style="{ width: (w.waiting / maxValue(liveStats.workshops_data) * 100) + '%' }" :title="'In attesa: ' + w.waiting"></div>
                            </div>
                            <div class="w-24 text-right text-xs text-gray-500">
                                <span class="text-green-600">{{ w.confirmed }}</span> / <span class="text-yellow-600">{{ w.waiting }}</span>
                            </div>
                        </div>
                    </div>
                    <p v-else class="text-center text-gray-500">Nessun dato disponibile.</p>
                    <div class="mt-4 flex gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-green-500"></span> Confermati</span>
                        <span class="flex items-center gap-1"><span class="inline-block h-3 w-3 rounded bg-yellow-400"></span> In attesa</span>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
