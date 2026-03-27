<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    workshop: Object,
    myRegistration: Object,
});

function register() {
    router.post(route('employee.workshops.register', props.workshop.id));
}

function cancel() {
    if (confirm('Vuoi cancellare la tua registrazione?')) {
        router.delete(route('employee.registrations.destroy', props.myRegistration.id));
    }
}
</script>

<template>
    <Head :title="workshop.title" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ workshop.title }}</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-lg bg-white p-6 shadow">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Descrizione</h3>
                            <p class="mt-1 whitespace-pre-wrap text-gray-900">{{ workshop.description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Data</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ new Date(workshop.date_time).toLocaleString('it-IT') }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Durata</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ workshop.duration_minutes }} min</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Posti</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ workshop.confirmed_count }} / {{ workshop.capacity }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">In attesa</h4>
                                <p class="mt-1 text-sm text-gray-900">{{ workshop.waiting_count }}</p>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div v-if="myRegistration">
                                <span class="inline-block rounded-full px-3 py-1 text-sm font-semibold" :class="myRegistration.status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                                    {{ myRegistration.status === 'confirmed' ? 'Sei iscritto' : 'In lista d\'attesa (posizione #' + myRegistration.position + ')' }}
                                </span>
                                <button @click="cancel" class="ml-4 text-sm text-red-600 hover:text-red-900">
                                    Cancella registrazione
                                </button>
                            </div>
                            <div v-else>
                                <button @click="register" class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                    {{ workshop.confirmed_count >= workshop.capacity ? 'Iscriviti in lista d\'attesa' : 'Iscriviti' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
