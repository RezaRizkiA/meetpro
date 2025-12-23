<script setup>
import { ref } from "vue";
import { Link, router, Head } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import {
    Calendar,
    Clock,
    ArrowLeft,
    Mail,
    Phone,
    Star,
    ArrowRightLeft,
    Copy,
    ExternalLink,
    CheckCircle,
    XCircle,
    Send,
    Edit,
    Shield,
    Globe,
    Video,
    X,
} from "lucide-vue-next";

defineOptions({ layout: DashboardLayout });

const props = defineProps({
    appointment: Object,
});

// Copy link state
const linkCopied = ref(false);

// Helper Formatting
const formatDate = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("en-US", {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
};

const formatTime = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });
};

const formatCurrency = (amount) => {
    if (!amount) return "Rp 0";
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(amount);
};

// Status Config
const statusConfig = {
    pending: {
        class: "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
        label: "Pending",
    },
    need_confirmation: {
        class: "bg-orange-500/20 text-orange-400 border-orange-500/30",
        label: "Need Confirmation",
    },
    confirmed: {
        class: "bg-green-500/20 text-green-400 border-green-500/30",
        label: "Confirmed",
    },
    scheduled: {
        class: "bg-blue-500/20 text-blue-400 border-blue-500/30",
        label: "Scheduled",
    },
    progress: {
        class: "bg-cyan-500/20 text-cyan-400 border-cyan-500/30",
        label: "In Progress",
    },
    completed: {
        class: "bg-emerald-500/20 text-emerald-400 border-emerald-500/30",
        label: "Completed",
    },
    declined: {
        class: "bg-red-500/20 text-red-400 border-red-500/30",
        label: "Declined",
    },
    cancelled: {
        class: "bg-red-500/20 text-red-400 border-red-500/30",
        label: "Cancelled",
    },
};

const paymentStatusConfig = {
    paid: {
        class: "bg-green-500/20 text-green-400 border-green-500/30",
        label: "Paid",
    },
    pending: {
        class: "bg-yellow-500/20 text-yellow-400 border-yellow-500/30",
        label: "Pending",
    },
    failed: {
        class: "bg-red-500/20 text-red-400 border-red-500/30",
        label: "Failed",
    },
    refunded: {
        class: "bg-purple-500/20 text-purple-400 border-purple-500/30",
        label: "Refunded",
    },
};

const getStatus = (status) =>
    statusConfig[status] || {
        class: "bg-gray-500/20 text-gray-400",
        label: status || "Unknown",
    };

const getPaymentStatus = (status) =>
    paymentStatusConfig[status] || {
        class: "bg-gray-500/20 text-gray-400",
        label: status || "Unknown",
    };

// Copy meeting link
const copyLink = () => {
    if (props.appointment?.location_url) {
        navigator.clipboard.writeText(props.appointment.location_url);
        linkCopied.value = true;
        setTimeout(() => {
            linkCopied.value = false;
        }, 2000);
    }
};

// Actions
const updateStatus = (newStatus) => {
    Swal.fire({
        title: "Are you sure?",
        text: `Change status to ${newStatus}?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3b82f6",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, update it!",
        background: "#1e293b",
        color: "#e2e8f0",
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(
                route(
                    "dashboard.appointments.update-status",
                    props.appointment.id
                ),
                { status: newStatus },
                { preserveScroll: true }
            );
        }
    });
};

const deleteAppointment = () => {
    Swal.fire({
        title: "Delete Appointment?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, delete it!",
        background: "#1e293b",
        color: "#e2e8f0",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(
                route("dashboard.appointments.destroy", props.appointment.id)
            );
        }
    });
};
</script>

<template>
    <Head title="Appointment Details" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-100 mb-3">
                    Appointment Details
                    <span class="text-slate-400">#ID-{{ appointment.id }}</span>
                </h1>
                <div class="flex items-center gap-2">
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold border flex items-center gap-1.5"
                        :class="getStatus(appointment.status).class"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-current"
                        ></span>
                        {{ getStatus(appointment.status).label }}
                    </span>
                    <span
                        v-if="appointment.transaction"
                        class="px-3 py-1 rounded-full text-xs font-semibold border"
                        :class="
                            getPaymentStatus(appointment.transaction?.status)
                                .class
                        "
                    >
                        {{
                            getPaymentStatus(appointment.transaction?.status)
                                .label
                        }}
                    </span>
                </div>
            </div>
            <Link
                :href="route('dashboard.appointments.index')"
                class="flex items-center gap-2 text-slate-400 hover:text-slate-200 transition-colors text-sm"
            >
                <ArrowLeft class="w-4 h-4" />
                Back to Appointment List
            </Link>
        </div>

        <!-- Participants Section -->
        <div>
            <h2 class="text-lg font-bold text-slate-200 mb-4">Participants</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Client Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6"
                >
                    <div
                        class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4"
                    >
                        CLIENT
                    </div>
                    <div class="flex items-start gap-4 mb-6">
                        <div
                            class="w-14 h-14 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center text-white font-bold text-xl shrink-0"
                        >
                            {{
                                appointment.user?.name
                                    ? appointment.user.name
                                          .substring(0, 2)
                                          .toUpperCase()
                                    : "CL"
                            }}
                        </div>
                        <div class="flex-1">
                            <div class="text-lg font-bold text-slate-100">
                                {{ appointment.user?.name || "Unknown Client" }}
                            </div>
                            <div class="text-sm text-slate-400">
                                {{
                                    appointment.user?.company ||
                                    appointment.company_name ||
                                    "-"
                                }}
                            </div>
                        </div>
                        <button
                            class="p-2 text-slate-500 hover:text-slate-300 hover:bg-slate-700/50 rounded-lg transition-colors"
                        >
                            <ArrowRightLeft class="w-5 h-5" />
                        </button>
                    </div>
                    <div class="space-y-3 pt-4 border-t border-slate-700/50">
                        <div class="flex items-center gap-3 text-sm">
                            <Mail class="w-4 h-4 text-slate-500" />
                            <span class="text-slate-300">{{
                                appointment.user?.email || "-"
                            }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <Phone class="w-4 h-4 text-slate-500" />
                            <span class="text-slate-300">{{
                                appointment.user?.phone || "+62 812 3456 7890"
                            }}</span>
                        </div>
                    </div>
                </div>

                <!-- Expert Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="text-xs font-semibold text-slate-500 uppercase tracking-wider"
                        >
                            EXPERT
                        </div>
                        <div
                            class="flex items-center gap-1 bg-yellow-500/20 px-2 py-1 rounded-lg"
                        >
                            <Star
                                class="w-3.5 h-3.5 text-yellow-400 fill-yellow-400"
                            />
                            <span class="text-xs font-bold text-yellow-400">{{
                                appointment.expert?.rating || "4.9"
                            }}</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 mb-6">
                        <div
                            class="w-14 h-14 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-white shrink-0 overflow-hidden"
                        >
                            <img
                                v-if="appointment.expert?.user?.avatar"
                                :src="appointment.expert.user.avatar"
                                class="w-full h-full object-cover"
                            />
                            <span v-else class="font-bold text-xl">{{
                                appointment.expert?.user?.name
                                    ? appointment.expert.user.name
                                          .substring(0, 2)
                                          .toUpperCase()
                                    : "EX"
                            }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="text-lg font-bold text-slate-100">
                                {{
                                    appointment.expert?.user?.name ||
                                    "Unknown Expert"
                                }}
                            </div>
                            <div class="text-sm text-cyan-400">
                                {{
                                    appointment.skill?.name ||
                                    "Marketing Strategy"
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3 pt-4 border-t border-slate-700/50">
                        <div class="flex items-center gap-3 text-sm">
                            <Shield class="w-4 h-4 text-slate-500" />
                            <span class="text-slate-300"
                                >Certified Consultant</span
                            >
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <Globe class="w-4 h-4 text-slate-500" />
                            <span class="text-slate-300"
                                >English, Bahasa Indonesia</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Session Topic -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Session Topic Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6"
                >
                    <div
                        class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2"
                    >
                        SESSION TOPIC
                    </div>
                    <h3 class="text-2xl font-bold text-slate-100 mb-6">
                        "{{
                            appointment.skill?.name ||
                            appointment.topic ||
                            "Konsultasi Strategi Marketing Q4"
                        }}"
                    </h3>

                    <div
                        class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3"
                    >
                        NOTES / AGENDA
                    </div>
                    <p class="text-slate-300 leading-relaxed">
                        {{
                            appointment.topic ||
                            "Discussion regarding the budget allocation for Q4 marketing campaigns, reviewing performance of previous ads, and setting up KPI targets for the upcoming holiday season. Client requested specific focus on digital channels."
                        }}
                    </p>
                </div>
            </div>

            <!-- Right Column: Date/Time & Meeting Link -->
            <div class="space-y-6">
                <!-- Date & Time Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="text-xs font-semibold text-slate-500 uppercase tracking-wider"
                        >
                            DATE & TIME
                        </div>
                        <div
                            class="flex items-center gap-1.5 bg-green-500/20 px-2 py-1 rounded-lg border border-green-500/30"
                        >
                            <CheckCircle class="w-3.5 h-3.5 text-green-400" />
                            <span class="text-xs font-semibold text-green-400"
                                >SYNCED</span
                            >
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-cyan-500/20 flex items-center justify-center"
                        >
                            <Calendar class="w-6 h-6 text-cyan-400" />
                        </div>
                        <div>
                            <div class="text-lg font-bold text-slate-100">
                                {{ formatDate(appointment.date_time) }}
                            </div>
                            <div class="text-sm text-slate-400">
                                {{ formatTime(appointment.date_time) }} ({{
                                    appointment.hours || 1
                                }}
                                Hour{{ appointment.hours > 1 ? "s" : "" }})
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Meeting Link Card -->
                <div
                    class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-6"
                >
                    <div
                        class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4"
                    >
                        LOCATION (MEETING LINK)
                    </div>
                    <div
                        class="flex items-center gap-3 bg-slate-900/50 rounded-xl p-3 mb-4"
                    >
                        <Video class="w-5 h-5 text-blue-400 shrink-0" />
                        <span class="text-sm text-slate-300 truncate flex-1">
                            {{
                                appointment.location_url ||
                                "https://meet.google.com/abc-defg-hij"
                            }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            @click="copyLink"
                            class="flex items-center justify-center gap-2 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-xl text-sm font-medium transition-colors"
                        >
                            <Copy class="w-4 h-4" />
                            {{ linkCopied ? "Copied!" : "Copy Link" }}
                        </button>
                        <a
                            :href="appointment.location_url || '#'"
                            target="_blank"
                            class="flex items-center justify-center gap-2 py-2.5 bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 rounded-xl text-sm font-medium transition-colors border border-cyan-500/30"
                        >
                            Open Meeting
                            <ExternalLink class="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Footer -->
        <div
            class="bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 p-4"
        >
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Transaction Info -->
                <div class="flex items-center gap-4">
                    <div>
                        <div class="text-xs text-slate-500">
                            Total Transaction
                        </div>
                        <div class="text-xl font-bold text-slate-100">
                            {{
                                formatCurrency(
                                    appointment.transaction?.amount ||
                                        appointment.price ||
                                        0
                                )
                            }}
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button
                        v-if="
                            !['completed', 'cancelled', 'declined'].includes(
                                appointment.status
                            )
                        "
                        @click="updateStatus('cancelled')"
                        class="flex items-center gap-2 px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-xl text-sm font-medium transition-colors"
                    >
                        <XCircle class="w-4 h-4" />
                        Cancel Appointment
                    </button>
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-xl text-sm font-medium transition-colors"
                    >
                        <Send class="w-4 h-4" />
                        Resend Email
                    </button>
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-xl text-sm font-medium transition-colors"
                    >
                        <Edit class="w-4 h-4" />
                        Edit Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
