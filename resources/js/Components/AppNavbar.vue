<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import {
    ChevronDownIcon,
    UserGroupIcon,
    BriefcaseIcon,
    UserIcon,
    ArrowRightOnRectangleIcon
} from '@heroicons/vue/20/solid';

// --- 1. SETUP DATA & ASSETS (Sesuai perbaikan sebelumnya) ---
const page = usePage();
const user = computed(() => page.props.auth.user);
const assets = computed(() => page.props.assets);

// --- 2. LOGIC SCROLL & MOBILE MENU (Dari kodingan lama Anda) ---
const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => { window.addEventListener('scroll', handleScroll); });
onUnmounted(() => { window.removeEventListener('scroll', handleScroll); });

// --- 3. HELPER ACTIVE LINK ---
const isActive = (name) => {
    return route().current(name);
};
</script>

<template>
    <header class="fixed top-0 left-0 w-full z-50 transition-all duration-500 ease-in-out"
        :class="isScrolled ? 'bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 py-3' : 'bg-transparent py-6'">

        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-12">

                <div class="flex-shrink-0 flex items-center cursor-pointer">
                    <Link :href="route('home')" class="flex items-center gap-2">
                        <img :src="assets.logoSmallUrl" class="h-9 w-auto" alt="KeyPerson Logo" />
                        <span class="font-display font-bold text-xl tracking-tight text-slate-900">
                            Key<span class="text-violet-600">Person</span>
                        </span>
                    </Link>
                </div>

                <div class="hidden lg:flex lg:items-center lg:gap-8">
                    <Link :href="route('home')" class="text-sm font-medium transition-colors duration-200"
                        :class="isActive('home') ? 'text-violet-600 font-bold' : 'text-slate-600 hover:text-violet-600'">
                        Home
                    </Link>
                    <Link :href="route('about')" class="text-sm font-medium transition-colors duration-200"
                        :class="isActive('about') ? 'text-violet-600 font-bold' : 'text-slate-600 hover:text-violet-600'">
                        About
                    </Link>
                    <Link :href="route('support')" class="text-sm font-medium transition-colors duration-200"
                        :class="isActive('support') ? 'text-violet-600 font-bold' : 'text-slate-600 hover:text-violet-600'">
                        Support
                    </Link>
                    <Link :href="route('pricing')" class="text-sm font-medium transition-colors duration-200"
                        :class="isActive('pricing') ? 'text-violet-600 font-bold' : 'text-slate-600 hover:text-violet-600'">
                        Pricing
                    </Link>
                </div>

                <div class="hidden lg:flex items-center gap-4">

                    <template v-if="!user">
                        <Link :href="route('login')"
                            class="text-sm font-bold text-slate-600 hover:text-violet-600 transition-colors">
                            Sign In
                        </Link>

                        <Menu as="div" class="relative inline-block text-left">
                            <div>
                                <MenuButton
                                    class="group inline-flex items-center justify-center gap-x-1.5 rounded-full px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-violet-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-violet-500/50 focus:outline-none bg-gradient-to-r from-violet-600 to-fuchsia-600 hover:from-violet-500 hover:to-fuchsia-500">
                                    Get Started
                                    <ChevronDownIcon
                                        class="-mr-1 h-5 w-5 text-violet-200 group-hover:text-white transition-colors"
                                        aria-hidden="true" />
                                </MenuButton>
                            </div>

                            <transition enter-active-class="transition duration-200 ease-out"
                                enter-from-class="translate-y-2 opacity-0 scale-95"
                                enter-to-class="translate-y-0 opacity-100 scale-100"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="translate-y-0 opacity-100 scale-100"
                                leave-to-class="translate-y-2 opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 mt-3 w-60 origin-top-right divide-y divide-gray-100 rounded-2xl bg-white shadow-xl ring-1 ring-black/5 focus:outline-none overflow-hidden">
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('register_client')" :class="[
                                            active ? 'bg-violet-50' : '',
                                            'group flex w-full items-center px-4 py-3 text-sm text-gray-900 transition-colors'
                                        ]">
                                            <div
                                                class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-violet-100 text-violet-600 group-hover:bg-violet-600 group-hover:text-white transition-colors">
                                                <UserGroupIcon class="h-5 w-5" />
                                            </div>
                                            <div class="flex flex-col text-left">
                                                <span class="font-bold text-violet-900">Sebagai Client</span>
                                                <span class="text-xs text-slate-500">Cari mentor & booking</span>
                                            </div>
                                        </Link>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('register_expert')" :class="[
                                            active ? 'bg-fuchsia-50' : '',
                                            'group flex w-full items-center px-4 py-3 text-sm text-gray-900 transition-colors'
                                        ]">
                                            <div
                                                class="mr-3 flex h-9 w-9 items-center justify-center rounded-full bg-fuchsia-100 text-fuchsia-600 group-hover:bg-fuchsia-600 group-hover:text-white transition-colors">
                                                <BriefcaseIcon class="h-5 w-5" />
                                            </div>
                                            <div class="flex flex-col text-left">
                                                <span class="font-bold text-fuchsia-900">Sebagai Expert</span>
                                                <span class="text-xs text-slate-500">Tawarkan jasa anda</span>
                                            </div>
                                        </Link>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </template>

                    <template v-else>
                        <Menu as="div" class="relative ml-3">
                            <MenuButton
                                class="flex items-center gap-2 rounded-full bg-white/50 pl-1 pr-3 py-1 hover:bg-white transition-all border border-transparent hover:border-violet-200 hover:shadow-md">
                                <img class="h-8 w-8 rounded-full object-cover border border-violet-100"
                                    :src="user.profile_photo_url || assets.userPlaceholderUrl" alt="" />
                                <span class="text-sm font-bold text-slate-700 hidden sm:block">{{ user.name }}</span>
                                <ChevronDownIcon class="h-4 w-4 text-slate-400" />
                            </MenuButton>

                            <transition enter-active-class="transition duration-200 ease-out"
                                enter-from-class="translate-y-2 opacity-0 scale-95"
                                enter-to-class="translate-y-0 opacity-100 scale-100"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="translate-y-0 opacity-100 scale-100"
                                leave-to-class="translate-y-2 opacity-0 scale-95">
                                <MenuItems
                                    class="absolute right-0 mt-2 w-48 origin-top-right divide-y divide-gray-100 rounded-xl bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('profile')"
                                            :class="[active ? 'bg-violet-50 text-violet-700' : 'text-slate-700', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                                            <UserIcon class="mr-2 h-5 w-5 text-violet-400" />
                                            Dashboard
                                        </Link>
                                        </MenuItem>
                                    </div>
                                    <div class="px-1 py-1">
                                        <MenuItem v-slot="{ active }">
                                        <Link :href="route('logout')" method="post" as="button"
                                            :class="[active ? 'bg-red-50 text-red-700' : 'text-slate-700', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                                            <ArrowRightOnRectangleIcon class="mr-2 h-5 w-5 text-red-400" />
                                            Log Out
                                        </Link>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </template>
                </div>

                <div class="flex items-center lg:hidden">
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="text-slate-600 hover:text-violet-600 focus:outline-none p-2 rounded-md hover:bg-slate-100 transition-colors">
                        <svg v-if="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <transition enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform -translate-y-4 opacity-0" enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in" leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-4 opacity-0">

            <div v-if="isMobileMenuOpen"
                class="lg:hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-xl shadow-xl border-t border-slate-100 px-4 py-6 flex flex-col gap-2">
                <Link :href="route('home')"
                    class="px-4 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-600 transition-colors">
                    Home</Link>
                <Link :href="route('about')"
                    class="px-4 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-600 transition-colors">
                    About</Link>
                <Link :href="route('support')"
                    class="px-4 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-600 transition-colors">
                    Support</Link>
                <Link :href="route('pricing')"
                    class="px-4 py-3 rounded-xl text-base font-semibold text-slate-700 hover:bg-violet-50 hover:text-violet-600 transition-colors">
                    Pricing</Link>

                <div class="mt-4 pt-4 border-t border-slate-100 space-y-3">
                    <template v-if="!user">
                        <Link :href="route('login')"
                            class="block w-full text-center px-4 py-3 font-bold text-slate-600 hover:text-violet-600">
                            Sign In</Link>
                        <div class="grid grid-cols-2 gap-3">
                            <Link :href="route('register_client')"
                                class="flex flex-col items-center justify-center p-3 rounded-xl bg-violet-50 border border-violet-100 text-violet-700 active:scale-95 transition-transform">
                                <UserGroupIcon class="h-6 w-6 mb-1" />
                                <span class="text-xs font-bold">Client</span>
                            </Link>
                            <Link :href="route('register_expert')"
                                class="flex flex-col items-center justify-center p-3 rounded-xl bg-fuchsia-50 border border-fuchsia-100 text-fuchsia-700 active:scale-95 transition-transform">
                                <BriefcaseIcon class="h-6 w-6 mb-1" />
                                <span class="text-xs font-bold">Expert</span>
                            </Link>
                        </div>
                    </template>
                    <template v-else>
                        <div class="flex items-center px-4 gap-3 mb-2">
                            <img :src="user.profile_photo_url || assets.userPlaceholderUrl"
                                class="h-10 w-10 rounded-full object-cover" />
                            <div>
                                <div class="font-bold text-slate-900">{{ user.name }}</div>
                                <div class="text-xs text-slate-500">{{ user.email }}</div>
                            </div>
                        </div>
                        <Link :href="route('profile')"
                            class="block w-full text-center px-6 py-3 text-sm font-bold text-white bg-slate-900 rounded-xl hover:bg-slate-800">
                            Go to Dashboard
                        </Link>
                    </template>
                </div>
            </div>
        </transition>
    </header>
</template>