<script setup>
import { ref, computed } from "vue";
import { Head, usePage, Link } from "@inertiajs/vue3";
import AppLayout from "../../Layouts/AppLayout.vue";

// Components
import HomeTab from "./Tabs/HomeTab.vue";
import AppointmentTab from "./Tabs/AppointmentTab.vue";
import CalendarTab from "./Tabs/CalendarTab.vue";
import MembersTab from "./Tabs/MembersTab.vue";
import ExpertiseTab from "./Tabs/ExpertiseTab.vue";
import PaymentTab from "./Tabs/PaymentTab.vue";

// Icons
import {
    Home,
    Briefcase,
    Calendar as CalendarIcon,
    Users,
    Settings,
    ChevronRight,
    CreditCard,
    LogOut,
    Menu,
    X
} from "lucide-vue-next";

// Props
const props = defineProps({
    user: Object,
    roles: Array,
    isExpert: Boolean,
    isClient: Boolean,
    isAdmin: Boolean,
    expertises: Array,
    appointments: Array,
    appointmentsCount: Number,
    calendarEvents: Array,
    socialMedias: Array,
    transactions: Array,
});

// Assets Helper
const page = usePage();
const assets = computed(() => page.props.assets);

// State
const activeTab = ref("home");
const isMobileMenuOpen = ref(false);

// Config Menu Tabs
const tabs = [
    {
        id: 'home',
        label: 'Overview',
        desc: 'Stats & Activity',
        icon: Home,
        show: true,
        color: 'text-violet-600',
        bgActive: 'bg-violet-50',
        borderActive: 'border-violet-200'
    },
    {
        id: 'appointments',
        label: 'My Appointments',
        desc: 'Manage Sessions',
        icon: Briefcase,
        show: true,
        color: 'text-blue-600',
        bgActive: 'bg-blue-50',
        borderActive: 'border-blue-200'
    },
    {
        id: 'calendar',
        label: 'Schedule',
        desc: 'Calendar View',
        icon: CalendarIcon,
        show: true,
        color: 'text-emerald-600',
        bgActive: 'bg-emerald-50',
        borderActive: 'border-emerald-200'
    },
    {
        id: 'transactions',
        label: 'Billing',
        desc: 'Invoices & History',
        icon: CreditCard,
        show: true,
        color: 'text-pink-600',
        bgActive: 'bg-pink-50',
        borderActive: 'border-pink-200'
    },
    {
        id: 'members',
        label: 'Community',
        desc: 'Experts & Users',
        icon: Users,
        show: true,
        color: 'text-indigo-600',
        bgActive: 'bg-indigo-50',
        borderActive: 'border-indigo-200'
    },
    {
        id: 'expertises',
        label: 'Settings',
        desc: 'System Config',
        icon: Settings,
        show: props.isAdmin,
        color: 'text-orange-600',
        bgActive: 'bg-orange-50',
        borderActive: 'border-orange-200'
    },
];

// Computed Title untuk Header Kanan
const currentTabTitle = computed(() => {
    const tab = tabs.find(t => t.id === activeTab.value);
    return tab ? tab.label : 'Dashboard';
});
</script>

<template>

    <Head title="My Dashboard" />

    <AppLayout>
        <div class="bg-slate-50/50 min-h-screen pt-28 pb-20 font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
                    <div>
                        <h1 class="font-display text-3xl md:text-4xl font-bold text-slate-900">
                            Welcome back, <span class="text-violet-600">{{ user.name.split(' ')[0] }}</span>!
                        </h1>
                        <p class="text-slate-500 mt-2 text-lg">Here's what's happening with your account today.</p>
                    </div>

                    <button @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="md:hidden flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm text-slate-700 font-bold">
                        <Menu v-if="!isMobileMenuOpen" class="w-5 h-5" />
                        <X v-else class="w-5 h-5" />
                        Menu
                    </button>
                </div>

                <div class="grid lg:grid-cols-12 gap-8 items-start relative">

                    <div class="lg:col-span-3 lg:sticky lg:top-28 z-20 transition-all duration-300"
                        :class="isMobileMenuOpen ? 'block' : 'hidden lg:block'">

                        <div
                            class="bg-white rounded-3xl p-6 border border-slate-200/60 shadow-xl shadow-slate-200/40 mb-6 text-center relative overflow-hidden group">
                            <div
                                class="absolute top-0 left-0 w-full h-20 bg-linear-to-r from-violet-500 to-fuchsia-500 opacity-10 group-hover:opacity-20 transition-opacity">
                            </div>

                            <div class="relative inline-block mt-4 mb-4">
                                <div class="p-1 bg-white rounded-full">
                                    <img :src="user.profile_photo_url || assets.userPlaceholderUrl"
                                        class="w-20 h-20 rounded-full object-cover border-2 border-slate-100 shadow-sm"
                                        alt="Profile">
                                </div>
                                <div class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full"
                                    title="Online"></div>
                            </div>

                            <h3 class="font-bold text-slate-900 text-lg leading-tight">{{ user.name }}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1 mb-4">
                                {{ isExpert ? 'Professional Expert' : 'Member Client' }}
                            </p>

                            <Link :href="route('update_profile')"
                                class="inline-flex w-full justify-center items-center py-2.5 px-4 rounded-xl bg-slate-50 text-slate-600 text-sm font-bold hover:bg-slate-900 hover:text-white transition-all duration-300">
                            Edit Profile
                            </Link>
                        </div>

                        <div class="bg-white rounded-3xl p-3 border border-slate-200/60 shadow-sm">
                            <nav class="space-y-1">
                                <template v-for="tab in tabs" :key="tab.id">
                                    <button v-if="tab.show" @click="activeTab = tab.id; isMobileMenuOpen = false"
                                        class="group w-full flex items-center justify-between p-3 rounded-xl transition-all duration-200"
                                        :class="activeTab === tab.id
                                            ? `bg-white shadow-md border ${tab.borderActive}`
                                            : 'hover:bg-slate-50 text-slate-500'">

                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
                                                :class="activeTab === tab.id ? tab.bgActive : 'bg-slate-100 group-hover:bg-white'">
                                                <component :is="tab.icon" class="w-5 h-5"
                                                    :class="activeTab === tab.id ? tab.color : 'text-slate-500'" />
                                            </div>
                                            <div class="text-left">
                                                <div class="font-bold text-sm"
                                                    :class="activeTab === tab.id ? 'text-slate-900' : 'text-slate-600'">
                                                    {{ tab.label }}
                                                </div>
                                                <div class="text-[10px] font-medium opacity-60 hidden xl:block">
                                                    {{ tab.desc }}
                                                </div>
                                            </div>
                                        </div>

                                        <ChevronRight v-if="activeTab === tab.id" class="w-4 h-4 transition-colors"
                                            :class="tab.color" />
                                    </button>
                                </template>
                            </nav>

                            <div class="mt-3 pt-3 border-t border-slate-100">
                                <Link :href="route('logout')" method="post" as="button"
                                    class="w-full flex items-center gap-3 p-3 rounded-xl text-red-600 hover:bg-red-50 transition-colors font-medium text-sm">
                                <LogOut class="w-5 h-5" />
                                Sign Out
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-9">
                        <div
                            class="bg-white border border-slate-200/60 shadow-xl shadow-slate-200/30 rounded-[2.5rem] min-h-[600px] overflow-hidden relative flex flex-col">

                            <div class="lg:hidden p-6 border-b border-slate-100 bg-slate-50/50">
                                <h2 class="text-xl font-bold text-slate-900">{{ currentTabTitle }}</h2>
                            </div>

                            <div class="flex-1 relative">
                                <Transition name="fade" mode="out-in">

                                    <div v-if="activeTab === 'home'" key="home">
                                        <HomeTab :user="user" :appointments-count="appointmentsCount"
                                            :is-expert="isExpert" />
                                    </div>

                                    <div v-else-if="activeTab === 'expertises' && isAdmin" key="expertises"
                                        class="h-full">
                                        <div
                                            class="p-8 border-b border-slate-100 sticky top-0 bg-white/95 backdrop-blur z-10">
                                            <h2 class="text-2xl font-bold text-slate-900">Expertise Settings</h2>
                                            <p class="text-slate-500">Configure system categories and skills.</p>
                                        </div>
                                        <div class="p-8">
                                            <ExpertiseTab :expertises="expertises" />
                                        </div>
                                    </div>

                                    <div v-else-if="activeTab === 'appointments'" key="appointments" class="h-full">
                                        <AppointmentTab :appointments="appointments" :is-expert="isExpert"
                                            class="h-full" />
                                    </div>

                                    <div v-else-if="activeTab === 'calendar'" key="calendar" class="h-full p-6 md:p-10">
                                        <div class="mb-8">
                                            <h2 class="text-2xl font-bold text-slate-900">Your Schedule</h2>
                                            <p class="text-slate-500">Manage your availability and upcoming sessions.
                                            </p>
                                        </div>
                                        <CalendarTab :calendar-events="calendarEvents" />
                                    </div>

                                    <div v-else-if="activeTab === 'members'" key="members" class="h-full p-6 md:p-10">
                                        <div class="mb-8">
                                            <h2 class="text-2xl font-bold text-slate-900">Community Members</h2>
                                            <p class="text-slate-500">Explore registered users and experts.</p>
                                        </div>
                                        <MembersTab />
                                    </div>

                                    <div v-else-if="activeTab === 'transactions'" key="transactions"
                                        class="h-full p-6 md:p-10">
                                        <div class="mb-8">
                                            <h2 class="text-2xl font-bold text-slate-900">Transaction History</h2>
                                            <p class="text-slate-500">Track your payments and invoices.</p>
                                        </div>
                                        <PaymentTab :transactions="transactions" :is-expert="isExpert" />
                                    </div>

                                </Transition>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from {
    opacity: 0;
    transform: translateY(10px);
}

.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>