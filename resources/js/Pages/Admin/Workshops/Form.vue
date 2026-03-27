<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({ workshop: { type: Object, default: null } });
const isEditing = !!props.workshop;

const form = useForm({
    title: props.workshop?.title ?? '',
    description: props.workshop?.description ?? '',
    date_time: props.workshop?.date_time ? props.workshop.date_time.slice(0, 16) : '',
    duration_minutes: props.workshop?.duration_minutes ?? 60,
    capacity: props.workshop?.capacity ?? 20,
});

function submit() {
    isEditing ? form.put(route('admin.workshops.update', props.workshop.id)) : form.post(route('admin.workshops.store'));
}
</script>

<template>
    <Head :title="isEditing ? 'Modifica Workshop' : 'Nuovo Workshop'" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <Link :href="route('admin.workshops.index')" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-700 transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Torna alla lista
                    </Link>
                    <h1 class="mt-3 text-2xl font-bold text-slate-900">{{ isEditing ? 'Modifica workshop' : 'Nuovo workshop' }}</h1>
                    <p class="mt-1 text-sm text-slate-500">{{ isEditing ? 'Aggiorna i dettagli del workshop' : 'Compila i campi per creare un nuovo workshop' }}</p>
                </div>

                <form @submit.prevent="submit" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700">Titolo</label>
                        <input id="title" v-model="form.title" type="text" required class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm shadow-sm transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20" placeholder="Es: Introduzione a Vue 3" />
                        <InputError :message="form.errors.title" class="mt-1.5" />
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Descrizione</label>
                        <textarea id="description" v-model="form.description" rows="4" required class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm shadow-sm transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20" placeholder="Descrivi il workshop, gli argomenti trattati..." />
                        <InputError :message="form.errors.description" class="mt-1.5" />
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                        <div>
                            <label for="date_time" class="block text-sm font-medium text-slate-700">Data e ora</label>
                            <input id="date_time" v-model="form.date_time" type="datetime-local" required class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm shadow-sm transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20" />
                            <InputError :message="form.errors.date_time" class="mt-1.5" />
                        </div>
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-slate-700">Durata (min)</label>
                            <input id="duration_minutes" v-model="form.duration_minutes" type="number" min="15" max="480" required class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm shadow-sm transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20" />
                            <InputError :message="form.errors.duration_minutes" class="mt-1.5" />
                        </div>
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-slate-700">Capienza</label>
                            <input id="capacity" v-model="form.capacity" type="number" min="1" required class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm shadow-sm transition focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-violet-500/20" />
                            <InputError :message="form.errors.capacity" class="mt-1.5" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                        <Link :href="route('admin.workshops.index')" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Annulla</Link>
                        <button type="submit" :disabled="form.processing" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 transition hover:shadow-violet-500/40 hover:brightness-110 disabled:opacity-50">
                            {{ isEditing ? 'Salva modifiche' : 'Crea workshop' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
