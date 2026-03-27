<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    workshop: {
        type: Object,
        default: null,
    },
});

const isEditing = !!props.workshop;

const form = useForm({
    title: props.workshop?.title ?? '',
    description: props.workshop?.description ?? '',
    date_time: props.workshop?.date_time ? props.workshop.date_time.slice(0, 16) : '',
    duration_minutes: props.workshop?.duration_minutes ?? 60,
    capacity: props.workshop?.capacity ?? 20,
});

function submit() {
    if (isEditing) {
        form.put(route('admin.workshops.update', props.workshop.id));
    } else {
        form.post(route('admin.workshops.store'));
    }
}
</script>

<template>
    <Head :title="isEditing ? 'Modifica Workshop' : 'Nuovo Workshop'" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ isEditing ? 'Modifica Workshop' : 'Nuovo Workshop' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="title" value="Titolo" />
                            <TextInput id="title" v-model="form.title" type="text" class="mt-1 block w-full" required />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Descrizione" />
                            <textarea id="description" v-model="form.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4" required />
                            <InputError :message="form.errors.description" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div>
                                <InputLabel for="date_time" value="Data e Ora" />
                                <TextInput id="date_time" v-model="form.date_time" type="datetime-local" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.date_time" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="duration_minutes" value="Durata (minuti)" />
                                <TextInput id="duration_minutes" v-model="form.duration_minutes" type="number" min="15" max="480" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.duration_minutes" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="capacity" value="Capienza" />
                                <TextInput id="capacity" v-model="form.capacity" type="number" min="1" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.capacity" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">
                                {{ isEditing ? 'Aggiorna' : 'Crea' }}
                            </PrimaryButton>
                            <Link :href="route('admin.workshops.index')" class="text-sm text-gray-600 hover:text-gray-900">Annulla</Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
