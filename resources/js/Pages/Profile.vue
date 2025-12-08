<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import MainLayout from "../Layouts/MainLayout.vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import {
    User,
    Calendar as CalendarIcon,
    Users,
    Settings,
    LogOut,
    MoreHorizontal,
    Briefcase,
    CheckCircle,
    Clock,
    MapPin,
    Phone,
    Mail,
    AlertCircle,
} from "lucide-vue-next";

const props = defineProps({
    auth: Object,
    expertises: Array,
    appointments: Array,
    isExpert: Boolean,
    calendarAppointments: Array,
    appointmentsCount: Number,
});

const user = computed(() => props.auth.user);
const assets = computed(() => usePage().props.assets);
const activeTab = ref("appointments");

// Calendar Options
const calendarOptions = ref({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: "dayGridMonth",
    headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    events: props.calendarAppointments,
    height: "auto",
    eventColor: "#7c3aed", // Violet-600
});

const formatDate = (dateString) => {
    const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    };
    return new Date(dateString).toLocaleDateString("en-US", options);
};

const getStatusColor = (status) => {
    switch (status) {
        case "pending":
            return "bg-yellow-100 text-yellow-800";
        case "confirmed":
            return "bg-green-100 text-green-800";
        case "completed":
            return "bg-blue-100 text-blue-800";
        case "cancelled":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};
</script>

<template>
    <Head title="My Profile" />

    <MainLayout>
        <div class="bg-slate-50 min-h-screen pb-12">
            <!-- Header Banner -->
            <div class="relative h-64 bg-slate-900 overflow-hidden">
                <div
                    class="absolute inset-0 bg-linear-to-r from-violet-900 to-fuchsia-900 opacity-90"
                ></div>
                <img
                    :src="assets.bannerUrl"
                    alt="Banner"
                    class="w-full h-full object-cover opacity-30 mix-blend-overlay"
                />
                <div class="absolute inset-0 flex items-center justify-center">
                    <!-- Optional: Add content over banner -->
                </div>
            </div>

            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-10"
            >
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                    <div class="p-6 sm:p-10">
                        <div
                            class="flex flex-col md:flex-row gap-8 items-start"
                        >
                            <!-- Profile Picture -->
                            <div class="shrink-0 mx-auto md:mx-0">
                                <div class="relative">
                                    <div
                                        class="w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-white shadow-lg overflow-hidden bg-slate-100"
                                    >
                                        <img
                                            :src="
                                                user.picture
                                                    ? `/storage/${user.picture}`
                                                    : '/assets/images/profile/user-3.jpg'
                                            "
                                            alt="Profile"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>
                                    <Link
                                        :href="route('update_profile')"
                                        class="absolute bottom-2 right-2 p-2 bg-white rounded-full shadow-md text-slate-600 hover:text-violet-600 transition-colors"
                                    >
                                        <Settings class="w-5 h-5" />
                                    </Link>
                                </div>
                            </div>

                            <!-- User Info -->
                            <div
                                class="flex-1 text-center md:text-left space-y-4"
                            >
                                <div>
                                    <h1
                                        class="text-3xl font-bold text-slate-900"
                                    >
                                        {{ user.name }}
                                    </h1>
                                    <p class="text-slate-500 font-medium">
                                        {{ user.email }}
                                    </p>
                                    <div
                                        v-if="!user.calendar_connected"
                                        class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-600 text-sm font-medium"
                                    >
                                        <AlertCircle class="w-4 h-4" />
                                        <span>Calendar not connected</span>
                                        <Link
                                            :href="
                                                route('google.calendar.connect')
                                            "
                                            class="underline hover:text-red-700"
                                            >Connect Now</Link
                                        >
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div
                                    class="flex flex-wrap justify-center md:justify-start gap-6 sm:gap-12 py-4 border-y border-slate-100"
                                >
                                    <div class="text-center md:text-left">
                                        <div
                                            class="text-2xl font-bold text-slate-900 flex items-center justify-center md:justify-start gap-2"
                                        >
                                            <Users
                                                class="w-5 h-5 text-violet-500"
                                            />
                                            <span>9</span>
                                        </div>
                                        <div
                                            class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1"
                                        >
                                            Members
                                        </div>
                                    </div>
                                    <div class="text-center md:text-left">
                                        <div
                                            class="text-2xl font-bold text-slate-900 flex items-center justify-center md:justify-start gap-2"
                                        >
                                            <Briefcase
                                                class="w-5 h-5 text-violet-500"
                                            />
                                            <span>{{ appointmentsCount }}</span>
                                        </div>
                                        <div
                                            class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1"
                                        >
                                            Appointments
                                        </div>
                                    </div>
                                    <div class="text-center md:text-left">
                                        <div
                                            class="text-2xl font-bold text-slate-900 flex items-center justify-center md:justify-start gap-2"
                                        >
                                            <CheckCircle
                                                class="w-5 h-5 text-violet-500"
                                            />
                                            <span>7</span>
                                        </div>
                                        <div
                                            class="text-xs text-slate-500 uppercase tracking-wider font-semibold mt-1"
                                        >
                                            Completed
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div
                                    class="flex flex-wrap justify-center md:justify-start gap-3"
                                >
                                    <Link
                                        v-if="user.roles.includes('client')"
                                        :href="
                                            route(
                                                'home_client',
                                                user.client?.slug_page
                                            )
                                        "
                                        class="px-5 py-2.5 rounded-xl bg-slate-900 text-white font-semibold text-sm hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20"
                                    >
                                        Client Dashboard
                                    </Link>
                                    <Link
                                        v-if="user.roles.includes('expert')"
                                        :href="
                                            route(
                                                'expert_detail',
                                                user.expert?.id
                                            )
                                        "
                                        class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-colors"
                                    >
                                        Expert Profile
                                    </Link>

                                    <div class="ml-auto hidden md:block">
                                        <Link
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="px-4 py-2.5 rounded-xl text-red-600 font-medium text-sm hover:bg-red-50 transition-colors flex items-center gap-2"
                                        >
                                            <LogOut class="w-4 h-4" />
                                            Sign Out
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <div
                        class="border-t border-slate-100 bg-slate-50/50 px-6 sm:px-10"
                    >
                        <nav
                            class="flex space-x-8 overflow-x-auto"
                            aria-label="Tabs"
                        >
                            <button
                                @click="activeTab = 'appointments'"
                                :class="[
                                    activeTab === 'appointments'
                                        ? 'border-violet-500 text-violet-600'
                                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300',
                                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors',
                                ]"
                            >
                                <Briefcase class="w-4 h-4" />
                                Appointments
                            </button>
                            <button
                                @click="activeTab = 'calendar'"
                                :class="[
                                    activeTab === 'calendar'
                                        ? 'border-violet-500 text-violet-600'
                                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300',
                                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors',
                                ]"
                            >
                                <CalendarIcon class="w-4 h-4" />
                                Calendar
                            </button>
                            <button
                                @click="activeTab = 'members'"
                                :class="[
                                    activeTab === 'members'
                                        ? 'border-violet-500 text-violet-600'
                                        : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300',
                                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors',
                                ]"
                            >
                                <Users class="w-4 h-4" />
                                User Members
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="mt-8">
                    <!-- Appointments Tab -->
                    <div v-if="activeTab === 'appointments'" class="space-y-6">
                        <div
                            v-if="appointments.length === 0"
                            class="text-center py-12 bg-white rounded-3xl shadow-sm border border-slate-100"
                        >
                            <Briefcase
                                class="w-12 h-12 text-slate-300 mx-auto mb-4"
                            />
                            <h3 class="text-lg font-medium text-slate-900">
                                No appointments yet
                            </h3>
                            <p class="text-slate-500 mt-1">
                                Your scheduled appointments will appear here.
                            </p>
                        </div>

                        <div v-else class="grid gap-4">
                            <div
                                v-for="appointment in appointments"
                                :key="appointment.id"
                                class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow"
                            >
                                <div
                                    class="flex flex-col md:flex-row justify-between md:items-center gap-4"
                                >
                                    <div class="flex items-start gap-4">
                                        <div
                                            class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center text-violet-600 font-bold text-lg"
                                        >
                                            {{
                                                isExpert
                                                    ? appointment.user.name.charAt(
                                                          0
                                                      )
                                                    : appointment.expert.user.name.charAt(
                                                          0
                                                      )
                                            }}
                                        </div>
                                        <div>
                                            <h4
                                                class="font-bold text-slate-900"
                                            >
                                                {{
                                                    isExpert
                                                        ? appointment.user.name
                                                        : appointment.expert
                                                              .user.name
                                                }}
                                            </h4>
                                            <p class="text-sm text-slate-500">
                                                {{
                                                    isExpert
                                                        ? appointment.user.email
                                                        : appointment.expert
                                                              .user.email
                                                }}
                                            </p>
                                            <div
                                                class="flex items-center gap-4 mt-2 text-sm text-slate-600"
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <Clock
                                                        class="w-4 h-4 text-slate-400"
                                                    />
                                                    {{
                                                        formatDate(
                                                            appointment.date_time
                                                        )
                                                    }}
                                                </div>
                                                <div
                                                    v-if="!isExpert"
                                                    class="flex items-center gap-1"
                                                >
                                                    <span
                                                        class="font-semibold text-slate-900"
                                                        >Rp
                                                        {{
                                                            new Intl.NumberFormat(
                                                                "id-ID"
                                                            ).format(
                                                                appointment
                                                                    .expert
                                                                    .price
                                                            )
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span
                                            :class="[
                                                'px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide',
                                                getStatusColor(
                                                    appointment.status
                                                ),
                                            ]"
                                        >
                                            {{ appointment.status }}
                                        </span>
                                        <Link
                                            :href="
                                                route(
                                                    'payment.show',
                                                    appointment.id
                                                )
                                            "
                                            class="p-2 text-slate-400 hover:text-violet-600 transition-colors"
                                        >
                                            <MoreHorizontal class="w-5 h-5" />
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calendar Tab -->
                    <div
                        v-if="activeTab === 'calendar'"
                        class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6"
                    >
                        <FullCalendar :options="calendarOptions" />
                    </div>

                    <!-- Members Tab -->
                    <div
                        v-if="activeTab === 'members'"
                        class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6"
                    >
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-slate-900">
                                User Members
                            </h3>
                            <span
                                class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold"
                                >20</span
                            >
                        </div>
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                        >
                            <!-- Dummy Member -->
                            <div
                                class="flex items-center gap-4 p-4 rounded-2xl border border-slate-100 hover:border-violet-200 transition-colors"
                            >
                                <img
                                    :src="assets.userPlaceholderUrl"
                                    alt="User"
                                    class="w-10 h-10 rounded-full"
                                />
                                <div>
                                    <h5
                                        class="font-bold text-slate-900 text-sm"
                                    >
                                        Ibnu Haris Al Mutaqin
                                    </h5>
                                    <p class="text-xs text-slate-500">
                                        al.ibnuharis@gmail.com
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style>
/* Custom FullCalendar Styles to match theme */
.fc {
    font-family: inherit;
}
.fc-toolbar-title {
    font-size: 1.25rem !important;
    font-weight: 700 !important;
    color: #0f172a;
}
.fc-button-primary {
    background-color: #fff !important;
    border-color: #e2e8f0 !important;
    color: #475569 !important;
    font-weight: 600 !important;
    text-transform: capitalize !important;
    padding: 0.5rem 1rem !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) !important;
}
.fc-button-primary:hover {
    background-color: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #1e293b !important;
}
.fc-button-active {
    background-color: #7c3aed !important;
    border-color: #7c3aed !important;
    color: #fff !important;
}
.fc-daygrid-day-number {
    color: #475569;
    font-weight: 500;
}
.fc-col-header-cell-cushion {
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    padding-bottom: 1rem !important;
}
.fc-event {
    border-radius: 0.375rem;
    padding: 2px 4px;
    font-size: 0.75rem;
    font-weight: 500;
    border: none;
}
</style>
