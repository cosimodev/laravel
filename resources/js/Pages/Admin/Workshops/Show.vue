<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    workshop: Object,
});
</script>

<template>
    <Head :title="workshop.title" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ workshop.title }}</h2>
                <Link :href="route('admin.workshops.edit', workshop.id)" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Modifica
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Info -->
                    <div class="col-span-1 rounded-lg bg-white p-6 shadow">
                        <h3 class="text-lg font-semibold text-gray-900">Dettagli</h3>
                        <dl class="mt-4 space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Data</dt>
                                <dd class="text-sm text-gray-900">{{ new Date(workshop.date_time).toLocaleString('it-IT') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Durata</dt>
                                <dd class="text-sm text-gray-900">{{ workshop.duration_minutes }} minuti</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Capienza</dt>
                                <dd class="text-sm text-gray-900">{{ workshop.confirmed_count }} / {{ workshop.capacity }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">In attesa</dt>
                                <dd class="text-sm text-gray-900">{{ workshop.waiting_count }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Creato da</dt>
                                <dd class="text-sm text-gray-900">{{ workshop.creator?.name }}</dd>
                            </div>
                        </dl>
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-500">Descrizione</h4>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ workshop.description }}</p>
                        </div>
                    </div>

                    <!-- Participants -->
                    <div class="col-span-2 rounded-lg bg-white p-6 shadow">
                        <h3 class="text-lg font-semibold text-gray-900">Partecipanti</h3>
                        <table class="mt-4 min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Nome</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Email</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Stato</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500">Posizione</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="reg in workshop.registrations" :key="reg.id">
                                    <td class="px-4 py-2 text-sm">{{ reg.user.name }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ reg.user.email }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold" :class="reg.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                            {{ reg.status === 'confirmed' ? 'Confermato' : 'In attesa' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ reg.position ?? '-' }}</td>
                                </tr>
                                <tr v-if="!workshop.registrations?.length">
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">Nessun iscritto.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
