<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ workshop: Object });
</script>

<template>
    <Head :title="workshop.title" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-start justify-between">
                    <div>
                        <Link :href="route('admin.workshops.index')" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Workshop
                        </Link>
                        <h1 class="mt-3 text-2xl font-bold text-slate-900">{{ workshop.title }}</h1>
                    </div>
                    <Link :href="route('admin.workshops.edit', workshop.id)" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Modifica
                    </Link>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Info card -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Dettagli</h2>
                        <dl class="mt-5 space-y-4">
                            <div>
                                <dt class="text-xs font-medium text-slate-500">Data e ora</dt>
                                <dd class="mt-0.5 text-sm font-medium text-slate-900">{{ new Date(workshop.date_time).toLocaleString('it-IT', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-slate-500">Durata</dt>
                                <dd class="mt-0.5 text-sm font-medium text-slate-900">{{ workshop.duration_minutes }} minuti</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-slate-500">Posti occupati</dt>
                                <dd class="mt-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-slate-900">{{ workshop.confirmed_count }} / {{ workshop.capacity }}</span>
                                        <span v-if="workshop.waiting_count > 0" class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">+{{ workshop.waiting_count }} in attesa</span>
                                    </div>
                                    <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full bg-gradient-to-r from-violet-500 to-indigo-500 transition-all" :style="{ width: Math.min(workshop.confirmed_count / workshop.capacity * 100, 100) + '%' }"></div>
                                    </div>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-slate-500">Creato da</dt>
                                <dd class="mt-0.5 text-sm text-slate-900">{{ workshop.creator?.name }}</dd>
                            </div>
                        </dl>
                        <div class="mt-5 border-t border-slate-100 pt-5">
                            <dt class="text-xs font-medium text-slate-500">Descrizione</dt>
                            <dd class="mt-1.5 text-sm leading-relaxed text-slate-700 whitespace-pre-wrap">{{ workshop.description }}</dd>
                        </div>
                    </div>

                    <!-- Participants -->
                    <div class="col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-6 py-4">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Partecipanti ({{ workshop.registrations?.length || 0 }})</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Utente</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Stato</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Posizione</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <tr v-for="reg in workshop.registrations" :key="reg.id">
                                        <td class="px-6 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 text-xs font-bold text-white">{{ reg.user.name.charAt(0).toUpperCase() }}</div>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-900">{{ reg.user.name }}</div>
                                                    <div class="text-xs text-slate-400">{{ reg.user.email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold" :class="reg.status === 'confirmed' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'">
                                                <span class="h-1.5 w-1.5 rounded-full" :class="reg.status === 'confirmed' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                                {{ reg.status === 'confirmed' ? 'Confermato' : 'In attesa' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-sm text-slate-500">{{ reg.position ?? '-' }}</td>
                                    </tr>
                                    <tr v-if="!workshop.registrations?.length">
                                        <td colspan="3" class="px-6 py-12 text-center text-sm text-slate-400">Nessun iscritto a questo workshop</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
