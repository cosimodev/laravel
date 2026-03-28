<script setup>
import { ref, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showMobile = ref(false);
const user = computed(() => usePage().props.auth.user);
const isAdmin = computed(() => user.value?.role === 'admin');
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col">
        <!-- Header -->
        <nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/80 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo + Nav -->
                    <div class="flex items-center gap-8">
                        <Link :href="isAdmin ? route('admin.dashboard') : route('employee.workshops.index')" class="flex items-center gap-2">
                            <span class="text-2xl leading-none">{{ $page.props.appLogo }}</span>
                            <span class="hidden text-base font-black text-slate-900 sm:block">Academy</span>
                        </Link>

                        <div class="hidden items-center gap-1 sm:flex">
                            <template v-if="isAdmin">
                                <Link :href="route('admin.dashboard')" class="rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200" :class="route().current('admin.dashboard') ? 'bg-gradient-to-r from-violet-50 to-indigo-50 text-violet-700 ring-1 ring-violet-200/60 shadow-sm shadow-violet-500/5' : 'text-slate-600 hover:text-violet-700 hover:bg-violet-50/50'">
                                    Dashboard
                                </Link>
                                <Link :href="route('admin.workshops.index')" class="rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200" :class="route().current('admin.workshops.*') ? 'bg-gradient-to-r from-violet-50 to-indigo-50 text-violet-700 ring-1 ring-violet-200/60 shadow-sm shadow-violet-500/5' : 'text-slate-600 hover:text-violet-700 hover:bg-violet-50/50'">
                                    Workshop
                                </Link>
                            </template>
                            <template v-else>
                                <Link :href="route('employee.workshops.index')" class="rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200" :class="route().current('employee.workshops.*') ? 'bg-gradient-to-r from-violet-50 to-indigo-50 text-violet-700 ring-1 ring-violet-200/60 shadow-sm shadow-violet-500/5' : 'text-slate-600 hover:text-violet-700 hover:bg-violet-50/50'">
                                    Workshop
                                </Link>
                            </template>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="hidden sm:flex sm:items-center sm:gap-3">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold" :class="isAdmin ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' : 'bg-sky-50 text-sky-700 ring-1 ring-sky-200'">
                            {{ isAdmin ? 'Admin' : 'Employee' }}
                        </span>

                        <div class="relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 text-xs font-bold text-white">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        {{ user.name }}
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                </template>
                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">Profilo</DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">Esci</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Mobile hamburger -->
                    <button @click="showMobile = !showMobile" class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 sm:hidden">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path v-if="!showMobile" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="showMobile" class="border-t border-slate-200 bg-white px-4 pb-4 pt-3 sm:hidden">
                <div class="space-y-1">
                    <template v-if="isAdmin">
                        <Link :href="route('admin.dashboard')" class="block rounded-lg px-3 py-2 text-sm font-medium" :class="route().current('admin.dashboard') ? 'bg-violet-50 text-violet-700' : 'text-slate-600'">Dashboard</Link>
                        <Link :href="route('admin.workshops.index')" class="block rounded-lg px-3 py-2 text-sm font-medium" :class="route().current('admin.workshops.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600'">Workshop</Link>
                    </template>
                    <template v-else>
                        <Link :href="route('employee.workshops.index')" class="block rounded-lg px-3 py-2 text-sm font-medium" :class="route().current('employee.workshops.*') ? 'bg-violet-50 text-violet-700' : 'text-slate-600'">Workshop</Link>
                    </template>
                </div>
                <div class="mt-3 border-t border-slate-200 pt-3">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 text-sm font-bold text-white">{{ user.name.charAt(0).toUpperCase() }}</div>
                        <div>
                            <div class="text-sm font-medium text-slate-900">{{ user.name }}</div>
                            <div class="text-xs text-slate-500">{{ user.email }}</div>
                        </div>
                    </div>
                    <Link :href="route('profile.edit')" class="block rounded-lg px-3 py-2 text-sm text-slate-600">Profilo</Link>
                    <Link :href="route('logout')" method="post" as="button" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-slate-600">Esci</Link>
                </div>
            </div>
        </nav>

        <!-- Page header -->
        <header v-if="$slots.header" class="border-b border-slate-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Flash Messages -->
        <div v-if="$page.props.flash?.success" class="mx-auto mt-6 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium text-emerald-800">{{ $page.props.flash.success }}</p>
            </div>
        </div>
        <div v-if="$page.props.flash?.error" class="mx-auto mt-6 w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                <svg class="h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
            </div>
        </div>

        <!-- Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-100 bg-gradient-to-r from-slate-50 to-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
                <div>
                    <p class="text-sm font-medium text-slate-700">Internal Academy</p>
                    <p class="mt-0.5 text-xs text-slate-400">Piattaforma di formazione aziendale</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistema online
                </div>
            </div>
        </footer>
    </div>
</template>
