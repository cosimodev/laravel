<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({ mustVerifyEmail: Boolean, status: String });

const user = usePage().props.auth.user;
const fileInput = ref(null);

function uploadAvatar(e) {
    const file = e.target.files[0];
    if (!file) return;
    const form = useForm({ avatar: file });
    form.post(route('profile.avatar'), { forceFormData: true });
}

function removeAvatar() {
    router.delete(route('profile.avatar.destroy'));
}
</script>

<template>
    <Head title="Profilo" />
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-slate-900">Il tuo profilo</h1>
                    <p class="mt-1 text-sm text-slate-500">Gestisci le tue informazioni personali e la sicurezza</p>
                </div>

                <!-- Avatar Section -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm mb-6">
                    <h2 class="text-base font-bold text-slate-900">Foto profilo</h2>
                    <p class="mt-1 text-sm text-slate-500">Carica un'immagine che ti rappresenti (max 2MB)</p>

                    <div class="mt-5 flex items-center gap-6">
                        <div class="relative group">
                            <div v-if="user.avatar_url" class="h-20 w-20 rounded-2xl overflow-hidden ring-2 ring-slate-200">
                                <img :src="user.avatar_url" class="h-full w-full object-cover" alt="Avatar" />
                            </div>
                            <div v-else class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 text-2xl font-black text-white ring-2 ring-violet-200">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row">
                            <button @click="$refs.fileInput.click()" class="rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-violet-500/25 transition-all duration-200 hover:shadow-violet-500/40 hover:scale-[1.02] active:scale-[0.98]">
                                Carica foto
                            </button>
                            <button v-if="user.avatar_url" @click="removeAvatar" class="rounded-xl border border-red-200 px-5 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50">
                                Rimuovi
                            </button>
                            <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="uploadAvatar" />
                        </div>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm mb-6">
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                </div>

                <!-- Password -->
                <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm mb-6">
                    <UpdatePasswordForm />
                </div>

                <!-- Delete Account -->
                <div class="rounded-2xl border border-red-100 bg-red-50/30 p-6 shadow-sm">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
