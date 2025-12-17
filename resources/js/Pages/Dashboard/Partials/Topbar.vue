<script setup>
import { Link } from "@inertiajs/vue3";
import {
    Menu as HeadlessMenu,
    MenuButton,
    MenuItems,
    MenuItem,
} from "@headlessui/vue";
import {
    Menu as MenuIcon,
    Search,
    Sun,
    Moon,
    Bell,
    ChevronDown,
    Settings,
    LogOut,
    Briefcase,
} from "lucide-vue-next";
import { computed, ref } from "vue";

const props = defineProps({
    title: String,
    user: Object,
    isExpert: Boolean,
    isAdmin: Boolean,
    assets: Object,
});

const emit = defineEmits(["openMobileMenu"]);
const isDarkMode = ref(false);

// LOGIC BARU: Menentukan Label Role
const userRoleLabel = computed(() => {
    if (props.isAdmin) return "Administrator";
    if (props.isExpert) return "Expert";
    return "Client";
});
</script>

<template>
    <header
        class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 px-4 sm:px-8 relative flex items-center justify-between"
    >
        <div class="flex items-center gap-4 shrink-0 relative z-20">
            <button
                @click="emit('openMobileMenu')"
                class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg"
            >
                <MenuIcon class="w-6 h-6" />
            </button>
            <h2
                class="text-xl font-bold text-slate-800 hidden sm:block whitespace-nowrap"
            >
                {{ title }}
            </h2>
        </div>

        <div
            class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-xl px-4 hidden md:block z-10"
        >
            <div class="relative group">
                <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                    <Search
                        class="h-5 w-5 text-slate-400 group-focus-within:text-violet-500 transition-colors"
                    />
                </div>
                <input
                    type="text"
                    class="block w-full pl-10 pr-4 py-2.5 bg-slate-100/50 border border-transparent rounded-full text-sm text-slate-900 placeholder-slate-500 focus:outline-none focus:bg-white focus:border-violet-500 transition-all shadow-sm"
                    placeholder="Search anything..."
                />
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-4 shrink-0 relative z-20">
            <button
                @click="isDarkMode = !isDarkMode"
                class="p-2 text-slate-500 bg-slate-100 rounded-full"
            >
                <component :is="isDarkMode ? Sun : Moon" class="w-5 h-5" />
            </button>
            <button
                class="p-2 text-slate-500 bg-slate-100 rounded-full relative"
            >
                <Bell class="w-5 h-5" />
                <span
                    class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"
                ></span>
            </button>

            <div class="h-6 w-px bg-slate-200 mx-1"></div>

            <HeadlessMenu as="div" class="relative">
                <MenuButton
                    class="flex items-center gap-3 pl-1 pr-1 sm:pl-2 sm:pr-2 py-1 rounded-full hover:bg-slate-100 transition-all border border-transparent hover:border-slate-200 group focus:outline-none"
                >
                    <div class="relative">
                        <img
                            :src="
                                user.profile_photo_url ||
                                assets.userPlaceholderUrl
                            "
                            class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm"
                            alt="Avatar"
                        />
                        <div
                            class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"
                        ></div>
                    </div>
                    <div
                        class="hidden lg:flex flex-col items-start mr-1 text-left"
                    >
                        <span
                            class="text-sm font-bold text-slate-900 leading-none max-w-[100px] truncate"
                            >{{ user.name }}</span
                        >
                        <span
                            class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5"
                        >
                            {{ userRoleLabel }}
                        </span>
                    </div>
                    <ChevronDown
                        class="w-4 h-4 text-slate-400 group-hover:text-slate-600 hidden lg:block"
                    />
                </MenuButton>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                        class="absolute right-0 mt-2 w-60 origin-top-right divide-y divide-slate-100 rounded-2xl bg-white shadow-xl ring-1 ring-black/5 focus:outline-none overflow-hidden z-50"
                    >
                        <div
                            class="px-5 py-4 bg-slate-50 border-b border-slate-100"
                        >
                            <p
                                class="text-sm font-bold text-slate-900 truncate"
                            >
                                {{ user.name }}
                            </p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ user.email }}
                            </p>
                        </div>
                        <div class="p-1.5">
                            <MenuItem v-slot="{ active }">
                                <Link
                                    :href="route('update_profile')"
                                    :class="[
                                        active
                                            ? 'bg-violet-50 text-violet-700'
                                            : 'text-slate-700',
                                        'group flex w-full items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-colors',
                                    ]"
                                >
                                    <Settings
                                        class="mr-3 h-4 w-4 text-slate-400 group-hover:text-violet-500"
                                    />
                                    Account Settings
                                </Link>
                            </MenuItem>

                            <MenuItem v-if="isExpert" v-slot="{ active }">
                                <Link
                                    :href="route('register_expert')"
                                    :class="[
                                        active
                                            ? 'bg-violet-50 text-violet-700'
                                            : 'text-slate-700',
                                        'group flex w-full items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-colors',
                                    ]"
                                >
                                    <Briefcase
                                        class="mr-3 h-4 w-4 text-slate-400 group-hover:text-violet-500"
                                    />
                                    Manage Expert Profile
                                </Link>
                            </MenuItem>

                            <MenuItem v-slot="{ active }">
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    :class="[
                                        active
                                            ? 'bg-red-50 text-red-600'
                                            : 'text-slate-600',
                                        'group flex w-full items-center rounded-xl px-3 py-2.5 text-sm font-medium transition-colors',
                                    ]"
                                >
                                    <LogOut
                                        class="mr-3 h-4 w-4 text-slate-400 group-hover:text-red-500"
                                    />
                                    Sign Out
                                </Link>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </HeadlessMenu>
        </div>
    </header>
</template>
