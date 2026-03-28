<script setup>
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);
const form = useForm({ password: '' });

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section>
        <h2 class="text-base font-bold text-red-700">Elimina account</h2>
        <p class="mt-1 text-sm text-slate-500">
            Una volta eliminato il tuo account, tutti i dati e le iscrizioni verranno rimossi in modo permanente.
        </p>

        <button @click="confirmUserDeletion"
            class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-700 transition-all duration-200 hover:bg-red-100 hover:border-red-300 active:scale-[0.98]">
            Elimina il mio account
        </button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-bold text-slate-900">Sei sicuro di voler eliminare il tuo account?</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Questa azione e irreversibile. Tutti i tuoi dati verranno eliminati definitivamente.
                    Inserisci la password per confermare.
                </p>

                <div class="mt-5">
                    <input ref="passwordInput" type="password" v-model="form.password" placeholder="La tua password"
                        class="block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 focus:border-red-400 focus:from-red-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-red-500/25"
                        @keyup.enter="deleteUser" />
                    <InputError :message="form.errors.password" class="mt-1.5" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button @click="closeModal"
                        class="rounded-2xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                        Annulla
                    </button>
                    <button @click="deleteUser" :disabled="form.processing"
                        class="rounded-2xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-500/25 transition-all duration-200 hover:shadow-red-500/40 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50">
                        Elimina definitivamente
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
