<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({ workshop: Object, myRegistration: Object });

function register() { router.post(route('employee.workshops.register', props.workshop.id)); }
function cancel() {
    if (confirm('Vuoi cancellare la tua registrazione?')) {
        router.delete(route('employee.registrations.destroy', props.myRegistration.id));
    }
}
</script>

<template>
    <Head :title="workshop.title" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <Link :href="route('employee.workshops.index')" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Tutti i workshop
                </Link>

                <div class="mt-4 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <!-- Top accent -->
                    <div class="h-1.5 w-full bg-gradient-to-r from-violet-500 via-indigo-500 to-cyan-500"></div>

                    <div class="p-6 sm:p-8">
                        <h1 class="text-2xl font-bold text-slate-900">{{ workshop.title }}</h1>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600 whitespace-pre-wrap">{{ workshop.description }}</p>

                        <!-- Info grid -->
                        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-xs font-medium text-slate-500">Data</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">{{ new Date(workshop.date_time).toLocaleDateString('it-IT', { day: '2-digit', month: 'short' }) }}</p>
                                <p class="text-xs text-slate-500">{{ new Date(workshop.date_time).toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' }) }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-xs font-medium text-slate-500">Durata</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">{{ workshop.duration_minutes }} min</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-xs font-medium text-slate-500">Confermati</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">{{ workshop.confirmed_count }} / {{ workshop.capacity }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4">
                                <p class="text-xs font-medium text-slate-500">In attesa</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">{{ workshop.waiting_count }}</p>
                            </div>
                        </div>

                        <!-- Capacity bar -->
                        <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full transition-all duration-500" :class="workshop.confirmed_count >= workshop.capacity ? 'bg-red-400' : 'bg-gradient-to-r from-violet-500 to-indigo-500'" :style="{ width: Math.min(workshop.confirmed_count / workshop.capacity * 100, 100) + '%' }"></div>
                        </div>

                        <!-- Action -->
                        <div class="mt-8 border-t border-slate-100 pt-6">
                            <div v-if="myRegistration" class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full" :class="myRegistration.status === 'confirmed' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'">
                                        <svg v-if="myRegistration.status === 'confirmed'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ myRegistration.status === 'confirmed' ? 'Sei iscritto!' : 'Sei in lista d\'attesa' }}</p>
                                        <p class="text-xs text-slate-500">{{ myRegistration.status === 'confirmed' ? 'La tua partecipazione e confermata' : 'Posizione #' + myRegistration.position + ' — verrai notificato se si libera un posto' }}</p>
                                    </div>
                                </div>
                                <button @click="cancel" class="shrink-0 rounded-xl border border-red-200 px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                                    Cancella iscrizione
                                </button>
                            </div>
                            <div v-else>
                                <button @click="register" class="w-full rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:shadow-violet-500/40 hover:brightness-110">
                                    {{ workshop.confirmed_count >= workshop.capacity ? 'Iscriviti in lista d\'attesa' : 'Iscriviti al workshop' }}
                                </button>
                                <p v-if="workshop.confirmed_count >= workshop.capacity" class="mt-2 text-center text-xs text-slate-400">I posti sono esauriti. Verrai messo in lista d'attesa.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
