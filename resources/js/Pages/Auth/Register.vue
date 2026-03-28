<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ name: '', email: '', password: '', password_confirmation: '' });

const submit = () => {
    form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') });
};
</script>

<template>
    <GuestLayout>
        <Head title="Registrati" />

        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-950">Crea il tuo account</h1>
            <p class="mt-3 text-base text-slate-600 leading-relaxed">Unisciti al team e scopri i workshop disponibili</p>
        </div>

        <form @submit.prevent="submit" class="mt-8 space-y-5">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Nome completo</label>
                <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                    class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 placeholder:text-slate-400 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25"
                    placeholder="Mario Rossi" />
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" type="email" v-model="form.email" required autocomplete="username"
                    class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 placeholder:text-slate-400 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25"
                    placeholder="nome@azienda.it" />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                        class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 placeholder:text-slate-400 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25"
                        placeholder="Min. 8 caratteri" />
                    <InputError class="mt-1.5" :message="form.errors.password" />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Conferma</label>
                    <input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                        class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 placeholder:text-slate-400 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25"
                        placeholder="Ripeti password" />
                    <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <button type="submit" :disabled="form.processing"
                class="w-full rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-violet-500/25 transition-all duration-200 hover:shadow-violet-500/40 hover:scale-[1.02] hover:-translate-y-0.5 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:translate-y-0">
                <span v-if="form.processing" class="flex items-center justify-center gap-2">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Registrazione...
                </span>
                <span v-else>Crea account</span>
            </button>

            <p class="text-center text-sm text-slate-500">
                Hai gia un account?
                <Link :href="route('login')" class="font-bold text-violet-700 underline underline-offset-2 decoration-violet-300 hover:decoration-violet-500 transition-all">Accedi</Link>
            </p>
        </form>
    </GuestLayout>
</template>
