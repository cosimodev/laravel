<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({ mustVerifyEmail: Boolean, status: String });

const user = usePage().props.auth.user;
const form = useForm({ name: user.name, email: user.email });
</script>

<template>
    <section>
        <h2 class="text-base font-bold text-slate-900">Informazioni personali</h2>
        <p class="mt-1 text-sm text-slate-500">Aggiorna il tuo nome e indirizzo email</p>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-5">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Nome</label>
                <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                    class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 placeholder:text-slate-400 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25" />
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" type="email" v-model="form.email" required autocomplete="username"
                    class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 placeholder:text-slate-400 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25" />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm text-slate-700">
                    Il tuo indirizzo email non e verificato.
                    <Link :href="route('verification.send')" method="post" as="button" class="font-semibold text-violet-700 underline underline-offset-2 decoration-violet-300 hover:decoration-violet-500 transition-all">
                        Invia nuovamente il link di verifica.
                    </Link>
                </p>
                <div v-show="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-emerald-600">
                    Un nuovo link di verifica e stato inviato alla tua email.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" :disabled="form.processing"
                    class="rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-violet-500/25 transition-all duration-200 hover:shadow-violet-500/40 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50">
                    Salva modifiche
                </button>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm font-medium text-emerald-600">Salvato!</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
