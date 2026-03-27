<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ workshops: Object });

function destroy(id) {
    if (confirm('Sei sicuro di voler eliminare questo workshop?')) {
        router.delete(route('admin.workshops.destroy', id));
    }
}
</script>

<template>
    <Head title="Workshop" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Workshop</h1>
                        <p class="mt-1 text-sm text-slate-500">Gestisci i workshop della piattaforma</p>
                    </div>
                    <Link :href="route('admin.workshops.create')" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:shadow-violet-500/40 hover:brightness-110">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Nuovo workshop
                    </Link>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Workshop</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Data</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Durata</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Iscrizioni</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="w in workshops.data" :key="w.id" class="transition hover:bg-slate-50/50">
                                <td class="px-6 py-4">
                                    <Link :href="route('admin.workshops.show', w.id)" class="font-semibold text-slate-900 hover:text-violet-600 transition">{{ w.title }}</Link>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ w.capacity }} posti</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ new Date(w.date_time).toLocaleDateString('it-IT', { day: '2-digit', month: 'short', year: 'numeric' }) }}<br><span class="text-xs text-slate-400">{{ new Date(w.date_time).toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' }) }}</span></td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ w.duration_minutes }} min</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            {{ w.confirmed_count }}
                                        </span>
                                        <span v-if="w.waiting_count > 0" class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            {{ w.waiting_count }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Link :href="route('admin.workshops.edit', w.id)" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-violet-600" title="Modifica">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </Link>
                                        <button @click="destroy(w.id)" class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-600" title="Elimina">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!workshops.data.length">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <p class="mt-3 text-sm font-medium text-slate-500">Nessun workshop ancora</p>
                                    <p class="mt-1 text-sm text-slate-400">Crea il primo workshop per iniziare</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="workshops.links.length > 3" class="mt-6 flex justify-center gap-1">
                    <template v-for="link in workshops.links" :key="link.label">
                        <Link v-if="link.url" :href="link.url" class="rounded-lg px-3 py-2 text-sm font-medium transition" :class="link.active ? 'bg-violet-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" v-html="link.label" />
                        <span v-else class="px-3 py-2 text-sm text-slate-300" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
