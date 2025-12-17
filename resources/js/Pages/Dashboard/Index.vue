<script setup>
import { ref, computed } from "vue";
import { Head, usePage } from "@inertiajs/vue3";

// Composables
import { useDashboardMenu } from "./Composables/useDashboardMenu";

// Layout Partials
import Sidebar from "./Partials/Sidebar.vue";
import Topbar from "./Partials/Topbar.vue";

// Tabs Content
import HomeTab from "./Tabs/HomeTab.vue";
import AppointmentTab from "./Tabs/AppointmentTab.vue";
import CalendarTab from "./Tabs/CalendarTab.vue";
import MembersTab from "./Tabs/MembersTab.vue";
import ExpertiseTab from "./Tabs/ExpertiseTab.vue";
import PaymentTab from "./Tabs/PaymentTab.vue";

const props = defineProps({
    user: Object,
    roles: Array,
    isExpert: Boolean,
    isClient: Boolean,
    isAdmin: Boolean,
    expertises: Array,
    appointmentsCount: Number,
    calendarEvents: Array,
    socialMedias: Array,
    appointments: Object,
    transactions: Object,
});

const page = usePage();
const assets = computed(() => page.props.assets);

// State Layout
const isDarkMode = ref(false);
const isSidebarExpanded = ref(true);
const isMobileMenuOpen = ref(false);

// State Menu & Tabs (Dari Composable)
const { activeTab, tabs, currentTabTitle } = useDashboardMenu(props);
</script>

<template>
    <Head title="My Dashboard" />

    <div class="min-h-screen bg-slate-50 font-sans flex">
        <Sidebar
            :is-sidebar-expanded="isSidebarExpanded"
            :is-mobile-menu-open="isMobileMenuOpen"
            :active-tab="activeTab"
            :tabs="tabs"
            :assets="assets"
            @toggle-sidebar="isSidebarExpanded = !isSidebarExpanded"
            @close-mobile-menu="isMobileMenuOpen = false"
            @update:active-tab="(id) => (activeTab = id)"
        />

        <main
            class="flex-1 min-h-screen transition-all duration-300 ease-in-out flex flex-col"
            :class="isSidebarExpanded ? 'lg:ml-64' : 'lg:ml-20'"
        >
            <Topbar
                :title="currentTabTitle"
                :user="user"
                :is-expert="isExpert"
                :is-admin="isAdmin"
                :assets="assets"
                @open-mobile-menu="isMobileMenuOpen = true"
            />

            <div class="p-4 sm:p-8 flex-1">
                <div class="max-w-7xl mx-auto h-full">
                    <Transition name="fade" mode="out-in">
                        <div
                            v-if="activeTab === 'home'"
                            key="home"
                            class="h-full"
                        >
                            <HomeTab
                                :user="user"
                                :appointments-count="appointmentsCount"
                                :is-expert="isExpert"
                            />
                        </div>

                        <div
                            v-else-if="activeTab === 'appointments'"
                            key="appointments"
                        >
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8"
                            >
                                <div class="mb-6">
                                    <h2
                                        class="text-2xl font-bold text-slate-900"
                                    >
                                        My Appointments
                                    </h2>
                                    <p class="text-slate-500 mt-1">
                                        Manage your upcoming and past sessions.
                                    </p>
                                </div>
                                <AppointmentTab
                                    :appointments="appointments.data"
                                    :is-expert="isExpert"
                                    :is-admin="isAdmin"
                                />
                            </div>
                        </div>

                        <div
                            v-else-if="activeTab === 'calendar'"
                            key="calendar"
                        >
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8"
                            >
                                <CalendarTab
                                    :calendar-events="calendarEvents"
                                />
                            </div>
                        </div>

                        <div
                            v-else-if="activeTab === 'transactions'"
                            key="transactions"
                        >
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8"
                            >
                                <PaymentTab
                                    :transactions="transactions.data"
                                    :is-expert="isExpert"
                                    :is-admin="isAdmin"
                                />
                            </div>
                        </div>

                        <div v-else-if="activeTab === 'members'" key="members">
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden p-6 md:p-8"
                            >
                                <MembersTab />
                            </div>
                        </div>

                        <div
                            v-else-if="activeTab === 'expertises' && isAdmin"
                            key="expertises"
                        >
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
                            >
                                <div
                                    class="p-6 md:p-8 border-b border-slate-100"
                                >
                                    <h2
                                        class="text-xl font-bold text-slate-900"
                                    >
                                        Expertise Settings
                                    </h2>
                                </div>
                                <div class="p-6 md:p-8">
                                    <ExpertiseTab :expertises="expertises" />
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </main>

        <div
            v-if="isMobileMenuOpen"
            @click="isMobileMenuOpen = false"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 lg:hidden"
        ></div>
    </div>
</template>

<style scoped>
/* Scrollbar custom style bisa dipindah ke Sidebar.vue atau global css */
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
