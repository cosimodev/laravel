<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    workshops: Object,
});

function destroy(id) {
    if (confirm('Sei sicuro di voler eliminare questo workshop?')) {
        router.delete(route('admin.workshops.destroy', id));
    }
}
</script>

<template>
    <Head title="Workshop" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Workshop</h2>
                <Link :href="route('admin.workshops.create')" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Nuovo Workshop
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Titolo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Durata</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Posti</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Iscritti</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="workshop in workshops.data" :key="workshop.id">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <Link :href="route('admin.workshops.show', workshop.id)" class="font-medium text-indigo-600 hover:text-indigo-900">
                                        {{ workshop.title }}
                                    </Link>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ new Date(workshop.date_time).toLocaleString('it-IT') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ workshop.duration_minutes }} min
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ workshop.capacity }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span class="text-green-600">{{ workshop.confirmed_count }} confermati</span>
                                    <span v-if="workshop.waiting_count > 0" class="ml-2 text-orange-500">{{ workshop.waiting_count }} in attesa</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <Link :href="route('admin.workshops.edit', workshop.id)" class="mr-3 text-indigo-600 hover:text-indigo-900">Modifica</Link>
                                    <button @click="destroy(workshop.id)" class="text-red-600 hover:text-red-900">Elimina</button>
                                </td>
                            </tr>
                            <tr v-if="workshops.data.length === 0">
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Nessun workshop trovato.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
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
