<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Internal Academy" />
    <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-indigo-50 to-white">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-indigo-600">Internal Academy</h1>
            <p class="mt-4 text-lg text-gray-600">Piattaforma di formazione aziendale</p>

            <div class="mt-8 flex gap-4 justify-center">
                <Link v-if="canLogin" :href="route('login')" class="rounded-md bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Accedi
                </Link>
                <Link v-if="canRegister" :href="route('register')" class="rounded-md bg-white px-6 py-3 text-sm font-semibold text-indigo-600 shadow-sm ring-1 ring-indigo-200 hover:bg-indigo-50">
                    Registrati
                </Link>
            </div>
        </div>
    </div>
</template>
