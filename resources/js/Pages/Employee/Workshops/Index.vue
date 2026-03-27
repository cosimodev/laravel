<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    workshops: Object,
});
</script>

<template>
    <Head title="Workshop Disponibili" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Workshop Disponibili</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div v-for="workshop in workshops.data" :key="workshop.id" class="overflow-hidden rounded-lg bg-white shadow transition hover:shadow-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">{{ workshop.title }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm text-gray-600">{{ workshop.description }}</p>
                            <div class="mt-4 space-y-2 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ new Date(workshop.date_time).toLocaleString('it-IT') }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ workshop.duration_minutes }} minuti
                                </div>
                                <div class="flex items-center">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ workshop.confirmed_count }} / {{ workshop.capacity }}
                                    <span v-if="workshop.confirmed_count >= workshop.capacity" class="ml-2 text-red-500 font-semibold">PIENO</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <span v-if="workshop.registrations?.length" class="inline-block rounded-full px-3 py-1 text-xs font-semibold" :class="workshop.registrations[0].status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                    {{ workshop.registrations[0].status === 'confirmed' ? 'Iscritto' : 'In attesa (#' + workshop.registrations[0].position + ')' }}
                                </span>
                            </div>

                            <div class="mt-4">
                                <Link :href="route('employee.workshops.show', workshop.id)" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    Dettagli &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="workshops.data.length === 0" class="rounded-lg bg-white p-12 text-center shadow">
                    <p class="text-gray-500">Nessun workshop disponibile al momento.</p>
                </div>

                <div v-if="workshops.links.length > 3" class="mt-6 flex justify-center">
                    <template v-for="link in workshops.links" :key="link.label">
                        <Link v-if="link.url" :href="link.url" class="mx-1 rounded px-3 py-2 text-sm" :class="link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" v-html="link.label" />
                        <span v-else class="mx-1 rounded px-3 py-2 text-sm text-gray-400" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
