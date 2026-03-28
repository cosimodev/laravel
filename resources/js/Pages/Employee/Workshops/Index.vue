<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({ workshops: Object });
</script>

<template>
    <Head title="Workshop Disponibili" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">Workshop disponibili</h1>
                    <p class="mt-1 text-sm text-slate-500">Scopri e iscriviti ai prossimi workshop</p>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <Link v-for="w in workshops.data" :key="w.id" :href="route('employee.workshops.show', w.id)" class="group relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition-all duration-300 hover:border-violet-200 hover:shadow-lg hover:shadow-violet-500/10 hover:-translate-y-1">
                        <!-- Top accent bar -->
                        <div class="h-1 w-full bg-gradient-to-r" :class="w.registrations?.length ? (w.registrations[0].status === 'confirmed' ? 'from-emerald-400 to-teal-500' : 'from-amber-400 to-orange-400') : 'from-violet-400 to-indigo-500'"></div>

                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <h3 class="text-base font-semibold text-slate-900 group-hover:text-violet-600 transition">{{ w.title }}</h3>
                                <span v-if="w.registrations?.length" class="shrink-0 ml-2 rounded-full px-2.5 py-1 text-xs font-semibold" :class="w.registrations[0].status === 'confirmed' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200'">
                                    {{ w.registrations[0].status === 'confirmed' ? 'Iscritto' : '#' + w.registrations[0].position }}
                                </span>
                            </div>

                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-500">{{ w.description }}</p>

                            <div class="mt-5 space-y-2.5">
                                <div class="flex items-center gap-3 text-sm text-slate-600">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ new Date(w.date_time).toLocaleDateString('it-IT', { weekday: 'short', day: '2-digit', month: 'short' }) }} alle {{ new Date(w.date_time).toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' }) }}
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-600">
                                    <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ w.duration_minutes }} minuti
                                </div>
                            </div>

                            <!-- Capacity bar -->
                            <div class="mt-5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-500">{{ w.confirmed_count }} / {{ w.capacity }} posti</span>
                                    <span v-if="w.confirmed_count >= w.capacity" class="font-semibold text-red-500">Completo</span>
                                </div>
                                <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full transition-all duration-500" :class="w.confirmed_count >= w.capacity ? 'bg-red-400' : 'bg-gradient-to-r from-violet-500 to-indigo-500'" :style="{ width: Math.min(w.confirmed_count / w.capacity * 100, 100) + '%' }"></div>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <div v-if="!workshops.data.length" class="flex flex-col items-center rounded-2xl border border-slate-200 bg-white py-16 shadow-sm">
                    <svg class="h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="mt-4 text-base font-medium text-slate-500">Nessun workshop al momento</p>
                    <p class="mt-1 text-sm text-slate-400">Torna presto, nuovi workshop vengono aggiunti regolarmente</p>
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
