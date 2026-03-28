<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ canResetPassword: Boolean, status: String });

const form = useForm({ email: '', password: '', remember: false });

const submit = () => {
    form.post(route('login'), { onFinish: () => form.reset('password') });
};
</script>

<template>
    <GuestLayout>
        <Head title="Accedi" />

        <div>
            <h1 class="text-3xl font-black tracking-tight text-slate-950">Bentornato</h1>
            <p class="mt-3 text-base text-slate-600 leading-relaxed">Accedi per esplorare i workshop del team</p>
        </div>

        <div v-if="status" class="mt-5 flex items-center gap-3 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 px-5 py-4 text-sm font-medium text-emerald-800 ring-1 ring-emerald-200/60">
            <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="mt-8 space-y-5">
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" type="email" v-model="form.email" required autofocus autocomplete="username"
                    class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 placeholder:text-slate-400 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25"
                    placeholder="nome@azienda.it" />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                <input id="password" type="password" v-model="form.password" required autocomplete="current-password"
                    class="mt-2 block w-full rounded-2xl border border-slate-200/80 bg-gradient-to-br from-slate-50 to-white px-4 py-3.5 text-sm text-slate-900 shadow-sm transition-all duration-200 placeholder:text-slate-400 focus:border-violet-400 focus:from-violet-50/50 focus:to-white focus:outline-none focus:ring-2 focus:ring-violet-500/25"
                    placeholder="La tua password" />
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="text-sm text-slate-600">Ricordami</span>
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-semibold text-violet-700 underline underline-offset-2 decoration-violet-300 hover:decoration-violet-500 transition-all">
                    Password dimenticata?
                </Link>
            </div>

            <button type="submit" :disabled="form.processing"
                class="w-full rounded-2xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-violet-500/25 transition-all duration-200 hover:shadow-violet-500/40 hover:scale-[1.02] hover:-translate-y-0.5 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:translate-y-0">
                <span v-if="form.processing" class="flex items-center justify-center gap-2">
                    <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Accesso in corso...
                </span>
                <span v-else>Accedi</span>
            </button>

            <p class="text-center text-sm text-slate-500">
                Non hai un account?
                <Link :href="route('register')" class="font-bold text-violet-700 underline underline-offset-2 decoration-violet-300 hover:decoration-violet-500 transition-all">Registrati</Link>
            </p>
        </form>
    </GuestLayout>
</template>
