<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({ current_password: '', password: '', password_confirmation: '' });

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) { form.reset('password', 'password_confirmation'); passwordInput.value.focus(); }
            if (form.errors.current_password) { form.reset('current_password'); currentPasswordInput.value.focus(); }
        },
    });
};
</script>

<template>
    <section>
        <h2 class="text-base font-bold text-slate-900">Cambia password</h2>
        <p class="mt-1 text-sm text-slate-500">Assicurati di usare una password lunga e sicura</p>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-5">
            <div>
                <label for="current_password" class="block text-sm font-semibold text-slate-700">Password attuale</label>
                <input id="current_password" ref="currentPasswordInput" type="password" v-model="form.current_password" autocomplete="current-password"
                    class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25" />
                <InputError :message="form.errors.current_password" class="mt-1.5" />
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Nuova password</label>
                    <input id="password" ref="passwordInput" type="password" v-model="form.password" autocomplete="new-password"
                        class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25" />
                    <InputError :message="form.errors.password" class="mt-1.5" />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Conferma</label>
                    <input id="password_confirmation" type="password" v-model="form.password_confirmation" autocomplete="new-password"
                        class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25" />
                    <InputError :message="form.errors.password_confirmation" class="mt-1.5" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" :disabled="form.processing"
                    class="rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-violet-500/25 transition-all duration-200 hover:shadow-violet-500/40 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50">
                    Aggiorna password
                </button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm font-medium text-emerald-600">Aggiornata!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
